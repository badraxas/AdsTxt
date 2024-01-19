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

    /**
     * Compares the current Record object with another AdsTxtLineInterface object.
     *
     * @param AdsTxtLineInterface $adsTxtLine the AdsTxtLineInterface object to compare with
     *
     * @return bool returns true if the provided object is an instance of Record and
     *              its string representation is equal to the string representation of the current object
     */
    public function equals(AdsTxtLineInterface $adsTxtLine): bool
    {
        return $adsTxtLine instanceof Blank && $adsTxtLine->__toString() === $this->__toString();
    }
}
