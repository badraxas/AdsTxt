<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

/**
 * Class Comment.
 *
 * Represents a comment line in the ads.txt file.
 */
class Comment implements AdsTxtLineInterface
{
    /**
     * Comment constructor.
     *
     * @param string $comment the content of the comment
     */
    public function __construct(private readonly string $comment)
    {
    }

    /**
     * Get the string representation of the Comment line.
     *
     * @return string returns the Comment line as a string
     */
    public function __toString(): string
    {
        return sprintf('#%s', $this->comment);
    }

    public function equals(AdsTxtLineInterface $adsTxtLine): bool
    {
        return $adsTxtLine instanceof Comment && $adsTxtLine->__toString() === $this->__toString();
    }
}
