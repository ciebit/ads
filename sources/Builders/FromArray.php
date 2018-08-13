<?php
namespace Ciebit\Ads\Builders;

use Ciebit\Ads\Ad;
use Ciebit\Ads\Status;
use Ciebit\Ads\Builders\Builder;
use Ciebit\Ads\Banners\Collection as BannersCollection;
use DateTime;
use Exception;

class FromArray implements Builder
{
    private $data; #: array

    public function build(): Ad
    {
        if ($this->isDataValid() == false) {
            var_dump($this->data);
            throw new Exception('ciebit.ads.builders.fromarray.data-invalid', 1);
        }

        $ad = new Ad(
            $this->data['name'],
            $this->data['banners'],
            new Status($this->data['status'])
        );

        isset($this->data['date_end'])
        && $ad->setDateEnd(new DateTime($this->data['date_end']));

        isset($this->data['date_start'])
        && $ad->setDateStart(new DateTime($this->data['date_start']));

        isset($this->data['id'])
        && is_numeric($this->data['id'])
        && $ad->setId($this->data['id']);

        return $ad;
    }

    public function isDataValid(): bool
    {
        return is_array($this->data)
        && isset($this->data['name'])
        && isset($this->data['banners'])
        && ($this->data['banners'] instanceof BannersCollection)
        && isset($this->data['status'])
        && is_numeric($this->data['status']);
    }

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }
}
