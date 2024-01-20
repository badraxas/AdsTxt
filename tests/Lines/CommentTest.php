<?php

namespace Lines;

use Badraxas\Adstxt\Lines\Comment;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CommentTest extends TestCase
{
    public function testGetters(): void
    {
        $comment = new Comment(' ads.txt file for example.com:');
        $this->assertEquals(' ads.txt file for example.com:', $comment->getComment());
    }
}
