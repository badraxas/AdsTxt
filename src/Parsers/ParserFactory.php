<?php

namespace Badraxas\Adstxt\Parsers;

use Badraxas\Adstxt\Interfaces\ParserInterface;

class ParserFactory
{
    public static function getParser(string $line): ?ParserInterface
    {
        if (str_starts_with($line, '#')) {
            return new CommentParser();
        }

        [$line] = explode('#', $line);

        if (str_contains($line, '=')) {
            return new VariableParser();
        }

        if (str_contains($line, ',')) {
            return new RecordParser();
        }

        return new InvalidParser();
    }
}
