<?php
namespace Ciebit\Ads\Banners\Builders;

use Ciebit\Ads\Banners\Banner;

interface Builder
{
    public function build(): Banner;
}
