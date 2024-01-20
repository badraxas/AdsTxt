<?php

namespace Parsers;

use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Parsers\CommentParser;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CommentParserTest extends TestCase
{
    public function testCommentParsing(): void
    {
        $parser = new CommentParser();

        $parsedLine = $parser->parse('# a comment');

        $this->assertEquals(new Comment(' a comment'), $parsedLine);
    }
}
