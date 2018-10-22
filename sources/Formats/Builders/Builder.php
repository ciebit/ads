<?php
namespace Ciebit\Ads\Formats\Builders;

use Ciebit\Ads\Formats\Format;

interface Builder
{
    public function build(): Format;
}
