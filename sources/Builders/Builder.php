<?php
namespace Ciebit\Ads\Builders;

use Ciebit\Ads\Ad;

interface Builder
{
    public function build(): Ad;
}
