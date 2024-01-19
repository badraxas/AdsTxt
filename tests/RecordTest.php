<?php

use Badraxas\Adstxt\Enums\Relationship;
use Badraxas\Adstxt\Exceptions\Lines\RecordArgumentException;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Record;
use PHPUnit\Framework\TestCase;

class RecordTest extends TestCase
{
    public function testVendorEquality(): void
    {
        $vendor1 = new Record('greenadexchange.com', 'XF436', Relationship::DIRECT, comment: new Comment(' Comment without certification ID'));
        $vendor2 = new Record('greenadexchange.com', 'XF436', Relationship::DIRECT, comment: new Comment(' Comment without certification ID'));
        $vendor3 = new Record('greenadexchange.com', 42, Relationship::DIRECT, comment: new Comment(' Comment without certification ID'));

        $this->assertTrue($vendor1->equals($vendor2));
        $this->assertTrue($vendor2->equals($vendor2));
        $this->assertFalse($vendor1->equals($vendor3));
        $this->assertFalse($vendor2->equals($vendor3));
    }

    public function testVendorOutput(): void
    {
        $variable = new Record('greenadexchange.com', 12345, Relationship::DIRECT, 'd75815a79');
        $this->assertEquals('greenadexchange.com, 12345, DIRECT, d75815a79', $variable->__toString());
    }

    public function testVendorOutputWithComment(): void
    {
        $variable = new Record('greenadexchange.com', 'XF436', Relationship::DIRECT, 'd75815a79', new Comment(' GreenAd certification ID'));
        $this->assertEquals('greenadexchange.com, XF436, DIRECT, d75815a79 # GreenAd certification ID', $variable->__toString());
    }

    public function testVendorOutputWithCommentWithoutCertificationId(): void
    {
        $variable = new Record('greenadexchange.com', 'XF436', Relationship::DIRECT, comment: new Comment(' Comment without certification ID'));
        $this->assertEquals('greenadexchange.com, XF436, DIRECT # Comment without certification ID', $variable->__toString());
    }

    public function testRecordInvalidDomain(): void
    {
        $this->expectException(RecordArgumentException::class);
        new Record('domain@domain.com', 'AZER', Relationship::DIRECT, 'az81');
    }

    public function testRecordInvalidPublisherId(): void
    {
        $this->expectException(RecordArgumentException::class);
        new Record('domain.com', 'Inv@lid', Relationship::DIRECT, 'az81');
    }

    public function testRecordInvalidCertificationId(): void
    {
        $this->expectException(RecordArgumentException::class);
        new Record('domain.com', 1034, Relationship::DIRECT, 'zzz');
    }
}
