<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

class Variable implements AdsTxtLineInterface
{
    public function __construct(private readonly string $name, private $value, private readonly ?Comment $comment = null)
    {
    }

    public function __toString(): string
    {
        if (!isset($this->comment)) {
            return sprintf('%s=%s', $this->name, $this->value);
        }

        return sprintf('%s=%s%s', $this->name, $this->value, $this->comment);
    }
}
