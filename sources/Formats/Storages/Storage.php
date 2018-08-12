<?php
namespace Ciebit\Ads\Formats\Storages;

use Ciebit\Ads\Formats\Format;
use Ciebit\Ads\Formats\Collection;

interface Storage
{
    public function delete(Format $Format): self;

    public function destroy(Format $Format): self;

    public function getAll(): Collection;

    public function get(): ?Format;

    public function store(Format $Format): self;

    public function update(Format $Format): self;
}
