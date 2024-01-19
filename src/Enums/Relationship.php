<?php

namespace Badraxas\Adstxt\Enums;

/**
 * Class Relationship.
 *
 * Represents an enumeration for the types of accounts in the ads.txt file.
 */
enum Relationship
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
     * Get the Relationship enum value from its name.
     *
     * @param string $name the name of the account type
     *
     * @return self returns the Relationship enum value corresponding to the given name
     */
    public static function fromName(string $name): self
    {
        $name = strtoupper($name);

        return match ($name) {
            'DIRECT' => Relationship::DIRECT,
            'RESELLER' => Relationship::RESELLER,
        };
    }
}
