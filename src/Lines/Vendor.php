<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Enums\AccountType;
use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

/**
 * Class Vendor.
 *
 * Represents a line in the ads.txt file containing vendor information.
 */
class Vendor implements AdsTxtLineInterface
{
    /**
     * Vendor constructor.
     *
     * @param string       $domain          the domain associated with the vendor
     * @param mixed        $publisherId     the ID of the publisher associated with the vendor
     * @param AccountType  $accountType     The account type of the vendor (e.g., DIRECT, RESELLER).
     * @param null|mixed   $certificationId the certification ID of the vendor (optional)
     * @param null|Comment $comment         the comment associated with the vendor (optional)
     */
    public function __construct(
        private readonly string $domain,
        private $publisherId,
        private readonly AccountType $accountType,
        private $certificationId = null,
        private readonly ?Comment $comment = null
    ) {}

    /**
     * Get the string representation of the Vendor line.
     *
     * @return string returns the Vendor line as a string
     */
    public function __toString(): string
    {
        $vendor = sprintf('%s, %s, %s', $this->domain, $this->publisherId, $this->accountType->name);

        if (isset($this->certificationId)) {
            $vendor = sprintf('%s, %s', $vendor, $this->certificationId);
        }

        if (isset($this->comment)) {
            $vendor = sprintf('%s %s', $vendor, $this->comment->__toString());
        }

        return $vendor;
    }

    public function equals(AdsTxtLineInterface $adsTxtLine): bool
    {
        return $adsTxtLine instanceof Vendor && $adsTxtLine->__toString() === $this->__toString();
    }
}
