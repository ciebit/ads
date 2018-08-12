<?php
namespace Ciebit\Ads\Tests\Formats;

use Ciebit\Ads\Formats\Builders\FromArray as BuilderFromArray;
use Ciebit\Ads\Formats\Format;
use Ciebit\Ads\Formats\Status;
use PHPUnit\Framework\TestCase;

class FormatCreateTest extends TestCase
{
    public function getDefault()
    {
        return [
            'id' => 0,
            'name' => 'Indefinido',
            'width' => 0,
            'height' => 0,
            'status' => Status::TRASH()->getValue()
        ];
    }

    public function invalidData()
    {
        $default = $this->getDefault();
        $data = [];

        $data[0] = [
            [
                'id' => 'a',
                'name' => 'b',
                'width' => 'c',
                'height' => 'd',
                'status' => 'e'
            ],
            array_merge($default, ['name' => 'b'])
        ];

        $data[0] = [
            [
                'id' => 1,
                'name' => 2,
                'width' => 3,
                'height' => 4,
                'status' => 5
            ],
            [
                'id' => 1,
                'name' => 2,
                'width' => 3,
                'height' => 4,
                'status' => 5
            ]
        ];

        return $data;
    }

    public function testCreateFromManual()
    {
        static $id = 2;
        static $name = 'Format 02';
        static $height = 300;
        static $width = 400;
        $status = Status::ACTIVE();

        $format = new Format($name, $width, $height, $status);
        $format->setId($id);

        $this->assertEquals($id, $format->getId());
        $this->assertEquals($name, $format->getName());
        $this->assertEquals($width, $format->getWidth());
        $this->assertEquals($height, $format->getHeight());
        $this->assertEquals($status, $format->getStatus());
    }

    public function testCreateFromFactoryArray()
    {
        $data = [
            'id' => 3,
            'name' => 'Format 03',
            'height' => 200,
            'width' => 300,
            'status' => Status::TRASH()->getValue(),
        ];
        $copy = $data;

        $format = (new BuilderFromArray)->setData($copy)->build();

        $this->assertEquals($data['id'], $format->getId());
        $this->assertEquals($data['name'], $format->getName());
        $this->assertEquals($data['width'], $format->getWidth());
        $this->assertEquals($data['height'], $format->getHeight());
        $this->assertEquals($data['status'], $format->getStatus()->getValue());
    }

    /**
     * @dataProvider invalidData
     */
    public function testCreateInvalidDataBuilderFromArray($data, $valid)
    {
        $format = (new BuilderFromArray)->setData($data)->build();

        $this->assertEquals($valid['id'], $format->getId());
        $this->assertEquals($valid['name'], $format->getName());
        $this->assertEquals($valid['width'], $format->getWidth());
        $this->assertEquals($valid['height'], $format->getHeight());
        $this->assertEquals($valid['status'], $format->getStatus()->getValue());
    }
}
