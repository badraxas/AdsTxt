<?php

namespace Badraxas\Adstxt\Parsers;

use Badraxas\Adstxt\Interfaces\ParserInterface;
use Badraxas\Adstxt\Lines\AbstractAdsTxtLine;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Invalid;
use Badraxas\Adstxt\Lines\Record;

class RecordParser implements ParserInterface
{
    public function parse(string $line): AbstractAdsTxtLine
    {
        $raw = $line;
        $comment = null;

        if (str_contains($line, '#')) {
            $exploded_line = explode('#', $line);

            $comment = new Comment(trim($exploded_line[1]));
            $line = $exploded_line[0];
        }

        $exploded_line = explode(',', $line);
        $exploded_line = array_map('trim', $exploded_line);

        $fieldsCount = count($exploded_line);

        if ($fieldsCount < 3) {
            $invalid = new Invalid($line, $comment);
            $invalid->addError('Record contains less than 3 comma separated values and is therefore improperly formatted.');

            return $invalid;
        }

        if ($fieldsCount > 4) {
            $invalid = new Invalid($line, $comment);
            $invalid->addError('Record contains more than 4 comma separated values and is therefore improperly formatted');

            return $invalid;
        }

        $domain = trim($exploded_line[0]);
        $publisherId = trim($exploded_line[1]);
        $relationship = trim($exploded_line[2]);
        $certificationId = isset($exploded_line[3]) ? trim($exploded_line[3]) : null;

        $record = new Record(
            domain: $domain,
            publisherId: $publisherId,
            relationship: $relationship,
            certificationId: $certificationId ?? null,
            comment: $comment
        );

        $record->setRawValue($raw);

        if (!$this->validateDomain($domain)) {
            $record->addError(sprintf('Domain "%s" does not appear valid.', $domain));
        }

        if ($domain !== $exploded_line[0]) {
            $record->addNotice('Data record domain value contains unnecessary whitespace.');
        }

        if (strtolower($domain) !== $domain) {
            $record->addNotice('Data record domain value contains uppercase characters.');
        }

        if (!$this->validatePublisherId($publisherId)) {
            $record->addError(sprintf('Publisher ID "%s" contains invalid characters.', $publisherId));
        }

        if ($publisherId !== $exploded_line[1]) {
            $record->addNotice('Data record publisher ID contains unnecessary whitespace.');
        }

        if (isset($certificationId) && !$this->validateCertificationId($certificationId)) {
            $record->addError(sprintf('Certification authority ID "%s" is invalid. It may only contain numbers and lowercase letters, and must be 9 or 16 characters.', $certificationId));
        }

        if (isset($certificationId) && $certificationId !== $exploded_line[3]) {
            $record->addNotice('Data record certification ID value contains unnecessary whitespace.');
        }

        if (!$this->validateRelationship($relationship)) {
            $record->addError("Relationship value must be 'DIRECT' or 'RESELLER'.");
        }

        if (strtoupper($relationship) !== $relationship) {
            $record->addNotice('Data record relationship value contains lowercase characters.');
        }

        if ($publisherId !== $exploded_line[1]) {
            $record->addNotice('Data record relationship contains unnecessary whitespace.');
        }

        return $record;
    }

    private function validateCertificationId($certificationId): bool
    {
        return isset($certificationId) && preg_match('/^[a-f0-9]{9,16}$/', $certificationId);
    }

    private function validateDomain(string $domain): bool
    {
        // We check if the domain is valid and if the domain had a dot (we don't validate localhost)
        return filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) && str_contains($domain, '.');
    }

    private function validatePublisherId($publisherId): bool
    {
        return !empty($publisherId) && preg_match('/^[a-z0-9-]+$/i', $publisherId);
    }

    private function validateRelationship($relationship): bool
    {
        $relationship = strtoupper($relationship);

        return 'DIRECT' === $relationship || 'RESELLER' === $relationship;
    }
}
