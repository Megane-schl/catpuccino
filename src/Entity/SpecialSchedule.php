<?php

namespace App\Entity;

use App\Repository\SpecialScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecialScheduleRepository::class)]
#[ORM\Table(name: 'specials_schedules')]
class SpecialSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'spe_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'spe_date', type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(name: 'spe_open_time', type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $openTime = null;

    #[ORM\Column(name: 'spe_close_time', type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $closeTime = null;

    #[ORM\Column(name: 'spe_is_closed')]
    private ?bool $isClosed = null;

    #[ORM\Column(name: 'spe_max_people')]
    private ?int $maxPeople = null;

    #[ORM\Column(name: 'spe_created_at')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'spe_updated_at', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(name: 'spe_deleted_at', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

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

    public function isClosed(): ?bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(bool $isClosed): static
    {
        $this->isClosed = $isClosed;

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

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
