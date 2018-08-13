<?php
namespace Ciebit\Ads\Links\Builders;

use Ciebit\Ads\Links\Link;

class Builder
{
    private $href; #: string
    private $target; #: string

    public function build(): Link
    {
        return (new Link($this->href))
        ->setTarget($this->target);
    }

    public function setHref(string $href): self
    {
        $this->href = $href;
        return $this;
    }

    public function setTarget(string $target): self
    {
        $this->target = $target;
        return $this;
    }
}
