<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

/**
 * Class Invalid.
 *
 * Represents an invalid line in the ads.txt file.
 */
class Invalid extends AbstractAdsTxtLine
{
    /**
     * Invalid constructor.
     *
     * @param string       $value   the value of the invalid line
     * @param null|Comment $comment the comment associated with the invalid line (optional)
     */
    public function __construct(private readonly string $value, private readonly ?Comment $comment = null) {}

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
        return $adsTxtLine instanceof Invalid && $adsTxtLine->pretty() === $this->pretty();
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function pretty(bool $withComment = true): string
    {
        if (!isset($this->comment)) {
            return $this->value;
        }

        return sprintf('%s%s', $this->value, $this->comment->pretty($withComment));
    }
}
