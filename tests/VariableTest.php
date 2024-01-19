<?php

use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Variable;
use PHPUnit\Framework\TestCase;

final class VariableTest extends TestCase
{
    public function testVariableOutput(): void
    {
        $variable = new Variable('contact', 'adops@example.com');
        $this->assertEquals('contact=adops@example.com', $variable->__toString());
    }

    public function testVariableWithComment(): void
    {
        $variable = new Variable('contact', 'adops@example.com', new Comment(' a nice comment!'));
        $this->assertEquals('contact=adops@example.com # a nice comment!', $variable->__toString());
    }
}
