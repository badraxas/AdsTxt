<?php

namespace Badraxas\Adstxt;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;
use Badraxas\Adstxt\Lines\AbstractAdsTxtLine;
use Badraxas\Adstxt\Lines\Invalid;

/**
 * Class AdsTxt.
 *
 * Represents the content of an ads.txt file and provides methods to manage its lines.
 */
class AdsTxt
{
    /**
     * @var array<AbstractAdsTxtLine> An array containing all the lines of this ads.txt file.
     */
    private array $lines = [];

    /**
     * @var bool Indicates whether the ads.txt content is valid or not.
     */
    private bool $valid = true;

    /**
     * Add a line to the ads.txt content.
     *
     * @param AdsTxtLineInterface $line The line to be added to the ads.txt content.
     *
     * @return AdsTxt returns the AdsTxt instance after adding the line
     */
    public function addLine(AbstractAdsTxtLine $line): self
    {
        if ($this->valid && (!empty($line->getError()) || $line instanceof Invalid)) {
            $this->valid = false;
        }

        $this->lines[] = $line;

        return $this;
    }

    /**
     * Compare the current AdsTxt instance with another AdsTxt instance and return the lines that are missing
     * in the other instance.
     *
     * @param AdsTxt $other the other AdsTxt instance to compare with
     *
     * @return array<AbstractAdsTxtLine> returns an array containing the lines that are present in the current instance
     *                                    but missing in the other instance
     *
     * @psalm-api
     */
    public function diff(AdsTxt $other): array
    {
        $missingLines = [];

        // Iterate through the lines in the current instance
        foreach ($this->lines as $line) {
            // Check if the line exists in the other instance
            if (!$this->lineExistsInAdsTxt($line, $other)) {
                $missingLines[] = $line;
            }
        }

        return $missingLines;
    }

    /**
     * Check if two AdsTxt instances are equal.
     *
     * @param AdsTxt $other the other AdsTxt instance to compare with
     *
     * @return bool returns true if the two AdsTxt instances are equal; otherwise, false
     *
     * @psalm-api
     */
    public function equals(AdsTxt $other): bool
    {
        // Check if the two AdsTxt instances have the same number of lines
        if (count($this->lines) !== count($other->getLines())) {
            return false;
        }

        // Check if both instances have the same validity status
        if ($this->valid !== $other->isValid()) {
            return false;
        }

        // Compare each line of the two AdsTxt instances
        foreach ($this->lines as $index => $line) {
            if (!$line->equals($other->getLines()[$index])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Filter the lines in the ads.txt content based on the provided callback function.
     *
     * The callback function should accept an AbstractAdsTxtLine object as a parameter
     * and return true if the line should be included in the filtered result, false otherwise.
     *
     * @param callable(AbstractAdsTxtLine $line): bool $callback the callback function used to filter the lines
     *
     * @return AdsTxt returns a new AdsTxt instance containing the filtered lines
     *
     * @psalm-api
     */
    public function filter(callable $callback): AdsTxt
    {
        $filteredAdsTxt = new AdsTxt();

        foreach ($this->lines as $line) {
            if ($callback($line)) {
                $filteredAdsTxt->addLine($line);
            }
        }

        return $filteredAdsTxt;
    }

    /**
     * Get an array of invalid lines in the ads.txt content.
     *
     * @return array<Invalid> Returns an array containing the invalid lines in the ads.txt content.
     *
     * @psalm-api
     */
    public function getInvalidLines(): array
    {
        return array_filter($this->lines, fn ($line) => $line instanceof Invalid);
    }

    /**
     * @return array<AbstractAdsTxtLine>
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    /**
     * Check if the ads.txt content is valid.
     *
     * @return bool Returns true if the ads.txt content is valid; otherwise, false.
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * Get the string representation of the ads.txt content.
     *
     * @return string Returns the ads.txt content as a string.
     */
    public function pretty(bool $withComment = true): string
    {
        $output = '';

        foreach ($this->lines as $line) {
            $output .= sprintf('%s%s', $line->pretty($withComment), PHP_EOL);
        }

        return trim($output);
    }

    /**
     * Check if a given line exists in the AdsTxt instance.
     *
     * @param AbstractAdsTxtLine $searchLine the line to search for
     * @param AdsTxt              $adsTxt     the AdsTxt instance to search in
     *
     * @return bool returns true if the line exists in the AdsTxt instance; otherwise, false
     */
    private function lineExistsInAdsTxt(AbstractAdsTxtLine $searchLine, AdsTxt $adsTxt): bool
    {
        foreach ($adsTxt->getLines() as $line) {
            if ($searchLine->equals($line)) {
                return true;
            }
        }

        return false;
    }
}
