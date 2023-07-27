<?php

namespace Badraxas\Adstxt;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

/**
 * Class AdsTxt.
 *
 * Represents the content of an ads.txt file and provides methods to manage its lines.
 */
class AdsTxt implements \Stringable
{
    /**
     * @var array<AdsTxtLineInterface> An array containing all the lines of this ads.txt file.
     */
    private array $lines = [];

    /**
     * Get the string representation of the ads.txt content.
     *
     * @return string Returns the ads.txt content as a string.
     */
    public function __toString(): string
    {
        $output = '';

        foreach ($this->lines as $line) {
            $output .= sprintf('%s%s', $line->__toString(), PHP_EOL);
        }

        return trim($output);
    }

    /**
     * Add a line to the ads.txt content.
     *
     * @param AdsTxtLineInterface $line The line to be added to the ads.txt content.
     *
     * @return AdsTxt returns the AdsTxt instance after adding the line
     */
    public function addLines(AdsTxtLineInterface $line): self
    {
        $this->lines[] = $line;

        return $this;
    }
}
