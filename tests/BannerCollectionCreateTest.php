<?php
use Ciebit\Ads\Banners\Factories\FromArrayCollection as BannerCollectionFactoryArray;
use Ciebit\Arquivos\Imagens\Auxiliares\Fabrica as ImageFactory;
use Ciebit\Ads\Formats\Format;
use Ciebit\Ads\Banners\Banner;
use Ciebit\Ads\Banners\Collection as BannerCollection;
use PHPUnit\Framework\TestCase;

class BannerCollectionCreateTest extends TestCase
{
    public function testCreateDefault()
    {
        $Collection = new BannerCollection;

        $this->assertEquals(0, $Collection->count());
    }

    /**
     * @expectedException TypeError
     */
    public function testErrorAdd()
    {
        $Collection = new BannerCollection;
        $Collection->append('error');
    }

    public function testCreateFromManual()
    {
        $Banner01 = (new Banner)->setId(1);
        $Banner02 = (new Banner)->setId(2);

        $Collection = new BannerCollection;

        $Collection->append($Banner01);
        $Collection->append($Banner02);

        $this->assertEquals(
            $Banner01->getId(),
            $Collection->offsetGet(0)->getId()
        );
        $this->assertEquals(
            $Banner02->getId(),
            $Collection->offsetGet(1)->getId()
        );
    }

    public function testCreateFromFactoryArray()
    {
        $data = [
            0 => [
                'id' => 1,
                'ad_id' => 1,
                'date_end' => new DateTime('9999-12-31 23:59:59'),
                'date_start' => new DateTime('0001-01-01 00:00:01'),
                'file' => ImageFactory::criarPadrao(),
                'format' => new Format,
                'link' => 'banner01',
                'link_target' => '_blank',
                'views' => 1,
                'status' => Format::STATUS_DELETED,
            ],
            1 => [
                'id' => 2,
                'ad_id' => 1,
                'date_end' => new DateTime('2018-12-31 23:59:59'),
                'date_start' => new DateTime('2017-01-30 10:00:00'),
                'file' => ImageFactory::criarPadrao(),
                'format' => new Format,
                'link' => 'banner02',
                'link_target' => '_blank',
                'views' => 2,
                'status' => Format::STATUS_ACTIVE,
            ]
        ];
        $copy = $data;

        $Collection = (new BannerCollectionFactoryArray)->convert($copy);

        $this->assertEquals($data[0]['id'], $Collection->offsetGet(0)->getId());
        $this->assertEquals($data[1]['id'], $Collection->offsetGet(1)->getId());
    }

    public function testUnconvertFromFactoryArray()
    {
        $Banner01 = (new Banner)->setId(1);
        $Banner02 = (new Banner)->setId(2);

        $Collection = new BannerCollection;

        $Collection->append($Banner01);
        $Collection->append($Banner02);

        $data = (new BannerCollectionFactoryArray)->unconvert($Collection);

        $this->assertEquals($Banner01->getId(), $data[0]['id']);
        $this->assertEquals($Banner02->getId(), $data[1]['id']);
    }
}
