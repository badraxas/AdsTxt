<?php

namespace Badraxas\Adstxt\Enums;

enum AccountType
{
    case DIRECT;
    case RESELLER;

    public static function fromName(string $name){

        return constant("self::$name");
    }
}
