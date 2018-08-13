<?php
namespace Ciebit\Ads\Tests\Banners\Storages;

use Ciebit\Ads\Banners\Banner;
use Ciebit\Ads\Banners\Status;
use Ciebit\Ads\Banners\Storages\Database\Sql;
use Ciebit\Ads\Formats\Format;
use Ciebit\Ads\Formats\Status as FormatStatus;
use Ciebit\Ads\Formats\Storages\Database\Sql as FormatSql;
use Ciebit\Ads\Links\Link;
use Ciebit\Ads\Links\Builders\Builder as LinkBuilder;
use Ciebit\Files\Status as FileStatus;
use Ciebit\Files\Images\Image;
use Ciebit\Files\Storages\Database\Sql as FileSql;
use Ciebit\Ads\Tests\Database\Connection;
use DateTime;

class SqlTest extends Connection
{
    public function getSql()
    {
        $fileSql = new FileSql($this->getPdo());
        $formatSql = new FormatSql($this->getPdo());
        return new Sql($this->getPdo(), $fileSql, $formatSql, new LinkBuilder);
    }

    public function testGet()
    {
        $storage = $this->getSql();
        $banner = $storage->addFilterById(1)->get();

        $this->assertEquals(1, $banner->getId());
        $this->assertEquals('http://ciebit.com.br', $banner->getLink()->getHref());
        $this->assertEquals('_blank', $banner->getLink()->getTarget());
        $this->assertEquals(0, $banner->getViews());
        $this->assertEquals('2017-11-26 18:01:00', $banner->getDateStart()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-12-27 19:02:00', $banner->getDateEnd()->format('Y-m-d H:i:s'));
        $this->assertEquals(1, $banner->getStatus()->getValue());
        $this->assertEquals(1, $banner->getFormat()->getId());
        $this->assertEquals(1, $banner->getFile()->getId());
    }

    public function testGetAll()
    {
        $storage = $this->getSql();
        $banners = $storage->getAll();

        $this->assertEquals(2, $banners->count());
        $this->assertEquals(1, $banners->getArrayObject()->offsetGet(0)->getFormat()->getId());
        $this->assertEquals(1, $banners->getArrayObject()->offsetGet(0)->getFile()->getId());
    }

    public function pendentTestStorage()
    {
        $data = [
            'date_start' => new DateTime("2017-08-10 20:00:00"),
            'date_end' => new DateTime("2018-08-10 20:00:00"),
            'file' => new Image('Image 01', 'image/jpg', 'teste-01.jpg', 400, 300, FileStatus::ACTIVE()),
            'format' => new Format('Format 01', 400, 300, FormatStatus::ACTIVE()),
            'link' => (new Link('banner.jpg'))->setTarget('_blank'),
            'status' => 2,
            'views' => 8,
        ];

        $banner = new Banner($data['file'], $data['format'], new Status($data['status']));
        $banner
        ->setDateStart($data['date_start'])
        ->setDateEnd($data['date_end'])
        ->setLink($data['link'])
        ->setViews($data['views'])
        ;

        $storage = $this->getSql();
        $storage->store($banner);

        $banner2 = $storage->addFilterById($banner->getId())->get();

        $this->assertNotEquals(0, $Banner2->getId());
        $this->assertEquals($data['date_end'], $banner2->getDateEnd());
        $this->assertEquals($data['date_start'], $banner2->getDateStart());
        $this->assertEquals($data['format'], $banner2->getFormat());
        $this->assertEquals($data['link'], $banner2->getLink());
        $this->assertEquals($data['status'], $banner2->getStatus());
        $this->assertEquals($data['views'], $banner2->getViews());
    }
}
