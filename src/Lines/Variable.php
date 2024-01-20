<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

/**
 * Class Variable.
 *
 * Represents a line in the ads.txt file containing a variable with its name and value.
 */
class Variable implements AdsTxtLineInterface
{
    /**
     * Variable constructor.
     *
     * @param string       $name    the name of the variable
     * @param mixed        $value   the value of the variable
     * @param null|Comment $comment the comment associated with the variable (optional)
     */
    public function __construct(private readonly string $name, private $value, private readonly ?Comment $comment = null) {}

    /**
     * Get the string representation of the Variable line.
     *
     * @return string returns the Variable line as a string
     */
    public function __toString(): string
    {
        if (!isset($this->comment)) {
            return sprintf('%s=%s', $this->name, $this->value);
        }

        return sprintf('%s=%s %s', $this->name, $this->value, $this->comment->__toString());
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): mixed
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
        return $adsTxtLine instanceof Variable && $adsTxtLine->__toString() === $this->__toString();
    }
}
