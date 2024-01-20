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
class Record implements AdsTxtLineInterface
{
    /**
     * Record constructor.
     *
     * @param string       $domain          the domain associated with the record
     * @param mixed        $publisherId     the ID of the publisher associated with the record
     * @param Relationship $relationship    The relationship of the record (e.g., DIRECT, RESELLER).
     * @param null|mixed   $certificationId the certification ID of the record (optional)
     * @param null|Comment $comment         the comment associated with the record (optional)
     */
    public function __construct(
        private readonly string $domain,
        private readonly mixed $publisherId,
        private readonly Relationship $relationship,
        private readonly mixed $certificationId = null,
        private readonly ?Comment $comment = null
    ) {
        $this->validateDomain();
        $this->validatePublisherId();
        $this->validateCertificationId();
    }

    /**
     * Get the string representation of the Record line.
     *
     * @return string returns the Record line as a string
     */
    public function __toString(): string
    {
        $vendor = sprintf('%s, %s, %s', $this->domain, $this->publisherId, $this->relationship->name);

        if (isset($this->certificationId)) {
            $vendor = sprintf('%s, %s', $vendor, $this->certificationId);
        }

        if (isset($this->comment)) {
            $vendor = sprintf('%s %s', $vendor, $this->comment->__toString());
        }

        return $vendor;
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
        return $adsTxtLine instanceof Record && $adsTxtLine->__toString() === $this->__toString();
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

    public function getRelationship(): Relationship
    {
        return $this->relationship;
    }

    private function validateCertificationId(): void
    {
        if (isset($this->certificationId) && !preg_match('/^[a-f0-9]{9,16}$/', $this->certificationId)) {
            throw new RecordArgumentException(sprintf('Certification authority ID "%s" is invalid. It may only contain numbers and lowercase letters, and must be 9 or 16 characters.', $this->certificationId));
        }
    }

    private function validateDomain(): void
    {
        // We check if the domain is valid and if the domain had a dot (we don't validate localhost)
        if (!filter_var($this->domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) || !str_contains($this->domain, '.')) {
            throw new RecordArgumentException(sprintf('Domain "%s" does not appear valid.', $this->domain));
        }
    }

    private function validatePublisherId(): void
    {
        if (empty($this->publisherId) || !preg_match('/^[a-z0-9-]+$/i', $this->publisherId)) {
            throw new RecordArgumentException(sprintf('Publisher ID "%s" contains invalid characters.', $this->publisherId));
        }
    }
}
