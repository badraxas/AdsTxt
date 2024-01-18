<?php

namespace Badraxas\Adstxt\Enums;

/**
 * Class AccountType.
 *
 * Represents an enumeration for the types of accounts in the ads.txt file.
 */
enum AccountType
{
    /**
     * Represents the DIRECT account type in the ads.txt file.
     */
    case DIRECT;

    /**
     * Represents the RESELLER account type in the ads.txt file.
     */
    case RESELLER;

    /**
     * Get the AccountType enum value from its name.
     *
     * @param string $name the name of the account type
     *
     * @return self returns the AccountType enum value corresponding to the given name
     */
    public static function fromName(string $name): self
    {
        $name = strtoupper($name);

        return constant("self::{$name}");
    }
}
