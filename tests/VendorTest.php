<?php


use Badraxas\Adstxt\Enums\AccountType;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Vendor;
use PHPUnit\Framework\TestCase;

class VendorTest extends TestCase
{
    public function testVendorOutput(): void
    {
        $variable = new Vendor('greenadexchange.com', 12345, AccountType::DIRECT, 'd75815a79');
        $this->assertEquals('greenadexchange.com, 12345, DIRECT, d75815a79', $variable->__toString());
    }

    public function testVendorOutputWithComment(): void
    {
        $variable = new Vendor('greenadexchange.com', 'XF436', AccountType::DIRECT, 'd75815a79', new Comment(' GreenAd certification ID'));
        $this->assertEquals('greenadexchange.com, XF436, DIRECT, d75815a79 # GreenAd certification ID', $variable->__toString());
    }

    public function testVendorOutputWithCommentWithoutCertificationId(): void
    {
        $variable = new Vendor('greenadexchange.com', 'XF436', AccountType::DIRECT, comment: new Comment(' Comment without certification ID'));
        $this->assertEquals('greenadexchange.com, XF436, DIRECT # Comment without certification ID', $variable->__toString());
    }
}
