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
    public function __construct(private readonly string $comment) {}

    /**
     * Get the string representation of the Comment line.
     *
     * @return string returns the Comment line as a string
     */
    public function __toString(): string
    {
        return sprintf('#%s', $this->comment);
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
        return $adsTxtLine instanceof Comment && $adsTxtLine->__toString() === $this->__toString();
    }

    public function getComment(): string
    {
        return $this->comment;
    }
}
