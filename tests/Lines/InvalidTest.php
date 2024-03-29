<?php

namespace Lines;

use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Invalid;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class InvalidTest extends TestCase
{
    public function testGetters(): void
    {
        $invalid = new Invalid('an invalid line', new Comment(' a nice comment!'));

        $this->assertEquals('an invalid line', $invalid->getValue());
        $this->assertEquals(new Comment(' a nice comment!'), $invalid->getComment());
    }

    public function testInvalidEquality(): void
    {
        $invalid1 = new Invalid('Invalid line', new Comment('Invalid!'));
        $invalid2 = new Invalid('Invalid line', new Comment('Invalid!'));

        $this->assertTrue($invalid1->equals($invalid2));
    }

    public function testInvalidOutput(): void
    {
        $invalid = new Invalid('Invalid line', new Comment('Invalid!'));

        $this->assertEquals('Invalid line# Invalid!', $invalid->pretty());
    }
}
