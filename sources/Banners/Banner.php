<?php
namespace Ciebit\Ads\Banners;

use Ciebit\Ads\Formats\Format;
use Ciebit\Files\File;
use DateTime;
use Psr\Link\LinkInterface;

class Banner
{
    private $dateEnd; #: DateTime
    private $dateStart; #: DateTime
    private $file; #: File
    private $format; #: Format
    private $id; #: int
    private $link; #: LinkInterface
    private $status; #: Status
    private $views; #: int

    public function __construct(File $file, Format $format, Status $status)
    {
        $this->dateEnd = new DateTime('9999-12-31 23:59:59');
        $this->dateStart = new DateTime('0001-01-01 00:00:01');
        $this->id = 0;
        $this->file = $file;
        $this->format = $format;
        $this->status = $status;
        $this->views = 0;
    }

    public function getDateEnd(): DateTime
    {
        return $this->dateEnd;
    }

    public function getDateStart(): DateTime
    {
        return $this->dateStart;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFile(): File
    {
        return $this->file;
    }

    public function getFormat(): Format
    {
        return $this->Format;
    }

    public function getLink(): ?LinkInterface
    {
        return $this->link;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function setDateEnd(DateTime $date_end): self
    {
        $this->date_end = $date_end;
        return $this;
    }

    public function setDateStart(DateTime $date_start): self
    {
        $this->date_start = $date_start;
        return $this;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;
        return $this;
    }

    public function setFormat(Format $format): self
    {
        $this->format = $format;
        return $this;
    }

    public function setLink(LinkInterface $link): self
    {
        $this->link = $link;
        return $this;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;
        return $this;
    }
}
