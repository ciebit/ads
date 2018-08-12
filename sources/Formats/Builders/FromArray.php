<?php
namespace Ciebit\Ads\Formats\Builders;

use Ciebit\Ads\Formats\Format;
use Ciebit\Ads\Formats\Builders\Builder;

class FromArray implements Builder
{
    private $data; #: ?array

    public function build(): Format
    {
        if ($this->dataValid() == false) {
            throw new Exception('ciebit.ads.formats.builders.fromarray.data-invalid', 1);
        }

        $format = new Format(
            (string) $this->data['name'],
            (int) $this->data['width'],
            (int) $this->data['height'],
            new Status((int) $this->data['status'])
        );

        isset($this->data['id'])
        && is_numeric($this->data['id'])
        && $format->setId((int) $format_data['id']);

        return $format;
    }

    public function dataValid(): bool
    {
        return is_array($this->data)
        && isset($this->data['name'])
        && is_numeric($this->data['height'] ?? false)
        && is_numeric($this->data['width'] ?? false)
        && is_numeric($this->data['status'] ?? false)
        ;
    }


    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }
}
