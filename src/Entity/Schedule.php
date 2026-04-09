<?php

namespace App\Entity;

use App\Enum\WeekDay;
use App\Repository\ScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
#[ORM\Table(name: 'schedules')]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'sch_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'sch_day', enumType: WeekDay::class)]
    private ?WeekDay $day = null;

    #[ORM\Column(name: 'sch_open_time', type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $openTime = null;

    #[ORM\Column(name: 'sch_close_time', type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $closeTime = null;

    #[ORM\Column(name: 'sch_max_people')]
    private ?int $maxPeople = null;

    #[ORM\Column(name: 'sch_is_close')]
    private ?bool $isClose = null;

    #[ORM\Column(name: 'sch_created_at')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'sch_updated_at', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?WeekDay
    {
        return $this->day;
    }

    public function setDay(WeekDay $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getOpenTime(): ?\DateTimeImmutable
    {
        return $this->openTime;
    }

    public function setOpenTime(\DateTimeImmutable $openTime): static
    {
        $this->openTime = $openTime;

        return $this;
    }

    public function getCloseTime(): ?\DateTimeImmutable
    {
        return $this->closeTime;
    }

    public function setCloseTime(\DateTimeImmutable $closeTime): static
    {
        $this->closeTime = $closeTime;

        return $this;
    }

    public function getMaxPeople(): ?int
    {
        return $this->maxPeople;
    }

    public function setMaxPeople(int $maxPeople): static
    {
        $this->maxPeople = $maxPeople;

        return $this;
    }

    public function isClose(): ?bool
    {
        return $this->isClose;
    }

    public function setIsClose(bool $isClose): static
    {
        $this->isClose = $isClose;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
