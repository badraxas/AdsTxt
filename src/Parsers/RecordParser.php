<?php

namespace Badraxas\Adstxt\Parsers;

use Badraxas\Adstxt\Enums\Relationship;
use Badraxas\Adstxt\Exceptions\Lines\RecordArgumentException;
use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;
use Badraxas\Adstxt\Interfaces\ParserInterface;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Invalid;
use Badraxas\Adstxt\Lines\Record;

class RecordParser implements ParserInterface
{
    public function parse(string $line): AdsTxtLineInterface
    {
        $comment = null;

        if (str_contains($line, '#')) {
            $exploded_line = explode('#', $line);

            $comment = new Comment(rtrim($exploded_line[1]));
            $line = trim($exploded_line[0]);
        }

        $line = rtrim($line, ',');

        $exploded_line = explode(',', $line);
        $exploded_line = array_map('trim', $exploded_line);

        $fieldsCount = count($exploded_line);

        if ($fieldsCount < 3) {
            return new Invalid($line, 'Record contains less than 3 comma separated values and is therefore improperly formatted.', $comment);
        }

        if ($fieldsCount > 4) {
            return new Invalid($line, 'Record contains more than 4 comma separated values and is therefore improperly formatted', $comment);
        }

        try {
            return new Record(
                domain: $exploded_line[0],
                publisherId: $exploded_line[1],
                relationship: Relationship::fromName($exploded_line[2]),
                certificationId: $exploded_line[3] ?? null,
                comment: $comment
            );
        } catch (\UnhandledMatchError $unhandledMatchError) {
            return new Invalid($line, "Relationship value must be 'DIRECT' or 'RESELLER'.", $comment);
        } catch (RecordArgumentException $t) {
            return new Invalid($line, $t->getMessage(), $comment);
        }
    }
}
