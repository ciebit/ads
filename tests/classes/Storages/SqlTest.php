<?php
namespace Ciebit\Ads\Tests\Storages;

use Ciebit\Ads\Ad;
use Ciebit\Ads\Status;
use Ciebit\Ads\Banners\Storages\Database\Sql as BannerStorage;
use Ciebit\Ads\Formats\Storages\Database\Sql as FormatStorage;
use Ciebit\Ads\Storages\Database\Sql;
use Ciebit\Ads\Links\Builders\Builder as LinkBuilder;
use Ciebit\Ads\Tests\Database\Connection;
use Ciebit\Files\Storages\Database\Sql as FileStorage;
use DateTime;

class SqlTest extends Connection
{
    public function getStorage()
    {
        $pdo = $this->getPdo();
        $fileStorage = new FileStorage($pdo);
        $formatStorage = new FormatStorage($pdo);
        $bannerStorage = new BannerStorage($pdo, $fileStorage, $formatStorage, new LinkBuilder);
        return new Sql($pdo, $bannerStorage);
    }

    public function testGet()
    {
        $storage = $this->getStorage();
        $ad = $storage->addFilterById(2)->get();

        $this->assertEquals(2, $ad->getId());
        $this->assertEquals('Publicidade 02', $ad->getName());
        $this->assertEquals('2018-02-02 08:52:00', $ad->getDateStart()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-03-03 10:42:49', $ad->getDateEnd()->format('Y-m-d H:i:s'));
        $this->assertEquals(2, $ad->getStatus()->getValue());

        $this->assertEquals(1, $ad->getBanners()->count());

        $banner01 = $ad->getBanners()->getArrayObject()->offsetGet(0);

        $this->assertEquals(2, $banner01->getId());
    }

    public function testGetFilterById()
    {
        $storage = $this->getStorage();
        $ad = $storage->addFilterById(2)->get();

        $this->assertEquals(2, $ad->getId());
    }

    public function testGetFilterByStatus()
    {
        $storage = $this->getStorage();
        $ad = $storage->addFilterByStatus(Status::ACTIVE())->get();

        $this->assertEquals(3, $ad->getId());
    }

    public function testGetFilterByFormatId()
    {
        $storage = $this->getStorage();
        $ad = $storage->addFilterByFormatId(3)->get();

        $this->assertEquals(3, $ad->getId());
        $this->assertEquals(1, $ad->getBanners()->count());
    }

    public function Storage()
    {
        $data = [
            'id' => 2,
            'date_start' => new DateTime("2017-08-10 20:00:00"),
            'date_end' => new DateTime("2018-08-10 20:00:00"),
            'name' => 'Publicidade 02',
            'status' => 2
        ];

        $Ad = (new Ad)
        ->setDateStart($data['date_start'])
        ->setDateEnd($data['date_end'])
        ->setName($data['name'])
        ->setStatus($data['status'])
        ;

        $Storage = new Mysql($this->getDatabase());
        $Storage->store($Ad);

        $Ad2 = $Storage->filterId(2)->get();

        $this->assertEquals($data['id'], $Ad2->getId());
        $this->assertEquals($data['date_start'], $Ad2->getDateStart());
        $this->assertEquals($data['date_end'], $Ad2->getDateEnd());
        $this->assertEquals($data['name'], $Ad2->getName());
        $this->assertEquals($data['status'], $Ad2->getStatus());
    }
}
