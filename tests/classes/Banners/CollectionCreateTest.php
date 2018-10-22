<?php
namespace Ciebit\Ads\Tests\Banners;

use Ciebit\Ads\Banners\Banner;
use Ciebit\Ads\Banners\Collection;
use Ciebit\Ads\Banners\Status;
use Ciebit\Ads\Formats\Format;
use Ciebit\Ads\Formats\Status as FormatStatus;
use Ciebit\Files\Images\Image;
use Ciebit\Files\Status as FileStatus;
use PHPUnit\Framework\TestCase;

class CollectionCreateTest extends TestCase
{
    private $banner01;
    private $banner02;

    public function setUp()
    {
        $file01 = new Image('Image 01', 'image/jpg', 'teste-01.jpg', 400, 300, FileStatus::ACTIVE());
        $file02 = new Image('Image 02', 'image/jpg', 'teste-02.jpg', 800, 600, FileStatus::ACTIVE());

        $format01 = new Format('Format 01', 400, 300, FormatStatus::ACTIVE());
        $format02 = new Format('Format 02', 800, 600, FormatStatus::ACTIVE());

        $this->banner01 = new Banner($file01, $format01, Status::DRAFT());
        $this->banner02 = new Banner($file02, $format02, Status::ACTIVE());
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
        $collection->add('invalid');
    }

    public function testCreateFromManual()
    {
        $collection = new Collection;

        $collection->add($this->banner01);
        $collection->add($this->banner02);

        $this->assertEquals(
            $this->banner01->getId(),
            $collection->getArrayObject()->offsetGet(0)->getId()
        );
        $this->assertEquals(
            $this->banner02->getId(),
            $collection->getArrayObject()->offsetGet(1)->getId()
        );
    }

    public function testImpossibilityAddingInvalidData()
    {
        $collection = new Collection;
        $collection->getArrayObject()->append('invalid');
        $this->assertEquals(0, $collection->count());
    }
}
