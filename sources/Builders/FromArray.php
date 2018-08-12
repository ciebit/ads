<?php
namespace Ciebit\Ads\Factories;

use Ciebit\Ads\Ad;
use Ciebit\Ads\Status;
use Ciebit\Ads\Builders\Builder;
use Ciebit\Ads\Banners\Collection as BannersCollection;
use DateTime;

class FromArray implements Builder
{
    private $data; #: array

    public function build(): Ad
    {
        if ($this->isDataValid() == false) {
            throw new Exception('ciebit.ads.builders.fromarray.data-invalid', 1);
        }

        $ad = new Ad(
            $this->data['name'],
            $this->data['banners'],
            new Status($this->data['status'])
        );

        isset($this->data['date_end'])
        && $banner->setDateEnd(new DateTime($this->data['date_end']));

        isset($this->data['date_start'])
        && $banner->setDateStart(new DateTime($this->data['date_start']));

        isset($this->data['id'])
        && is_numeric($this->data['id'])
        && $ad->setId($ad_data['id']);

        return $ad;
    }

    public function isDataValid(): boolean
    {
        return is_array($this->data)
        && isset($this->data['name'])
        && isset($this->data['banners'])
        && $this->data['banners'] instanceof BannersCollection
        && isset($this->data['status'])
        && is_numeric($this->data['status']);
    }

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }
}
