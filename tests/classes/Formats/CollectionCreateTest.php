<?php
namespace Ciebit\Ads\Tests\Formats;

use Ciebit\Ads\Formats\Collection;
use Ciebit\Ads\Formats\Format;
use Ciebit\Ads\Formats\Status;
use PHPUnit\Framework\TestCase;

class CollectionCreateTest extends TestCase
{
    private $format1;
    private $format2;

    public function setUp()
    {
        $this->format1 = new Format('Format 01', 400, 300, Status::ACTIVE());
        $this->format2 = new Format('Format 02', 300, 200, Status::DRAFT());
    }

    public function testCreateDefault()
    {
        $collection = new Collection;

        $this->assertEquals(0, $collection->count());
    }

    /**
     * @expectedException TypeError
     */
    public function testErrorAdd()
    {
        $collection = new Collection;
        $collection->add('error');
    }

    public function testCreateFromManual()
    {
        $collection = new Collection;

        $collection->add($this->format1);
        $collection->add($this->format2);

        $this->assertEquals(
            $this->format1->getName(),
            $collection->getArrayObject()->offsetGet(0)->getName()
        );
        $this->assertEquals(
            $this->format2->getName(),
            $collection->getArrayObject()->offsetGet(1)->getName()
        );
    }

    public function testAddInvalidData()
    {
        $collection = new Collection;
        $collection->getArrayObject()->append('invalid');
        $this->assertEquals(0, $collection->count());
    }
}
