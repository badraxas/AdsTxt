<?php

namespace Parsers;

use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Invalid;
use Badraxas\Adstxt\Parsers\InvalidParser;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class InvalidParserTest extends TestCase
{
    public function testInvalidParsing(): void
    {
        $parser = new InvalidParser();

        $parsedLine = $parser->parse('an invalid line');

        $this->assertEquals((new Invalid('an invalid line'))->pretty(), $parsedLine->pretty());
    }

    public function testInvalidParsingWithComment(): void
    {
        $parser = new InvalidParser();

        $parsedLine = $parser->parse('an invalid line#with comment');

        $this->assertEquals((new Invalid(
            'an invalid line',
            new Comment('with comment')
        ))->pretty(), $parsedLine->pretty());
    }
}
