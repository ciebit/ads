<?php
use Ciebit\Ads\Formats\Factories\FromArrayCollection as FormatCollectionFactoryArray;
use Ciebit\Ads\Formats\Format;
use Ciebit\Ads\Formats\Collection as FormatCollection;
use PHPUnit\Framework\TestCase;

class FormatCollectionCreateTest extends TestCase
{
    public function testCreateDefault()
    {
        $Collection = new FormatCollection;

        $this->assertEquals(0, $Collection->count());
    }

    /**
     * @expectedException TypeError
     */
    public function testErrorAdd()
    {
        $Collection = new FormatCollection;
        $Collection->append('error');
    }

    public function testCreateFromManual()
    {
        $Format01 = (new Format)->setId(1);
        $Format02 = (new Format)->setId(2);

        $Collection = new FormatCollection;

        $Collection->append($Format01);
        $Collection->append($Format02);

        $this->assertEquals(
            $Format01->getId(),
            $Collection->offsetGet(0)->getId()
        );
        $this->assertEquals(
            $Format02->getId(),
            $Collection->offsetGet(1)->getId()
        );
    }

    public function testCreateFromFactoryArray()
    {
        static $data = [
            0 => [
                'id' => 1,
                'name' => 'Format 01',
                'height' => 50,
                'width' => 100,
                'status' => Format::STATUS_DELETED,
            ],
            1 => [
                'id' => 2,
                'name' => 'Format 02',
                'height' => 100,
                'width' => 200,
                'status' => Format::STATUS_ACTIVE,
            ]
        ];

        $Collection = (new FormatCollectionFactoryArray)->convert($data);

        $this->assertEquals($data[0]['id'], $Collection->offsetGet(0)->getId());
        $this->assertEquals($data[1]['id'], $Collection->offsetGet(1)->getId());
    }

    public function testUnconvertFromFactoryArray()
    {
        $Format01 = (new Format)->setId(1);
        $Format02 = (new Format)->setId(2);

        $Collection = new FormatCollection;

        $Collection->append($Format01);
        $Collection->append($Format02);

        $data = (new FormatCollectionFactoryArray)->unconvert($Collection);

        $this->assertEquals($Format01->getId(), $data[0]['id']);
        $this->assertEquals($Format02->getId(), $data[1]['id']);
    }
}
