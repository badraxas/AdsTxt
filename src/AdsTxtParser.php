<?php

namespace Badraxas\Adstxt;

use Badraxas\Adstxt\Enums\Relationship;
use Badraxas\Adstxt\Exceptions\AdsTxtParser\FileOpenException;
use Badraxas\Adstxt\Exceptions\Lines\RecordArgumentException;
use Badraxas\Adstxt\Lines\Blank;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Invalid;
use Badraxas\Adstxt\Lines\Record;
use Badraxas\Adstxt\Lines\Variable;
use Badraxas\Adstxt\Parsers\ParserFactory;

/**
 * Class AdsTxtParser.
 *
 * A parser for processing content of ads.txt files and converting them to AdsTxt instances.
 *
 * @psalm-api
 */
class AdsTxtParser
{
    /**
     * Creates an AdsTxt instance by parsing the content of an ads.txt file.
     *
     * @param string $path The path to the ads.txt file.
     *
     * @return AdsTxt returns an instance of AdsTxt containing the parsed data
     *
     * @throws FileOpenException if the file cannot be opened (check file permissions or path validity)
     */
    public function fromFile(string $path): AdsTxt
    {
        $handle = fopen($path, 'r');

        if (!$handle) {
            throw new FileOpenException(sprintf('Cannot open file %s. Please check the path and/or file permissions', $path));
        }

        $content = fread($handle, filesize($path));

        fclose($handle);

        return $this->fromString($content);
    }

    /**
     * Creates an AdsTxt instance by parsing the content of an ads.txt file provided as a string.
     *
     * @param string $adsTxtContent The content of the ads.txt file as a string.
     *
     * @return AdsTxt returns an instance of AdsTxt containing the parsed data
     */
    public function fromString(string $adsTxtContent): AdsTxt
    {
        $lines = explode(PHP_EOL, $adsTxtContent);
        $adsTxt = new AdsTxt();

        foreach ($lines as $line) {
            $line = trim($line);

            if ('' === $line) {
                $adsTxt->addLine(new Blank());

                continue;
            }

            $parser = ParserFactory::getParser($line);

            $adsTxt->addLine($parser->parse($line));
        }

        return $adsTxt;
    }
}
