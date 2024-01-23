<?php

namespace Badraxas\Adstxt\Parsers;

use Badraxas\Adstxt\Interfaces\ParserInterface;
use Badraxas\Adstxt\Lines\AbstractAdsTxtLine;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Invalid;
use Badraxas\Adstxt\Lines\Variable;

class VariableParser implements ParserInterface
{
    public function parse(string $line): AbstractAdsTxtLine
    {
        $comment = null;

        if (str_contains($line, '#')) {
            $exploded_line = explode('#', $line);

            $comment = new Comment(trim($exploded_line[1]));
            $line = $exploded_line[0];
        }

        $exploded_line = explode('=', $line);

        if (2 != count($exploded_line)) {
            $invalid = new Invalid($line, $comment);
            $invalid->addError('Line appears invalid, it does not validate as a record, variable or comment.');

            return $invalid;
        }

        $name = trim($exploded_line[0]);
        $value = trim($exploded_line[1]);

        $variable = new Variable(
            name: $name,
            value: $value,
            comment: $comment
        );

        if (!$this->validateName($name)) {
            $variable->addWarning("Variable name is invalid, must be 'CONTACT', 'SUBDOMAIN', 'OWNERDOMAIN' or 'INVENTORYPARTNERDOMAIN'.");
        }

        return $variable;
    }

    private function validateName($name): bool
    {
        $name = strtoupper($name);

        return in_array($name, ['CONTACT', 'SUBDOMAIN', 'INVENTORYPARTNERDOMAIN', 'OWNERDOMAIN'], true);
    }
}
