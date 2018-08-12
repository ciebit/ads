<?php
use Ciebit\Arquivos\Imagens\Auxiliares\Fabrica as ImageFactory;
use Ciebit\Ads\Formats\Format;
use Ciebit\Ads\Banners\Factories\FromArray as BannerFactoryArray;
use Ciebit\Ads\Banners\Banner;
use Ciebit\Arquivos\Arquivo as File;
use PHPUnit\Framework\TestCase;

class BannerCreateTest extends TestCase
{
    public function getDefault()
    {
        return [
            'id' => 0,
            'ad_id' => 0,
            'date_start' => new DateTime('0001-01-01 00:00:01'),
            'date_end' => new DateTime('9999-12-31 23:59:59'),
            'file' => ImageFactory::criarPadrao(),
            'link' => '',
            'link_target' => '',
            'format' => new Format,
            'views' => 0,
            'status' => Banner::STATUS_DRAFT
        ];
    }

    public function invalidData()
    {
        $default = $this->getDefault();
        $data = [];

        $data[0] = [
            [], $default
        ];

        $data[1] = [
            [
                'id' => 'a',
                'ad_id' => 'b',
                'date_start' => 'teste',
                'date_end' => 'teste',
                'file' => 'e',
                'link' => 'f',
                'link_target' => 'g',
                'format' => 'h',
                'views' => 'i',
                'status' => 'j'
            ],
            array_merge(
                $default,
                [
                    'link' => 'f',
                    'link_target' => 'g',
                ]
            )
        ];

        $data[2] = [
            [
                'id' => 1,
                'ad_id' => 2,
                'date_start' => 3,
                'date_end' => 4,
                'file' => 5,
                'link' => 6,
                'link_target' => 7,
                'format' => 8,
                'views' => 9,
                'status' => 10
            ],
            array_merge(
                $default,
                [
                    'id' => 1,
                    'ad_id' => 2,
                    'date_start' => new DateTime('1969-12-31 09:00:03'),
                    'date_end' => new DateTime('1969-12-31 09:00:04'),
                    'link' => '6',
                    'link_target' => '7',
                    'views' => 9,
                    'status' => 10
                ]
            )
        ];

        return $data;
    }

    public function testCreateDefault()
    {
        $Banner = (new Banner);

        $default = $this->getDefault();

        $this->assertEquals($default['id'], $Banner->getId());
        $this->assertEquals($default['ad_id'], $Banner->getAdId());
        $this->assertEquals($default['date_start'], $Banner->getDateStart());
        $this->assertEquals($default['date_end'], $Banner->getDateEnd());
        $this->assertEquals($default['file']->obterUri(), $Banner->getFile()->obterUri());
        $this->assertEquals($default['link'], $Banner->getLink());
        $this->assertEquals($default['link_target'], $Banner->getLinkTarget());
        $this->assertEquals($default['format'], $Banner->getFormat());
        $this->assertEquals($default['views'], $Banner->getViews());
        $this->assertEquals($default['status'], $Banner->getStatus());
    }

    public function testCreateFromManual()
    {
        $id = 1;
        $DateStart = new DateTime('2017-10-15 13:16:24');
        $DateEnd = new DateTime('2017-12-31 12:50:12');
        $ad_id = 1;
        $File = new File;
        $Format = new Format;
        $link = "banner";
        $link_target = "_blank";
        $views = 12;
        $status = Banner::STATUS_DRAFT;

        $Banner = (new Banner)
        ->setId($id)
        ->setDateStart($DateStart)
        ->setDateEnd($DateEnd)
        ->setAdId($ad_id)
        ->setFile($File)
        ->setFormat($Format)
        ->setLink($link)
        ->setLinkTarget($link_target)
        ->setViews($views)
        ->setStatus($status)
        ;

        $this->assertEquals($id, $Banner->getId());
        $this->assertEquals($ad_id, $Banner->getAdId());
        $this->assertEquals($File, $Banner->getFile());
        $this->assertEquals($Format, $Banner->getFormat());
        $this->assertEquals($link, $Banner->getLink());
        $this->assertEquals($link_target, $Banner->getLinkTarget());
        $this->assertEquals($views, $Banner->getViews());
        $this->assertEquals($status, $Banner->getStatus());
        $this->assertEquals(
            $DateStart->format('Y-m-d h:i:s'),
            $Banner->getDateStart()->format('Y-m-d h:i:s')
        );
        $this->assertEquals(
            $DateEnd->format('Y-m-d h:i:s'),
            $Banner->getDateEnd()->format('Y-m-d h:i:s')
        );
    }

    public function testCreateFromFactoryArray()
    {
        $data = [
            'id' => 3,
            'ad_id' => 5,
            'date_start' => (new DateTime('2017-01-01 00:00:01')),
            'date_end' => (new DateTime('2018-12-31 20:59:59')),
            'file' => (ImageFactory::criarPadrao()),
            'link' => '',
            'link_target' => '',
            'format' => (new Format),
            'views' => 0,
            'status' => Banner::STATUS_DELETED,
        ];
        $copy = $data;

        $Banner = (new BannerFactoryArray)->convert($copy);

        $this->assertEquals($data['id'], $Banner->getId());
        $this->assertEquals($data['ad_id'], $Banner->getAdId());
        $this->assertEquals($data['date_start'], $Banner->getDateStart());
        $this->assertEquals($data['date_end'], $Banner->getDateEnd());
        $this->assertEquals($data['file'], $Banner->getFile());
        $this->assertEquals($data['link'], $Banner->getLink());
        $this->assertEquals($data['link_target'], $Banner->getLinkTarget());
        $this->assertEquals($data['format'], $Banner->getFormat());
        $this->assertEquals($data['views'], $Banner->getViews());
        $this->assertEquals($data['status'], $Banner->getStatus());
    }

    /**
     * @dataProvider invalidData
     */
    public function testCreateInvalidDataFromFactoryArray($data, $valid)
    {
        $Banner = (new BannerFactoryArray)->convert($data);

        $this->assertEquals($valid['id'], $Banner->getId());
        $this->assertEquals($valid['ad_id'], $Banner->getAdId());
        $this->assertEquals(
            $valid['date_start']->format('Y-m-d h:i:s'),
            $Banner->getDateStart()->format('Y-m-d h:i:s')
        );
        $this->assertEquals(
            $valid['date_end']->format('Y-m-d h:i:s'),
            $Banner->getDateEnd()->format('Y-m-d h:i:s')
        );
        $this->assertEquals($valid['file']->obterId(), $Banner->getFile()->obterId());
        $this->assertEquals($valid['link'], $Banner->getLink());
        $this->assertEquals($valid['link_target'], $Banner->getLinkTarget());
        $this->assertEquals($valid['format'], $Banner->getFormat());
        $this->assertEquals($valid['views'], $Banner->getViews());
        $this->assertEquals($valid['status'], $Banner->getStatus());
    }

    public function testUnconvertFromFactoryArray()
    {
        $data = [
            'id' => 4,
            'ad_id' => 8,
            'date_start' => new DateTime("2017-09-15 12:05:56"),
            'date_end' => new DateTime("2017-09-23 19:05:00"),
            'file' => ImageFactory::criarPadrao(),
            'link' => 'fachada_banco',
            'link_target' => '_blank',
            'format' => new Format,
            'views' => 98,
            'status' => Banner::STATUS_ACTIVE,
        ];

        $copy = $data;

        $Banner = (new Banner)
        ->setId($copy['id'])
        ->setAdId($copy['ad_id'])
        ->setDateStart($copy['date_start'])
        ->setDateEnd($copy['date_end'])
        ->setFile($copy['file'])
        ->setLink($copy['link'])
        ->setLinkTarget($copy['link_target'])
        ->setFormat($copy['format'])
        ->setViews($copy['views'])
        ->setStatus($copy['status'])
        ;

        $dataUnconverted = (new BannerFactoryArray)->unconvert($Banner);

        $this->assertEquals($data['id'], $dataUnconverted['id']);
        $this->assertEquals($data['ad_id'], $dataUnconverted['ad_id']);
        $this->assertEquals($data['date_start']->format('Y-m-d'), $dataUnconverted['date_start']);
        $this->assertEquals($data['date_end']->format('Y-m-d'), $dataUnconverted['date_end']);
        $this->assertEquals($data['file']->obterId(), $dataUnconverted['file']['id']);
        $this->assertEquals($data['link'], $dataUnconverted['link']);
        $this->assertEquals($data['link_target'], $dataUnconverted['link_target']);
        $this->assertEquals($data['format']->getId(), $dataUnconverted['format']['id']);
        $this->assertEquals($data['views'], $dataUnconverted['views']);
        $this->assertEquals($data['status'], $dataUnconverted['status']);
    }
}
