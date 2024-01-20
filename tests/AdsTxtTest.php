<?php

use Badraxas\Adstxt\AdsTxt;
use Badraxas\Adstxt\Enums\Relationship;
use Badraxas\Adstxt\Lines\Blank;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Invalid;
use Badraxas\Adstxt\Lines\Record;
use Badraxas\Adstxt\Lines\Variable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class AdsTxtTest extends TestCase
{
    public function testAdsTxtOutput(): void
    {
        $adsTxt = new AdsTxt();
        $adsTxt
            ->addLine(new Comment(' ads.txt file for example.com:'))
            ->addLine(new Record('greenadexchange.com', 12345, Relationship::DIRECT, 'd75815a79'))
            ->addLine(new Record('blueadexchange.com', 'XF436', Relationship::DIRECT))
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

        $line1 = new Record('example.com', 'pub-123456789', Relationship::DIRECT, 'abc123');
        $line2 = new Record('example.com', 'pub-987654321', Relationship::RESELLER, 'afe456');
        $line3 = new Record('example.com', 'pub-444555666', Relationship::DIRECT, 'def789');

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
            ->addLine(new Record(
                'greenadexchange.com',
                'XF436',
                Relationship::DIRECT,
                'd75815a79',
                new Comment(' GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $adsTxt2 = (new AdsTxt())
            ->addLine(new Comment(' First line of file'))
            ->addLine(new Blank())
            ->addLine(new Record(
                'greenadexchange.com',
                'XF436',
                Relationship::DIRECT,
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
            ->addLine(new Record(
                'greenadexchange.com',
                'XF436',
                Relationship::DIRECT,
                'd75815a79',
                new Comment(' GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $adsTxtExpected = (new AdsTxt())
            ->addLine(new Record(
                'greenadexchange.com',
                'XF436',
                Relationship::DIRECT,
                'd75815a79',
                new Comment(' GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $this->assertEquals($adsTxtExpected, $adsTxt->filter(fn ($AdsTxtLine) => $AdsTxtLine instanceof Record || $AdsTxtLine instanceof Variable));
    }

    public function testInvalidLine(): void
    {
        $adsTxt = new AdsTxt();
        $adsTxt->addLine(new Invalid('This line is invalid', 'Record contains less than 3 comma separated values and is therefore improperly formatted.'))
            ->addLine(new Comment(' This is a valid comment'))
            ->addLine(new Invalid('This, Is Invalid', 'Record contains less than 3 comma separated values and is therefore improperly formatted.'))
        ;

        $this->assertFalse($adsTxt->isValid());
        $this->assertEquals([
            0 => new Invalid('This line is invalid', 'Record contains less than 3 comma separated values and is therefore improperly formatted.'),
            2 => new Invalid('This, Is Invalid', 'Record contains less than 3 comma separated values and is therefore improperly formatted.'),
        ], $adsTxt->getInvalidLines());
    }

    public function testNonEquality(): void
    {
        $adsTxt1 = (new AdsTxt())
            ->addLine(new Comment(' First line of file'))
            ->addLine(new Blank())
            ->addLine(new Record(
                'greenadexchange.com',
                'XF436',
                Relationship::DIRECT,
                'd75815a79',
                new Comment(' GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $adsTxt2 = (new AdsTxt())
            ->addLine(new Comment(' First line of file'))
            ->addLine(new Record(
                'greenadexchange.com',
                'XF436',
                Relationship::DIRECT,
                'd75815a79',
                new Comment(' GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $this->assertFalse($adsTxt1->equals($adsTxt2));
        $this->assertFalse($adsTxt2->equals($adsTxt1));

        $adsTxt1 = (new AdsTxt())
            ->addLine(new Comment(' First line of file'))
            ->addLine(new Record(
                'greenadexcange.com',
                'XF436',
                Relationship::DIRECT,
                'd75815a79',
                new Comment(' GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $adsTxt2 = (new AdsTxt())
            ->addLine(new Comment(' First line of file'))
            ->addLine(new Record(
                'greenadexchange.com',
                'XF436',
                Relationship::DIRECT,
                'd75815a79',
                new Comment(' GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $this->assertFalse($adsTxt1->equals($adsTxt2));
        $this->assertFalse($adsTxt2->equals($adsTxt1));

        $adsTxt1 = (new AdsTxt())
            ->addLine(new Invalid('@@@@@', 'Wrong format'))
        ;

        $adsTxt2 = (new AdsTxt())
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $this->assertFalse($adsTxt1->equals($adsTxt2));
        $this->assertFalse($adsTxt2->equals($adsTxt1));
    }
}
