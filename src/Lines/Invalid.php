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
    public function __construct(private readonly string $value, private readonly ?Comment $comment = null)
    {
    }

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

        return sprintf('%s%s', $this->value, $this->comment->__toString());
    }
}
