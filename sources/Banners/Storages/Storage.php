<?php
namespace Ciebit\Ads\Banners\Storages;

use Ciebit\Ads\Banners\Banner;
use Ciebit\Ads\Banners\Collection as BannerCollection;

interface Storage
{
    public function delete(Banner $Banner): self;

    public function destroy(Banner $Banner): self;

    public function getAll(): BannerCollection;

    public function get(): ?Banner;

    public function store(Banner $Banner): self;

    public function update(Banner $Banner): self;
}
