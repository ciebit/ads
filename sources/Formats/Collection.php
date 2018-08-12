<?php
namespace Ciebit\Ads\Formats;

use ArrayIterator;
use ArrayObject;
use Ciebit\Ads\Formats\Format;
use Countable;
use IteratorAggregate;

class Collection implements Countable, IteratorAggregate
{
    private $formats; #: ArrayObject

    public function __construct()
    {
        $this->formats = new ArrayObject;
    }

    public function add(Format $format): self
    {
        $this->formats->append($format);
        return $this;
    }

    public function count(): int
    {
        return $this->formats->count();
    }

    public function getArrayObject(): ArrayObject
    {
        return clone $this->formats;
    }

    public function getById(int $id): ?Format
    {
        $iterator = $this->formats->getIterator();

        foreach ($Iterator as $format) {
            if ($format->getId() == $id) {
                return $format;
            }
        }

        return null;
    }

    public function getIterator(): ArrayIterator
    {
        return $this->formats->getIterator();
    }
}
