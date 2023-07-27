<?php

use Badraxas\Adstxt\AdsTxt;
use Badraxas\Adstxt\AdsTxtParser;
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
class AdsTxtParserTest extends TestCase
{
    public function testParseFromFile(): void
    {
        $adsTxt = AdsTxtParser::fromFile(__DIR__.'/test_files/ads.txt');
        $adsTxtReference = (new AdsTxt())
            ->addLine(new Comment(' ads.txt file for divisionone.example.com:'))
            ->addLine(new Vendor('silverssp.com', 5569, AccountType::DIRECT, 'f496211'))
            ->addLine(new Vendor('orangeexchange.com', 'AB345', AccountType::RESELLER))
        ;

        $this->assertInstanceOf(AdsTxt::class, $adsTxt);

        $this->assertEquals($adsTxtReference, $adsTxt);
    }

    public function testParseFromString(): void
    {
        $adsTxtString = "# First line of file\n\ngreenadexchange.com, XF436, DIRECT, d75815a79 # GreenAd certification ID\ncontact=contact@example.org";

        $adsTxtReference = (new AdsTxt())
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

        $adsTxt = AdsTxtParser::fromString($adsTxtString);

        $this->assertInstanceOf(AdsTxt::class, $adsTxt);

        $this->assertEquals($adsTxtReference, $adsTxt);

        $this->assertEquals(
            $adsTxtString,
            $adsTxt->__toString()
        );
    }

    public function testParseFromStringWithInvalidLines(): void
    {
        $adsTxtString = "# First line of file\ngreenadexchange.com, XF436, DIRECT, d75815a79, GreenAd certification ID\ncontact=contact@example.org";

        $adsTxtReference = (new AdsTxt())
            ->addLine(new Comment(' First line of file'))
            ->addLine(new Invalid('greenadexchange.com, XF436, DIRECT, d75815a79, GreenAd certification ID'))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $adsTxt = AdsTxtParser::fromString($adsTxtString);

        $this->assertInstanceOf(AdsTxt::class, $adsTxt);

        $this->assertEquals($adsTxtReference, $adsTxt);

        $this->assertEquals(
            $adsTxtString,
            $adsTxt->__toString()
        );
    }
}
