<?php

namespace Parsers;

use Badraxas\Adstxt\Enums\Relationship;
use Badraxas\Adstxt\Lines\Record;
use Badraxas\Adstxt\Parsers\RecordParser;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RecordParserTest extends TestCase
{
    public function testInvalidParsing(): void
    {
        $parser = new RecordParser();

        $parsedLine = $parser->parse('aps.amazon.com, 12345, DIRECT');

        $this->assertEquals(new Record('aps.amazon.com', 12345, Relationship::DIRECT), $parsedLine);
    }
}
