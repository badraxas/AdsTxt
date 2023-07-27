<?php

use Badraxas\Adstxt\AdsTxt;
use Badraxas\Adstxt\Enums\AccountType;
use Badraxas\Adstxt\Lines\Blank;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Invalid;
use Badraxas\Adstxt\Lines\Variable;
use Badraxas\Adstxt\Lines\Vendor;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class AdsTxtTest extends TestCase
{
    public function testAdsTxtOutput(): void
    {
        $adsTxt = new AdsTxt();
        $adsTxt
            ->addLine(new Comment(' ads.txt file for example.com:'))
            ->addLine(new Vendor('greenadexchange.com', 12345, AccountType::DIRECT, 'd75815a79'))
            ->addLine(new Vendor('blueadexchange.com', 'XF436', AccountType::DIRECT))
            ->addLine(new Variable('subdomain', 'divisionone.example.com'))
        ;

        $this->assertEquals('# ads.txt file for example.com:
greenadexchange.com, 12345, DIRECT, d75815a79
blueadexchange.com, XF436, DIRECT
subdomain=divisionone.example.com', $adsTxt->__toString());
    }

    public function testDiffMethodReturnsMissingLines(): void
    {
        $adsTxt1 = new AdsTxt();
        $adsTxt2 = new AdsTxt();

        $line1 = new Vendor('example.com', 'pub-123456789', AccountType::DIRECT, 'abc123');
        $line2 = new Vendor('example.com', 'pub-987654321', AccountType::RESELLER, 'xyz456');
        $line3 = new Vendor('example.com', 'pub-444555666', AccountType::DIRECT, 'def789');

        $adsTxt1->addLine($line1)->addLine($line2);

        $adsTxt2->addLine($line1)->addLine($line3);

        $missingLines = $adsTxt1->diff($adsTxt2);

        $this->assertCount(1, $missingLines);
        $this->assertEquals($line2, $missingLines[0]);
    }

    public function testEquality(): void
    {
        $adsTxt1 = (new AdsTxt())
            ->addLine(new Comment(' First line of file'))
            ->addLine(new Blank())
            ->addLine(new Vendor(
                'greenadexchange.com',
                'XF436',
                AccountType::DIRECT,
                'd75815a79',
                new Comment(' GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $adsTxt2 = (new AdsTxt())
            ->addLine(new Comment(' First line of file'))
            ->addLine(new Blank())
            ->addLine(new Vendor(
                'greenadexchange.com',
                'XF436',
                AccountType::DIRECT,
                'd75815a79',
                new Comment(' GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $this->assertTrue($adsTxt1->equals($adsTxt2));
        $this->assertTrue($adsTxt2->equals($adsTxt1));
    }

    public function testFIlter(): void
    {
        $adsTxt = (new AdsTxt())
            ->addLine(new Comment(' First line of file'))
            ->addLine(new Blank())
            ->addLine(new Vendor(
                'greenadexchange.com',
                'XF436',
                AccountType::DIRECT,
                'd75815a79',
                new Comment(' GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $adsTxtExpected = (new AdsTxt())
            ->addLine(new Vendor(
                'greenadexchange.com',
                'XF436',
                AccountType::DIRECT,
                'd75815a79',
                new Comment(' GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $this->assertEquals($adsTxtExpected, $adsTxt->filter(fn ($AdsTxtLine) => $AdsTxtLine instanceof Vendor || $AdsTxtLine instanceof Variable));
    }

    public function testInvalidLine(): void
    {
        $adsTxt = new AdsTxt();
        $adsTxt->addLine(new Invalid('This line is invalid'))
            ->addLine(new Comment(' This is a valid comment'))
            ->addLine(new Invalid('This, Is Invalid'))
        ;

        $this->assertFalse($adsTxt->isValid());
        $this->assertEquals([
            0 => new Invalid('This line is invalid'),
            2 => new Invalid('This, Is Invalid'),
        ], $adsTxt->getInvalidLines());
    }

    public function testNonEquality(): void
    {
        $adsTxt1 = (new AdsTxt())
            ->addLine(new Comment(' First line of file'))
            ->addLine(new Blank())
            ->addLine(new Vendor(
                'greenadexchange.com',
                'XF436',
                AccountType::DIRECT,
                'd75815a79',
                new Comment(' GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $adsTxt2 = (new AdsTxt())
            ->addLine(new Comment(' First line of file'))
            ->addLine(new Vendor(
                'greenadexchange.com',
                'XF436',
                AccountType::DIRECT,
                'd75815a79',
                new Comment(' GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $this->assertFalse($adsTxt1->equals($adsTxt2));
        $this->assertFalse($adsTxt2->equals($adsTxt1));
    }
}
