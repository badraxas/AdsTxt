<?php

namespace Badraxas\Adstxt;

use Badraxas\Adstxt\Enums\AccountType;
use Badraxas\Adstxt\Exceptions\AdsTxtParser\FileOpenException;
use Badraxas\Adstxt\Exceptions\AdsTxtParser\ParserException;
use Badraxas\Adstxt\Lines\Blank;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Invalid;
use Badraxas\Adstxt\Lines\Variable;
use Badraxas\Adstxt\Lines\Vendor;

class AdsTxtParser
{
    public static function fromFile(string $path): AdsTxt
    {
        $handle = fopen($path, 'r');

        if (!$handle) {
            throw new FileOpenException(sprintf("Cannot open file %s. Please check the path and/or file permissions", $path));
        }

        $content = fread($handle, filesize($path));

        fclose($handle);

        return self::fromString($content);
    }

    public static function fromString(string $adsTxtContent): AdsTxt
    {
        $lines = explode(PHP_EOL, $adsTxtContent);
        $adsTxt = new AdsTxt();

        foreach ($lines as $lineNumber => $line) {
            $line = trim($line);

            if ($line === '') {
                $adsTxt->addLines(new Blank());

                continue;
            }

            $comment = null;

            if (str_contains($line, '#')) {
                $exploded_line = explode('#', $line);

                if (str_starts_with($line, '#')) {
                    $adsTxt->addLines(new Comment(rtrim(mb_strcut($line, 1))));

                    continue;
                } else {
                    $comment = new Comment(rtrim($exploded_line[1]));
                    $line = trim($exploded_line[0]);
                }
            }

            if (str_contains($line, ',')) {
                $exploded_line = explode(',', $line);
                $exploded_line = array_map('trim', $exploded_line);

                $fieldsCount = count($exploded_line);

                if ($fieldsCount != 3 && $fieldsCount != 4) {
                    $adsTxt->addLines(new Invalid($line, $comment));

                    continue;
                }

                $adsTxt->addLines(new Vendor(
                    domain: $exploded_line[0],
                    publisherId: $exploded_line[1],
                    accountType: AccountType::fromName($exploded_line[2]),
                    certificationId: $exploded_line[3] ?? null,
                    comment: $comment
                ));
            } elseif (str_contains($line, '=')) {
                $exploded_line = explode('=', $line);
                $exploded_line = array_map('trim', $exploded_line);

                if (count($exploded_line) != 2) {
                    $adsTxt->addLines(new Invalid($line, $comment));

                    continue;
                }

                $adsTxt->addLines(new Variable(
                    name: $exploded_line[0],
                    value: $exploded_line[1],
                    comment: $comment
                ));
            } else {
                $adsTxt->addLines(new Invalid($line, $comment));
            }
        }

        return $adsTxt;
    }
}