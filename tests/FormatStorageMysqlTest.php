<?php
require_once 'Connection.php';

use Ciebit\Ads\Formats\Storages\Mysql;
use Ciebit\Ads\Formats\Format;
use Ciebit\Conexoes\Bd as Database;
use Ciebit\Conexoes\Configuracoes;

class FormatStorageMysqlTest extends Connection
{
    public function testGet()
    {
        $Storage = new Mysql($this->getDatabase());
        $Format = $Storage->get();

        $this->assertEquals(1, $Format->getId());
    }

    public function testStorage()
    {
        $data = [
            'width' => 400,
            'height' => 300,
            'name' => 'Teste Cadastro'
        ];

        $Format = (new Format)
        ->setWidth($data['width'])
        ->setHeight($data['height'])
        ->setName($data['name'])
        ;

        $Storage = new Mysql($this->getDatabase());
        $Storage->store($Format);

        $Format2 = $Storage->filterId($Format->getId())->get();

        $this->assertEquals($data['width'], $Format2->getWidth());
        $this->assertEquals($data['height'], $Format2->getHeight());
        $this->assertEquals($data['name'], $Format2->getName());
    }
}
