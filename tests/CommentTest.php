<?php

use Badraxas\Adstxt\Lines\Comment;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class CommentTest extends TestCase
{
    public function testCommentOutput(): void
    {
        $comment = new Comment(' ads.txt file for example.com:');
        $this->assertEquals('# ads.txt file for example.com:', $comment->__toString());
    }
}
