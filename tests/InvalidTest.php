<?php

use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Invalid;
use Badraxas\Adstxt\Lines\Variable;
use PHPUnit\Framework\TestCase;

final class InvalidTest extends TestCase
{
    public function testInvalidOutput(): void
    {
        $invalid = new Invalid('Invalid line', 'A good reason!', new Comment('Invalid!'));
        $this->assertEquals('A good reason!', $invalid->getReason());
        $this->assertEquals('Invalid line #Invalid!', $invalid->__toString());
    }

    public function testInvalidEquality(): void
    {
        $invalid1 = new Invalid('Invalid line', 'A good reason!', new Comment('Invalid!'));
        $invalid2 = new Invalid('Invalid line', 'A good reason!', new Comment('Invalid!'));

        $this->assertTrue($invalid1->equals($invalid2));
    }
}
