<?php
namespace Ciebit\Ads\Banners;

use Ciebit\Ads\Banners\Status;
use Ciebit\Ads\Formats\Format;
use Ciebit\Ads\Links\Link;
use Ciebit\Files\File;
use DateTime;

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
        return $this->format;
    }

    public function getLink(): ?Link
    {
        return $this->link;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function setDateEnd(DateTime $date): self
    {
        $this->dateEnd = $date;
        return $this;
    }

    public function setDateStart(DateTime $date): self
    {
        $this->dateStart = $date;
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

    public function setLink(Link $link): self
    {
        $this->link = $link;
        return $this;
    }

    public function setStatus(Status $status): self
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
