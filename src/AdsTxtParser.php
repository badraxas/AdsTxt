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

            $comment = null;

            if (str_contains($line, '#')) {
                $exploded_line = explode('#', $line);

                if (str_starts_with($line, '#')) {
                    $adsTxt->addLine(new Comment(rtrim(mb_strcut($line, 1))));

                    continue;
                }

                $comment = new Comment(rtrim($exploded_line[1]));
                $line = trim($exploded_line[0]);
            }

            try {
                if (str_contains($line, ',')) {
                    $exploded_line = explode(',', $line);
                    $exploded_line = array_map('trim', $exploded_line);

                    $fieldsCount = count($exploded_line);

                    if ($fieldsCount < 3) {
                        $adsTxt->addLine(new Invalid($line, 'Record contains less than 3 comma separated values and is therefore improperly formatted.', $comment));

                        continue;
                    }

                    if ($fieldsCount > 4) {
                        $adsTxt->addLine(new Invalid($line, 'Record contains more than 4 comma separated values and is therefore improperly formatted', $comment));

                        continue;
                    }

                    $adsTxt->addLine(new Record(
                        domain: $exploded_line[0],
                        publisherId: $exploded_line[1],
                        relationship: Relationship::fromName($exploded_line[2]),
                        certificationId: $exploded_line[3] ?? null,
                        comment: $comment
                    ));
                } elseif (str_contains($line, '=')) {
                    $exploded_line = explode('=', $line);
                    $exploded_line = array_map('trim', $exploded_line);

                    if (2 != count($exploded_line)) {
                        $adsTxt->addLine(new Invalid($line, 'Line appears invalid, it does not validate as a record, variable or comment.', $comment));

                        continue;
                    }

                    $adsTxt->addLine(new Variable(
                        name: $exploded_line[0],
                        value: $exploded_line[1],
                        comment: $comment
                    ));
                } else {
                    $adsTxt->addLine(new Invalid($line, 'Line appears invalid, it does not validate as a record, variable or comment.', $comment));
                }
            } catch (\UnhandledMatchError $unhandledMatchError) {
                $adsTxt->addLine(new Invalid($line, "Relationship value must be 'DIRECT' or 'RESELLER'.", $comment));
            } catch (RecordArgumentException $t) {
                $adsTxt->addLine(new Invalid($line, $t->getMessage(), $comment));
            }
        }

        return $adsTxt;
    }
}
