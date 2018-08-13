<?php
namespace Ciebit\Ads\Tests\Banners;

use Ciebit\Ads\Banners\Banner;
use Ciebit\Ads\Banners\Status;
use Ciebit\Ads\Banners\Builders\FromArray as BuilderFromArray;
use Ciebit\Ads\Formats\Format;
use Ciebit\Ads\Formats\Status as FormatStatus;
use Ciebit\Ads\Links\Link;
use Ciebit\Files\Images\Image;
use Ciebit\Files\Status as FileStatus;
use PHPUnit\Framework\TestCase;
use DateTime;

class CreateTest extends TestCase
{
    private $file;
    private $format;
    private $link;

    public function setUp()
    {
        $this->file = new Image('Image 01', 'image/jpg', 'teste.jpg', 400, 300, FileStatus::ACTIVE());
        $this->format = new Format('Format 01', 400, 300, FormatStatus::ACTIVE());
        $this->link = (new Link('/link/'))->setTarget('_blank');
    }

    public function testCreateFromManual()
    {
        $id = 1;
        $dateStart = new DateTime('2017-10-15 13:16:24');
        $dateEnd = new DateTime('2017-12-31 12:50:12');
        $ad_id = 1;
        $views = 12;
        $status = Status::ACTIVE();

        $banner = new Banner($this->file, $this->format, Status::DRAFT());

        $banner->setId($id)
        ->setDateStart($dateStart)
        ->setDateEnd($dateEnd)
        ->setLink($this->link)
        ->setViews($views)
        ->setStatus($status)
        ;

        $this->assertEquals($id, $banner->getId());
        $this->assertEquals($this->file, $banner->getFile());
        $this->assertEquals($this->format, $banner->getFormat());
        $this->assertEquals($this->link, $banner->getLink());
        $this->assertEquals($views, $banner->getViews());
        $this->assertEquals($status, $banner->getStatus());
        $this->assertEquals(
            $dateStart->format('Y-m-d h:i:s'),
            $banner->getDateStart()->format('Y-m-d h:i:s')
        );
        $this->assertEquals(
            $dateEnd->format('Y-m-d h:i:s'),
            $banner->getDateEnd()->format('Y-m-d h:i:s')
        );
    }

    public function testCreateBuilderFromArray()
    {
        $data = [
            'id' => 3,
            'date_start' => '2017-01-01 00:00:01',
            'date_end' => '2018-12-31 20:59:59',
            'file' => $this->file,
            'link' => $this->link,
            'format' => $this->format,
            'views' => 0,
            'status' => Status::TRASH()->getValue(),
        ];
        $copy = $data;

        $banner = (new BuilderFromArray)->setData($copy)->build();

        $this->assertEquals($data['id'], $banner->getId());
        $this->assertEquals($data['date_start'], $banner->getDateStart()->format('Y-m-d H:i:s'));
        $this->assertEquals($data['date_end'], $banner->getDateEnd()->format('Y-m-d H:i:s'));
        $this->assertEquals($this->file, $banner->getFile());
        $this->assertEquals($this->link, $banner->getLink());
        $this->assertEquals($this->format, $banner->getFormat());
        $this->assertEquals($data['views'], $banner->getViews());
        $this->assertEquals($data['status'], $banner->getStatus()->getValue());
    }
}
