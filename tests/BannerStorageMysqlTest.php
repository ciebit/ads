<?php
require_once 'Connection.php';

use Ciebit\Ads\Banners\Storages\Mysql;
use Ciebit\Ads\Banners\Banner;
use Ciebit\Conexoes\Bd as Database;
use Ciebit\Conexoes\Configuracoes;
use Ciebit\Arquivos\Imagens\Auxiliares\Fabrica as ImageFactory;
use Ciebit\Ads\Formats\Format;

class BannerStorageMysqlTest extends Connection
{
    public function testGet()
    {
        $Storage = new Mysql($this->getDatabase());
        $Banner = $Storage->filterId(1)->get();

        $this->assertEquals(1, $Banner->getId());
        $this->assertEquals(1, $Banner->getAdId());
        $this->assertEquals('http://ciebit.com.br', $Banner->getLink());
        $this->assertEquals('_blank', $Banner->getLinkTarget());
        $this->assertEquals(0, $Banner->getViews());
        $this->assertEquals('2017-11-26 18:01:00', $Banner->getDateStart()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-12-27 19:02:00', $Banner->getDateEnd()->format('Y-m-d H:i:s'));
        $this->assertEquals(1, $Banner->getStatus());
        $this->assertEquals(1, $Banner->getFormat()->getId());
        $this->assertEquals(1, $Banner->getFile()->obterId());
    }

    public function testStorage()
    {
        $data = [
            'ad_id' => 1,
            'date_start' => new DateTime("2017-08-10 20:00:00"),
            'date_end' => new DateTime("2018-08-10 20:00:00"),
            'file' => ImageFactory::criarPadrao(),
            'format' => new Format,
            'link' => 'banner.jpg',
            'link_target' => '_blank',
            'status' => 2,
            'views' => 8,
        ];

        $Banner = (new Banner)
        ->setAdId($data['ad_id'])
        ->setDateStart($data['date_start'])
        ->setDateEnd($data['date_end'])
        ->setFile($data['file'])
        ->setFormat($data['format'])
        ->setLink($data['link'])
        ->setLinkTarget($data['link_target'])
        ->setStatus($data['status'])
        ->setViews($data['views'])
        ;

        $Storage = new Mysql($this->getDatabase());
        $Storage->store($Banner);

        $Banner2 = $Storage->filterId($Banner->getId())->get();

        $this->assertNotEquals(0, $Banner2->getId());
        $this->assertEquals($data['ad_id'], $Banner2->getAdId());
        $this->assertEquals($data['date_end'], $Banner2->getDateEnd());
        $this->assertEquals($data['date_start'], $Banner2->getDateStart());
        $this->assertEquals($data['format'], $Banner2->getFormat());
        $this->assertEquals($data['link'], $Banner2->getLink());
        $this->assertEquals($data['link_target'], $Banner2->getLinkTarget());
        $this->assertEquals($data['status'], $Banner2->getStatus());
        $this->assertEquals($data['views'], $Banner2->getViews());
    }
}
