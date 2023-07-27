<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

class Invalid implements AdsTxtLineInterface
{
    public function __construct(private readonly string $value, private readonly ?Comment $comment = null)
    {
    }

    public function __toString(): string
    {
        if (!isset($this->comment)) {
            return $this->value;
        }

        return sprintf('%s%s', $this->value, $this->comment);
    }
}
