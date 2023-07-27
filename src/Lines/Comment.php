<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

class Comment implements AdsTxtLineInterface
{
    public function __construct(private readonly string $comment)
    {
    }

    public function __toString(): string
    {
        return sprintf('#%s', $this->comment);
    }
}