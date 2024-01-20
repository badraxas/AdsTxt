<?php

namespace Parsers;

use Badraxas\Adstxt\Enums\Relationship;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Record;
use Badraxas\Adstxt\Lines\Variable;
use Badraxas\Adstxt\Parsers\RecordParser;
use Badraxas\Adstxt\Parsers\VariableParser;
use PHPUnit\Framework\TestCase;

final class VariableParserTest extends TestCase
{
    public function testInvalidParsing(): void
    {
        $parser = new VariableParser();

        $parsedLine = $parser->parse('VARIABLE=VALUE');

        $this->assertEquals(new Variable('VARIABLE', 'VALUE'), $parsedLine);
    }
}
