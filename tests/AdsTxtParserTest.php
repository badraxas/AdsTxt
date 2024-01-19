<?php

use Badraxas\Adstxt\AdsTxt;
use Badraxas\Adstxt\AdsTxtParser;
use Badraxas\Adstxt\Enums\Relationship;
use Badraxas\Adstxt\Lines\Blank;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Invalid;
use Badraxas\Adstxt\Lines\Record;
use Badraxas\Adstxt\Lines\Variable;
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
        $adsTxtParser = new AdsTxtParser();
        $adsTxt = $adsTxtParser->fromFile(__DIR__.'/test_files/ads.txt');
        $adsTxtReference = (new AdsTxt())
            ->addLine(new Comment(' ads.txt file for divisionone.example.com:'))
            ->addLine(new Record('silverssp.com', 5569, Relationship::DIRECT, 'f496211'))
            ->addLine(new Record('orangeexchange.com', 'AB345', Relationship::RESELLER))
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
            ->addLine(new Record(
                'greenadexchange.com',
                'XF436',
                Relationship::DIRECT,
                'd75815a79',
                new Comment(' GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $adsTxtParser = new AdsTxtParser();
        $adsTxt = $adsTxtParser->fromString($adsTxtString);

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
            ->addLine(new Invalid('greenadexchange.com, XF436, DIRECT, d75815a79, GreenAd certification ID', 'Record contains more than 4 comma separated values and is therefore improperly formatted'))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $adsTxtParser = new AdsTxtParser();
        $adsTxt = $adsTxtParser->fromString($adsTxtString);

        $this->assertInstanceOf(AdsTxt::class, $adsTxt);

        $this->assertEquals($adsTxtReference, $adsTxt);

        $this->assertEquals(
            $adsTxtString,
            $adsTxt->__toString()
        );
    }
}
