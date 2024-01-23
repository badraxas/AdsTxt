<?php

namespace Parsers;

use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Variable;
use Badraxas\Adstxt\Parsers\VariableParser;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class VariableParserTest extends TestCase
{
    public function testInvalidParsing(): void
    {
        $parser = new VariableParser();

        $parsedLine = $parser->parse('VARIABLE=VALUE #test');

        $this->assertEquals((new Variable('VARIABLE', 'VALUE', new Comment('test')))->pretty(), $parsedLine->pretty());
    }
}
