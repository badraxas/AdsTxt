<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

/**
 * Class Invalid.
 *
 * Represents an invalid line in the ads.txt file.
 */
class Invalid implements AdsTxtLineInterface
{
    /**
     * Invalid constructor.
     *
     * @param string       $value   the value of the invalid line
     * @param null|Comment $comment the comment associated with the invalid line (optional)
     */
    public function __construct(private readonly string $value, private string $reason, private readonly ?Comment $comment = null) {}

    /**
     * Get the string representation of the Invalid line.
     *
     * @return string returns the Invalid line as a string
     */
    public function __toString(): string
    {
        if (!isset($this->comment)) {
            return $this->value;
        }

        return sprintf('%s %s', $this->value, $this->comment->__toString());
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
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
        return $adsTxtLine instanceof Invalid && $adsTxtLine->__toString() === $this->__toString();
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}
