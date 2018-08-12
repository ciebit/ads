<?php
namespace Ciebit\Ads;

use ArrayIterator;
use ArrayObject;
use Ciebit\Ads\Ad;
use Countable;
use IteratorAggregate;

class Collection implements Countable, IteratorAggregate
{
    private $ads; #: ArrayObject

    public function __construct()
    {
        $this->ads = new ArrayObject;
    }

    public function add(Ad $ad): self
    {
        $this->ads->append($ad);
        return $this;
    }

    public function count(): int
    {
        return $this->ads->count();
    }

    public function getArrayObject(): ArrayObject
    {
        return clone $this->ads;
    }

    public function getById(int $id): ?Ad
    {
        $iterator = $this->ads->getIterator();

        foreach ($Iterator as $ad) {
            if ($ad->getId() == $id) {
                return $ad;
            }
        }

        return null;
    }

    public function getIterator(): ArrayIterator
    {
        return $this->ads->getIterator();
    }
}
