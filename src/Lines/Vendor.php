<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Enums\AccountType;
use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

class Vendor implements AdsTxtLineInterface
{
    public function __construct(
        private readonly string $domain,
        private $publisherId,
        private readonly AccountType $accountType,
        private $certificationId = null,
        private readonly ?Comment $comment = null
    ) {
    }

    public function __toString(): string
    {
        $vendor = sprintf('%s, %s, %s', $this->domain, $this->publisherId, $this->accountType->name);

        if (isset($this->certificationId)) {
            $vendor = sprintf('%s, %s', $vendor, $this->certificationId);
        }

        if (isset($this->comment)) {
            $vendor = sprintf('%s %s', $vendor, $this->comment);
        }

        return $vendor;
    }
}
