<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

/**
 * Class Blank.
 *
 * Represents a blank line in the ads.txt file.
 */
class Blank extends AbstractAdsTxtLine
{
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
        return $adsTxtLine instanceof Blank && $adsTxtLine->pretty() === $this->pretty();
    }

    public function pretty(bool $withComment = true): string
    {
        return '';
    }
}
