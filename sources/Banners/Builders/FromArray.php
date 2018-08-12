<?php
namespace Ciebit\Ads\Banners\Builders;

use Ciebit\Ads\Banners\Banner;
use Ciebit\Files\File;
use Ciebit\Ads\Formats\Format;
use Ciebit\Ads\Formats\Builders\FromArray as FormatFactory;
use DateTime;
use Psr\Link\LinkInterface;

class FromArray implements Builder
{
    private $data; #: array

    public function build(): Banner
    {
        if ($this->dataValid() == false) {
            throw new Exception('ciebit.ads.banners.builders.fromarray.data-invalid', 1);
        }

        $banner = new Banner(
            $this->data['file'],
            $this->data['format'],
            new Status($this->data['status'])
        );

        isset($this->data['date_end'])
        && $banner->setDateEnd(new DateTime($this->data['date_end']));

        isset($this->data['date_start'])
        && $banner->setDateStart(new DateTime($this->data['date_start']));

        isset($this->data['id'])
        && is_numeric($this->data['id'])
        && $banner->setId((int) $this->data['id']);

        isset($this->data['link'])
        && $this->data['link'] instanceof LinkInterface
        && $banner->setLink($this->banner['link']);

        isset($this->data['views'])
        && is_numeric($this->data['views'])
        && $banner->setViews($this->data['views']);

        return $Banner;
    }

    public function dataValid(): bool
    {
        return is_array($this->data)
        && isset($this->data['file'])
        &&  $this->data['file'] instanceof File
        && isset($this->data['format'])
        && $this->data['format'] instanceof Format
        && isset($this->data['status'])
        && is_numeric($this->data['status']);
    }

    public function setData(array $data): Builder
    {
        return $this->data = $data;
    }
}
