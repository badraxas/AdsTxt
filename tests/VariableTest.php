<?php

use Badraxas\Adstxt\Lines\Variable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class VariableTest extends TestCase
{
    public function testVariableOutput(): void
    {
        $variable = new Variable('contact', 'adops@example.com');
        $this->assertEquals('contact=adops@example.com', $variable->__toString());
    }
}
