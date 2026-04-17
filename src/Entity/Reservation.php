<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\Table(name: 'reservations')]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'reserv_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'reserv_time_slot')]
    private ?\DateTimeImmutable $timeSlot = null;

    #[ORM\Column(name: 'reserv_nb_people')]
    private ?int $nbPeople = null;

    #[ORM\Column(name: 'reserv_is_canceled')]
    private bool $isCanceled = false;

    #[ORM\Column(name: 'reserv_created_at')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'reserv_user', referencedColumnName: 'user_id', nullable: false)]

    private ?User $createdBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeSlot(): ?\DateTimeImmutable
    {
        return $this->timeSlot;
    }

    public function setTimeSlot(\DateTimeImmutable $timeSlot): static
    {
        $this->timeSlot = $timeSlot;

        return $this;
    }

    public function getNbPeople(): ?int
    {
        return $this->nbPeople;
    }

    public function setNbPeople(int $nbPeople): static
    {
        $this->nbPeople = $nbPeople;

        return $this;
    }

    public function isCanceled(): bool
    {
        return $this->isCanceled;
    }

    public function setIsCanceled(bool $isCanceled): static
    {
        $this->isCanceled = $isCanceled;

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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
