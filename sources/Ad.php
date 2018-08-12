<?php
namespace Ciebit\Ads;

use Ciebit\Ads\Banners\Collection;
use Ciebit\Ads\Status;
use DateTime;

class Ad
{
    private $banners; #: Collection
    private $dateEnd; #: DateTime
    private $dateStart; #: DateTime
    private $id; #: int
    private $name; #: string
    private $status; #: int

    public function __construct(string $name, Collection $banners, Status $status)
    {
        $this->banners = $banners;
        $this->dateEnd = new DateTime('9999-12-31 23:59:59');
        $this->dateStart = new DateTime('0001-01-01 00:00:01');
        $this->id = 0;
        $this->name = $name;
        $this->status = $status;
    }

    public function getBanners(): Collection
    {
        return $this->Banners;
    }

    public function getDateEnd(): DateTime
    {
        return $this->date_end;
    }

    public function getDateStart(): DateTime
    {
        return $this->date_start;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): Status
    {
        return $this->status;
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

    public function setStatus(Status $status): self
    {
        $this->status = $status;
        return $this;
    }
}
