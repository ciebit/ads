<?php
use Ciebit\Ads\Ad;
use Ciebit\Ads\Factories\FromArray as AdFactoryArray;
use PHPUnit\Framework\TestCase;

class AdCreateTest extends TestCase
{
    public function getDefault()
    {
        return [
            'id' => 0,
            'name' => 'Indefinido',
            'date_start' => new DateTime('0001-01-01 00:00:01'),
            'date_end' => new DateTime('9999-12-31 23:59:59'),
            'status' => Ad::STATUS_DRAFT
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

    public function testCreateDefault()
    {
        $Ad = new Ad;

        $default = $this->getDefault();

        $this->assertEquals($default['id'], $Ad->getId());
        $this->assertEquals($default['date_start'], $Ad->getDateStart());
        $this->assertEquals($default['date_end'], $Ad->getDateEnd());
        $this->assertEquals($default['name'], $Ad->getName());
        $this->assertEquals($default['status'], $Ad->getStatus());
    }

    public function testCreateFromManual()
    {
        $id = 1;
        $name = 'Ad 01';
        $status = Ad::STATUS_ACTIVE;
        $DateStart = new DateTime('2017-10-15 13:16:24');
        $DateEnd = new DateTime('2017-12-31 12:50:12');

        $Advertising = (new Ad)
        ->setId($id)
        ->setDateStart($DateStart)
        ->setDateEnd($DateEnd)
        ->setName($name)
        ->setStatus($status)
        ;

        $this->assertEquals($id, $Advertising->getId());
        $this->assertEquals($name, $Advertising->getName());
        $this->assertEquals($status, $Advertising->getStatus());
        $this->assertEquals(
            $DateStart->format('Y-m-d h:i:s'),
            $Advertising->getDateStart()->format('Y-m-d h:i:s')
        );
        $this->assertEquals(
            $DateEnd->format('Y-m-d h:i:s'),
            $Advertising->getDateEnd()->format('Y-m-d h:i:s')
        );
    }

    public function testCreateFromFactoryArray()
    {
        $data = [
            'id' => 3,
            'date_start' => (new DateTime('2017-01-01 00:00:01')),
            'date_end' => (new DateTime('2018-12-31 20:59:59')),
            'name' => 'Example Ad',
            'status' => Ad::STATUS_DELETED,
        ];
        $copy = $data;

        $Ad = (new AdFactoryArray)->convert($copy);

        $this->assertEquals($data['id'], $Ad->getId());
        $this->assertEquals($data['date_start'], $Ad->getDateStart());
        $this->assertEquals($data['date_end'], $Ad->getDateEnd());
        $this->assertEquals($data['name'], $Ad->getName());
        $this->assertEquals($data['status'], $Ad->getStatus());
    }

    /**
     * @dataProvider invalidData
     */
    public function testCreateInvalidDataFromFactoryArray($data, $valid)
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


        public function testUnconvertFromFactoryArray()
        {
            $data = [
                'id' => 4,
                'date_start' => new DateTime("2017-09-15 12:05:56"),
                'date_end' => new DateTime("2017-09-23 19:05:00"),
                'name' => 'Example Ads 02',
                'status' => Ad::STATUS_ACTIVE,
            ];

            $copy = $data;

            $Ad = (new Ad)
            ->setId($copy['id'])
            ->setDateStart($copy['date_start'])
            ->setDateEnd($copy['date_end'])
            ->setName($copy['name'])
            ->setStatus($copy['status'])
            ;

            $dataUnconverted = (new AdFactoryArray)->unconvert($Ad);

            $this->assertEquals($data['id'], $dataUnconverted['id']);
            $this->assertEquals($data['date_start']->format('Y-m-d'), $dataUnconverted['date_start']);
            $this->assertEquals($data['date_end']->format('Y-m-d'), $dataUnconverted['date_end']);
            $this->assertEquals($data['name'], $dataUnconverted['name']);
            $this->assertEquals($data['status'], $dataUnconverted['status']);
        }
}
