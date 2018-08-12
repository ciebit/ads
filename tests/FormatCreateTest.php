<?php
use Ciebit\Ads\Formats\Factories\FromArray as FormatFactoryArray;
use Ciebit\Ads\Formats\Format;
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
            'status' => Format::STATUS_DRAFT
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

    public function testCreateDefault()
    {
        $Format = (new Format);

        $default = $this->getDefault();

        $this->assertEquals($default['id'], $Format->getId());
        $this->assertEquals($default['name'], $Format->getName());
        $this->assertEquals($default['width'], $Format->getWidth());
        $this->assertEquals($default['height'], $Format->getHeight());
        $this->assertEquals($default['status'], $Format->getStatus());
    }

    public function testCreateFromManual()
    {
        static $id = 2;
        static $name = 'Format 02';
        static $height = 300;
        static $width = 400;
        static $status = Format::STATUS_ACTIVE;

        $Format = (new Format)
        ->setId($id)
        ->setWidth($width)
        ->setHeight($height)
        ->setName($name)
        ->setStatus($status)
        ;

        $this->assertEquals($id, $Format->getId());
        $this->assertEquals($name, $Format->getName());
        $this->assertEquals($width, $Format->getWidth());
        $this->assertEquals($height, $Format->getHeight());
        $this->assertEquals($status, $Format->getStatus());
    }

    public function testCreateFromFactoryArray()
    {
        $data = [
            'id' => 3,
            'name' => 'Format 03',
            'height' => 200,
            'width' => 300,
            'status' => Format::STATUS_DELETED,
        ];
        $copy = $data;

        $Format = (new FormatFactoryArray)->convert($copy);

        $this->assertEquals($data['id'], $Format->getId());
        $this->assertEquals($data['name'], $Format->getName());
        $this->assertEquals($data['width'], $Format->getWidth());
        $this->assertEquals($data['height'], $Format->getHeight());
        $this->assertEquals($data['status'], $Format->getStatus());
    }

    /**
     * @dataProvider invalidData
     */
    public function testCreateInvalidDataFromFactoryArray($data, $valid)
    {
        $Format = (new FormatFactoryArray)->convert($data);

        $this->assertEquals($valid['id'], $Format->getId());
        $this->assertEquals($valid['name'], $Format->getName());
        $this->assertEquals($valid['width'], $Format->getWidth());
        $this->assertEquals($valid['height'], $Format->getHeight());
        $this->assertEquals($valid['status'], $Format->getStatus());
    }

    public function testUnconvertFromFactoryArray()
    {
        $data = [
            'id' => 4,
            'name' => 'Format 04',
            'height' => 400,
            'width' => 500,
            'status' => Format::STATUS_ACTIVE,
        ];

        $copy = $data;

        $Format = (new Format)
        ->setId($copy['id'])
        ->setWidth($copy['width'])
        ->setHeight($copy['height'])
        ->setName($copy['name'])
        ->setStatus($copy['status'])
        ;

        $dataUnconverted = (new FormatFactoryArray)->unconvert($Format);

        $this->assertEquals($data['id'], $dataUnconverted['id']);
        $this->assertEquals($data['name'], $dataUnconverted['name']);
        $this->assertEquals($data['width'], $dataUnconverted['width']);
        $this->assertEquals($data['height'], $dataUnconverted['height']);
        $this->assertEquals($data['status'], $dataUnconverted['status']);
    }
}
