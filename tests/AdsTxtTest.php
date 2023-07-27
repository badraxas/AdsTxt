<?php

use Badraxas\Adstxt\AdsTxt;
use Badraxas\Adstxt\Enums\AccountType;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Variable;
use Badraxas\Adstxt\Lines\Vendor;
use PHPUnit\Framework\TestCase;

class AdsTxtTest extends TestCase
{
    public function testAdsTxtOutput(): void
    {
        $adsTxt = new AdsTxt();
        $adsTxt
            ->addLines(new Comment(' ads.txt file for example.com:'))
            ->addLines(new Vendor('greenadexchange.com', 12345, AccountType::DIRECT, 'd75815a79'))
            ->addLines(new Vendor('blueadexchange.com', 'XF436', AccountType::DIRECT))
            ->addLines(new Variable('subdomain', 'divisionone.example.com'));

        $this->assertEquals('# ads.txt file for example.com:
greenadexchange.com, 12345, DIRECT, d75815a79
blueadexchange.com, XF436, DIRECT
subdomain=divisionone.example.com', $adsTxt->__toString());
    }
}
