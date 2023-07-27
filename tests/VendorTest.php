<?php

use Badraxas\Adstxt\Enums\AccountType;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Vendor;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class VendorTest extends TestCase
{
    public function testVendorEquality(): void
    {
        $vendor1 = new Vendor('greenadexchange.com', 'XF436', AccountType::DIRECT, comment: new Comment(' Comment without certification ID'));
        $vendor2 = new Vendor('greenadexchange.com', 'XF436', AccountType::DIRECT, comment: new Comment(' Comment without certification ID'));
        $vendor3 = new Vendor('greenadexchange.com', 42, AccountType::DIRECT, comment: new Comment(' Comment without certification ID'));

        $this->assertTrue($vendor1->equals($vendor2));
        $this->assertTrue($vendor2->equals($vendor2));
        $this->assertFalse($vendor1->equals($vendor3));
        $this->assertFalse($vendor2->equals($vendor3));
    }

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
