<?php
namespace Ciebit\Ads\Links;

class Link
{
    private $href; #: string
    private $target; #: string

    public function __construct(string $href = '')
    {
        $this->href = $href;
        $this->target = '';
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function setTarget(string $target): self
    {
        $this->target = $target;
        return $this;
    }
}
