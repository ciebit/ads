<?php
namespace Ciebit\Ads\Banners;

use ArrayIterator;
use ArrayObject;
use Countable;
use IteratorAggregate;
use Ciebit\Ads\Banners\Banner;

class Collection implements Countable, IteratorAggregate
{
    private $banners; #: ArrayObject

    public function __construct()
    {
        $this->banners = new ArrayObject;
    }

    public function add(Banner $banner): self
    {
        $this->banners->append($banner);
        return $this;
    }

    public function count(): int
    {
        return $this->banners->count();
    }

    public function getArrayObject(): ArrayObject
    {
        return clone $this->banners;
    }

    public function getById(int $id): ?Banner
    {
        $interator = $this->getInterator();

        foreach ($interator as $banner) {
            if ($banner->getId() == $id) {
                return $banner;
            }
        }

        return null;
    }

    public function getIterator(): ArrayIterator
    {
        return $this->banners->getIterator();
    }
}
