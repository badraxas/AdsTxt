<?php

namespace Lines;

use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Record;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class RecordTest extends TestCase
{
    public function testGetters(): void
    {
        $record = new Record('domain.com', 1034, 'DIRECT', 'aed12b962', new Comment(' a nice comment!'));

        $this->assertEquals('domain.com', $record->getDomain());
        $this->assertEquals(1034, $record->getPublisherId());
        $this->assertEquals('DIRECT', $record->getRelationship());
        $this->assertEquals('aed12b962', $record->getCertificationId());
        $this->assertEquals(new Comment(' a nice comment!'), $record->getComment());
    }

    public function testVendorEquality(): void
    {
        $vendor1 = new Record('greenadexchange.com', 'XF436', 'DIRECT', comment: new Comment(' Comment without certification ID'));
        $vendor2 = new Record('greenadexchange.com', 'XF436', 'DIRECT', comment: new Comment(' Comment without certification ID'));
        $vendor3 = new Record('greenadexchange.com', 42, 'DIRECT', comment: new Comment(' Comment without certification ID'));

        $this->assertTrue($vendor1->equals($vendor2));
        $this->assertTrue($vendor2->equals($vendor2));
        $this->assertFalse($vendor1->equals($vendor3));
        $this->assertFalse($vendor2->equals($vendor3));
    }

    public function testVendorOutput(): void
    {
        $variable = new Record('greenadexchange.com', 12345, 'DIRECT', 'd75815a79');
        $this->assertEquals('greenadexchange.com, 12345, DIRECT, d75815a79', $variable->pretty());
    }

    public function testVendorOutputWithComment(): void
    {
        $variable = new Record('greenadexchange.com', 'XF436', 'DIRECT', 'd75815a79', new Comment('GreenAd certification ID'));
        $this->assertEquals('greenadexchange.com, XF436, DIRECT, d75815a79# GreenAd certification ID', $variable->pretty());
    }

    public function testVendorOutputWithCommentWithoutCertificationId(): void
    {
        $variable = new Record('greenadexchange.com', 'XF436', 'DIRECT', comment: new Comment('Comment without certification ID'));
        $this->assertEquals('greenadexchange.com, XF436, DIRECT# Comment without certification ID', $variable->pretty());
    }
}
