<?php

namespace Badraxas\Adstxt\Parsers;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;
use Badraxas\Adstxt\Interfaces\ParserInterface;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Invalid;
use Badraxas\Adstxt\Lines\Variable;

class VariableParser implements ParserInterface
{
    public function parse(string $line): AdsTxtLineInterface
    {
        $comment = null;

        if (str_contains($line, '#')) {
            $exploded_line = explode('#', $line);

            $comment = new Comment(rtrim($exploded_line[1]));
            $line = trim($exploded_line[0]);
        }

        $exploded_line = explode('=', $line);
        $exploded_line = array_map('trim', $exploded_line);

        if (2 != count($exploded_line)) {
            return new Invalid($line, 'Line appears invalid, it does not validate as a record, variable or comment.', $comment);
        }

        return new Variable(
            name: $exploded_line[0],
            value: $exploded_line[1],
            comment: $comment
        );
    }
}
