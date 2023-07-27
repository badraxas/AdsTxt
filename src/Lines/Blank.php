<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

class Blank implements AdsTxtLineInterface
{
    public function __toString(): string
    {
        return '';
    }
}
