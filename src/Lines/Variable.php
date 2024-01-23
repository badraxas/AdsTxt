<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

/**
 * Class Variable.
 *
 * Represents a line in the ads.txt file containing a variable with its name and value.
 */
class Variable extends AbstractAdsTxtLine
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
     * Compares the current Record object with another AdsTxtLineInterface object.
     *
     * @param AdsTxtLineInterface $adsTxtLine the AdsTxtLineInterface object to compare with
     *
     * @return bool returns true if the provided object is an instance of Record and
     *              its string representation is equal to the string representation of the current object
     */
    public function equals(AdsTxtLineInterface $adsTxtLine): bool
    {
        return $adsTxtLine instanceof Variable && $adsTxtLine->pretty() === $this->pretty();
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function pretty(bool $withComment = true): string
    {
        if (!isset($this->comment)) {
            return sprintf('%s=%s', strtoupper($this->name), $this->value);
        }

        return sprintf('%s=%s%s', strtoupper($this->name), $this->value, $this->comment->pretty($withComment));
    }
}
