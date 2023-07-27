<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

/**
 * Class Blank.
 *
 * Represents a blank line in the ads.txt file.
 */
class Blank implements AdsTxtLineInterface
{
    /**
     * Get the string representation of the Blank line.
     *
     * @return string returns an empty string representing the Blank line
     */
    public function __toString(): string
    {
        return '';
    }
}
