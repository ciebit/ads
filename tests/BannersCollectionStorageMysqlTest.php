<?php
require_once 'Connection.php';

use Ciebit\Ads\Banners\Storages\Mysql;
use Ciebit\Ads\Banners\Banner;
use Ciebit\Conexoes\Bd as Database;
use Ciebit\Conexoes\Configuracoes;
use Ciebit\Arquivos\Imagens\Auxiliares\Fabrica as ImageFactory;
use Ciebit\Ads\Formats\Format;

class BannersCollectionStorageMysqlTest extends Connection
{
    public function testGet()
    {
        $Storage = new Mysql($this->getDatabase());
        $Banners = $Storage->getAll();

        $this->assertEquals(2, $Banners->count());
        $this->assertEquals(1, $Banners->offsetGet(0)->getFormat()->getId());
        $this->assertEquals(1, $Banners->offsetGet(0)->getFile()->obterId());
    }
}
