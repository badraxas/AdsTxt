<?php

namespace Badraxas\Adstxt\Interfaces;

/**
 * Interface AdsTxtLineInterface.
 *
 * Represents an interface for lines in the ads.txt file.
 * Implementing classes should provide their own custom logic for the __toString() method.
 */
interface AdsTxtLineInterface
{
    public function equals(AdsTxtLineInterface $adsTxtLine): bool;

    public function pretty(bool $withComment = true): string;
}
