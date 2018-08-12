<?php
use Ciebit\Ads\Formats\Factories\FromArray as FormatFactoryArray;
use Ciebit\Ads\Formats\Format;
use Ciebit\Conexoes\Bd as Database;
use Ciebit\Conexoes\Configuracoes\Configuracao;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

abstract class Connection extends TestCase
{
    use TestCaseTrait;

    private $Connection;
    static private $Database;

    public function getDatabase()
    {
        return self::$Database;
    }

    public function getConnection()
    {
        if ($this->Connection === null) {
            if (self::$Database == null) {
                self::$Database = new Database;
            }
            $Configuracoes = new Configuracao;
            $this->Connection = $this->createDefaultDBConnection(self::$Database->conectar(), $Configuracoes->obterBanco());
        }

        return $this->Connection;
    }

    public function getDataSet()
    {
        return $this->createXMLDataSet(__DIR__.'/date.xml');
    }
}
