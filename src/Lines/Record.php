<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Enums\Relationship;
use Badraxas\Adstxt\Exceptions\Lines\RecordArgumentException;
use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

/**
 * Class Record.
 *
 * Represents a line in the ads.txt file containing record information.
 */
class Record extends AbstractAdsTxtLine
{
    /**
     * Record constructor.
     *
     * @param string       $domain          the domain associated with the record
     * @param mixed        $publisherId     the ID of the publisher associated with the record
     * @param string $relationship    The relationship of the record (e.g., DIRECT, RESELLER).
     * @param null|string   $certificationId the certification ID of the record (optional)
     * @param null|Comment $comment         the comment associated with the record (optional)
     */
    public function __construct(
        private readonly string $domain,
        private readonly mixed $publisherId,
        private readonly string $relationship,
        private readonly ?string $certificationId = null,
        private readonly ?Comment $comment = null
    ) {
    }

    /**
     * Compares the current Record object with another AdsTxtLineInterface object.
     *
     * @param AdsTxtLineInterface $adsTxtLine the AdsTxtLineInterface object to compare with
     *
     * @return bool returns true if the provided object is an instance of Record and
     *              its string representation is equal to the string representation of the current object
     */
    public function equals(AdsTxtLineInterface $adsTxtLine): bool
    {
        return $adsTxtLine instanceof Record && $adsTxtLine->pretty() === $this->pretty();
    }

    public function getCertificationId(): mixed
    {
        return $this->certificationId;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getPublisherId(): mixed
    {
        return $this->publisherId;
    }

    public function getRelationship(): string
    {
        return $this->relationship;
    }

    public function pretty(bool $withComment = true): string
    {
        $vendor = sprintf('%s, %s, %s', $this->domain, $this->publisherId, $this->relationship);

        if (isset($this->certificationId)) {
            $vendor = sprintf('%s, %s', $vendor, $this->certificationId);
        }

        if (isset($this->comment)) {
            $vendor = sprintf('%s%s', $vendor, $this->comment->pretty($withComment));
        }

        return $vendor;
    }
}
