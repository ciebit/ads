<?php
namespace Ciebit\Ads\Tests;

use Ciebit\Ads\Ad;
use Ciebit\Ads\Status;
use Ciebit\Ads\Builders\FromArray as BuilderFromArray;
use Ciebit\Ads\Banners\Collection as BannersCollection;
use PHPUnit\Framework\TestCase;
use DateTime;

class CreateTest extends TestCase
{
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
                'name' => 'b',
                'date_start' => 'teste',
                'date_end' => 'teste',
                'status' => 'j'
            ],
            array_merge(
                $default,
                [
                    'name' => 'b'
                ]
            )
        ];

        $data[2] = [
            [
                'id' => 1,
                'name' => 2,
                'date_start' => 3,
                'date_end' => 4,
                'status' => 5
            ],
            array_merge(
                $default,
                [
                    'id' => 1,
                    'name' => '2',
                    'date_start' => new DateTime('1969-12-31 21:00:03'),
                    'date_end' => new DateTime('1969-12-31 21:00:04'),
                    'status' => 5
                ]
            )
        ];

        return $data;
    }

    public function testCreateFromManual()
    {
        $id = 1;
        $name = 'Ad 01';
        $status = Status::ACTIVE();
        $DateStart = new DateTime('2017-10-15 13:16:24');
        $DateEnd = new DateTime('2017-12-31 12:50:12');

        $advertising = new Ad($name, new BannersCollection, $status);
        $advertising->setId($id)
        ->setDateStart($DateStart)
        ->setDateEnd($DateEnd)
        ;

        $this->assertEquals($id, $advertising->getId());
        $this->assertEquals($name, $advertising->getName());
        $this->assertEquals($status, $advertising->getStatus());
        $this->assertEquals(
            $DateStart->format('Y-m-d H:i:s'),
            $advertising->getDateStart()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            $DateEnd->format('Y-m-d H:i:s'),
            $advertising->getDateEnd()->format('Y-m-d H:i:s')
        );
    }

    public function testCreateBuilderFromArray()
    {
        $data = [
            'id' => 3,
            'date_start' => '2017-01-01 00:00:01',
            'banners' => new BannersCollection,
            'date_end' => '2018-12-31 20:59:59',
            'name' => 'Example Ad',
            'status' => Status::TRASH()->getValue(),
        ];
        $copy = $data;

        $ad = (new BuilderFromArray)->setData($copy)->build();

        $this->assertEquals($data['id'], $ad->getId());
        $this->assertEquals($data['date_start'], $ad->getDateStart()->format('Y-m-d H:i:s'));
        $this->assertEquals($data['date_end'], $ad->getDateEnd()->format('Y-m-d H:i:s'));
        $this->assertEquals($data['name'], $ad->getName());
        $this->assertEquals($data['status'], $ad->getStatus()->getValue());
    }

    /**
     * @dataProvider invalidData
     */
    public function CreateInvalidDataFromFactoryArray($data, $valid)
    {
        $Ad = (new AdFactoryArray)->convert($data);

        $this->assertEquals($valid['id'], $Ad->getId());
        $this->assertEquals(
            $valid['date_start']->format('Y-m-d h:i:s'),
            $Ad->getDateStart()->format('Y-m-d h:i:s')
        );
        $this->assertEquals(
            $valid['date_end']->format('Y-m-d h:i:s'),
            $Ad->getDateEnd()->format('Y-m-d h:i:s')
        );
        $this->assertEquals($valid['name'], $Ad->getName());
        $this->assertEquals($valid['status'], $Ad->getStatus());
    }
}
