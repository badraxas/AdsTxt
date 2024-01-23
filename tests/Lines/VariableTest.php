<?php

namespace Lines;

use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Variable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class VariableTest extends TestCase
{
    public function testGetters(): void
    {
        $variable = new Variable('contact', 'adops@example.com', new Comment(' a nice comment!'));

        $this->assertEquals('contact', $variable->getName());
        $this->assertEquals('adops@example.com', $variable->getValue());
        $this->assertEquals(new Comment(' a nice comment!'), $variable->getComment());
    }

    public function testVariableOutput(): void
    {
        $variable = new Variable('contact', 'adops@example.com');
        $this->assertEquals('contact=adops@example.com', $variable->pretty());
    }

    public function testVariableWithComment(): void
    {
        $variable = new Variable('contact', 'adops@example.com', new Comment('a nice comment!'));
        $this->assertEquals('contact=adops@example.com# a nice comment!', $variable->pretty());
    }
}
