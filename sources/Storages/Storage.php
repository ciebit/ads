<?php
namespace Ciebit\Ads\Storages;

use Ciebit\Ads\Ad;
use Ciebit\Ads\Collection;

interface Storage
{
    public function delete(Ad $Ad): self;

    public function destroy(Ad $Ad): self;

    public function getAll(): Collection;

    public function get(): ?Ad;

    public function store(Ad $Ad): self;

    public function update(Ad $Ad): self;
}
