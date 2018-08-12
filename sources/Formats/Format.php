<?php
namespace Ciebit\Ads\Formats;

use Ciebit\Ads\Formats\Status;

class Format
{
    private $height; #: int
    private $id; #: int
    private $name; #: string
    private $status; #: int
    private $width; #: string

    public function __construct(string $name, int $width, int $height, Status $status)
    {
        $this->height = $height;
        $this->id = 0;
        $this->name = $name;
        $this->status = $status;
        $this->width = $width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getWidth(): string
    {
        return $this->width;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }
}
