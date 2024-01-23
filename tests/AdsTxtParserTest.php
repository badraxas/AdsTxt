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
use Badraxas\Adstxt\Parsers\ParserFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class AdsTxtParserTest extends TestCase
{
    public function testInvalidParser(): void
    {
        $this->assertEquals((new Invalid('an invalid line', new Comment('test')))->pretty(), ParserFactory::getParser('an invalid line#test')->parse('an invalid line#test')->pretty());
    }

    public function testParseFromFile(): void
    {
        $adsTxtParser = new AdsTxtParser();
        $adsTxt = $adsTxtParser->fromFile(__DIR__.'/test_files/ads.txt');
        $adsTxtReference = (new AdsTxt())
            ->addLine(new Comment('ads.txt file for divisionone.example.com:'))
            ->addLine(new Record('silverssp.com', 5569, 'DIRECT', 'f496211f496211'))
            ->addLine(new Record('orangeexchange.com', 'AB345', 'RESELLER'))
        ;

        $this->assertInstanceOf(AdsTxt::class, $adsTxt);

        $this->assertEquals($adsTxtReference->pretty(), $adsTxt->pretty());
    }

    public function testParseFromInvalidString(): void
    {
        $adsTxtString = "greenadexchange.com, XF436\nVARIABLE=john=doe\ndomain.com,1234,DIRECT\"\ndomain@domain.tld,abcd,DIRECT";

        $adsTxtReference = (new AdsTxt())
            ->addLine(new Invalid('greenadexchange.com, XF436'))
            ->addLine(new Invalid('VARIABLE=john=doe'))
            ->addLine(new Invalid('domain.com, 1234, DIRECT"'))
            ->addLine(new Invalid('domain@domain.tld, abcd, DIRECT'))
        ;

        $adsTxtParser = new AdsTxtParser();
        $adsTxt = $adsTxtParser->fromString($adsTxtString);

        $this->assertInstanceOf(AdsTxt::class, $adsTxt);

        $this->assertEquals($adsTxtReference->pretty(), $adsTxt->pretty());
    }

    public function testParseFromMissingFile(): void
    {
        $adsTxtParser = new AdsTxtParser();

        $this->expectException(FileOpenException::class);
        @$adsTxtParser->fromFile(__DIR__.'/test_files/missing_ads.txt');
    }

    public function testParseFromString(): void
    {
        $adsTxtString = "# First line of file\n\ngreenadexchange.com, XF436, DIRECT, d75815a79# GreenAd certification ID\ncontact=contact@example.org";

        $adsTxtReference = (new AdsTxt())
            ->addLine(new Comment('First line of file'))
            ->addLine(new Blank())
            ->addLine(new Record(
                'greenadexchange.com',
                'XF436',
                'DIRECT',
                'd75815a79',
                new Comment('GreenAd certification ID')
            ))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $adsTxtParser = new AdsTxtParser();
        $adsTxt = $adsTxtParser->fromString($adsTxtString);

        $this->assertInstanceOf(AdsTxt::class, $adsTxt);

        $this->assertEquals($adsTxtReference->pretty(), $adsTxt->pretty());

        $this->assertEquals(
            $adsTxtString,
            $adsTxt->pretty()
        );
    }

    public function testParseFromStringWithInvalidLines(): void
    {
        $adsTxtString = "# First line of file\ngreenadexchange.com, XF436, DIRECT, d75815a79, GreenAd certification ID\ncontact=contact@example.org";

        $adsTxtReference = (new AdsTxt())
            ->addLine(new Comment('First line of file'))
            ->addLine(new Invalid('greenadexchange.com, XF436, DIRECT, d75815a79, GreenAd certification ID'))
            ->addLine(new Variable('contact', 'contact@example.org'))
        ;

        $adsTxtParser = new AdsTxtParser();
        $adsTxt = $adsTxtParser->fromString($adsTxtString);

        $this->assertInstanceOf(AdsTxt::class, $adsTxt);

        $this->assertEquals($adsTxtReference->pretty(), $adsTxt->pretty());

        $this->assertEquals(
            $adsTxtString,
            $adsTxt->pretty()
        );
    }
}
