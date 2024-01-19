<?php

use Badraxas\Adstxt\AdsTxt;
use Badraxas\Adstxt\AdsTxtParser;
use Badraxas\Adstxt\Enums\Relationship;
use Badraxas\Adstxt\Exceptions\AdsTxtParser\FileOpenException;
use Badraxas\Adstxt\Lines\Blank;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Invalid;
use Badraxas\Adstxt\Lines\Record;
use Badraxas\Adstxt\Lines\Variable;
use PHPUnit\Framework\TestCase;

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

    public function testParseFromMissingFile(): void
    {
        $adsTxtParser = new AdsTxtParser();

        $this->expectException(FileOpenException::class);
        $adsTxtParser->fromFile(__DIR__.'/test_files/missing_ads.txt');
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

    public function testParseFromInvalidString(): void
    {
        $adsTxtString = "greenadexchange.com, XF436\nVARIABLE=john=doe\ndomain.com,1234,DIRECT\"\ndomain@domain.tld,abcd,DIRECT";

        $adsTxtReference = (new AdsTxt())
            ->addLine(new Invalid('greenadexchange.com, XF436', 'Record contains less than 3 comma separated values and is therefore improperly formatted.'))
            ->addLine(new Invalid('VARIABLE=john=doe', 'Line appears invalid, it does not validate as a record, variable or comment.'))
            ->addLine(new Invalid('domain.com,1234,DIRECT"', "Relationship value must be 'DIRECT' or 'RESELLER'."))
            ->addLine(new Invalid('domain@domain.tld,abcd,DIRECT', 'Domain "domain@domain.tld" does not appear valid.'))
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
