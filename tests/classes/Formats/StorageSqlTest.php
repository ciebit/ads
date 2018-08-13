<?php
namespace Ciebit\Ads\Tests\Formats;

use Ciebit\Ads\Formats\Format;
use Ciebit\Ads\Formats\Status;
use Ciebit\Ads\Formats\Storages\Database\Sql;
use Ciebit\Ads\Tests\Database\Connection;

class StorageSqlTest extends Connection
{
    public function testGet()
    {
        $storage = new Sql($this->getPdo());
        $format = $storage->get();

        $this->assertEquals(1, $format->getId());
    }

    public function testStorage()
    {
        $name = 'Teste Cadastro';
        $height = 300;
        $width = 400;
        $status = Status::ACTIVE();

        $format = new Format($name, $width, $height, $status);

        $storage = new Sql($this->getPdo());
        $storage->store($format);

        $format2 = $storage->addFilterById($format->getId())->get();

        $this->assertEquals($name, $format2->getName());
        $this->assertEquals($width, $format2->getWidth());
        $this->assertEquals($height, $format2->getHeight());
        $this->assertEquals($status, $format2->getStatus());
        $this->assertEquals($format->getId(), $format2->getId());
    }
}
