<?php
require_once 'Connection.php';

use Ciebit\Ads\Storages\Mysql;
use Ciebit\Ads\Ad;
use Ciebit\Conexoes\Bd as Database;
use Ciebit\Conexoes\Configuracoes;

class AdStorageMysqlTest extends Connection
{
    public function testGet()
    {
        $Storage = new Mysql($this->getDatabase());
        $Ad = $Storage->filterId(1)->get();

        $this->assertEquals(1, $Ad->getId());
        $this->assertEquals('Publicidade 01', $Ad->getName());
        $this->assertEquals('2017-01-01 07:00:00', $Ad->getDateStart()->format('Y-m-d H:i:s'));
        $this->assertEquals('2017-12-31 23:49:49', $Ad->getDateEnd()->format('Y-m-d H:i:s'));
        $this->assertEquals(1, $Ad->getStatus());

        $this->assertEquals(2, $Ad->getBanners()->count());

        $Banner01 = $Ad->getBanners()->offsetGet(0);
        $Banner02 = $Ad->getBanners()->offsetGet(1);

        $this->assertEquals(1, $Banner01->getId());
        $this->assertEquals(2, $Banner02->getId());
    }

    public function testStorage()
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
