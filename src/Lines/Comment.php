<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

/**
 * Class Comment.
 *
 * Represents a comment line in the ads.txt file.
 */
class Comment extends AbstractAdsTxtLine
{
    /**
     * Comment constructor.
     *
     * @param string $comment the content of the comment
     */
    public function __construct(private readonly string $comment) {}

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
        return $adsTxtLine instanceof Comment && $adsTxtLine->pretty() === $this->pretty();
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function pretty(bool $withComment = true): string
    {
        return !$withComment ? '' : sprintf("# %s", $this->getComment());
    }
}
