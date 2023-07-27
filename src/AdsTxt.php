<?php

namespace Badraxas\Adstxt;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

class AdsTxt implements \Stringable
{
    /**
     * @var array<AdsTxtLineInterface> An array containing all the line of this ads.txt
     */
    private array $lines = [];

    public function __toString(): string
    {
        $output = '';

        foreach ($this->lines as $line) {
            $output .= sprintf('%s%s', $line->__toString(), PHP_EOL);
        }

        return trim($output);
    }

    public function addLines(AdsTxtLineInterface $line): self
    {
        $this->lines[] = $line;

        return $this;
    }
}
