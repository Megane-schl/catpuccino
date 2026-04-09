<?php

namespace App\Entity;

use App\Enum\CatGender;
use App\Repository\CatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CatRepository::class)]
#[ORM\Table(name: 'cats')]
class Cat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'cat_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'cat_name', length: 40)]
    private ?string $name = null;

    #[ORM\Column(name: 'cat_birthdate', type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $birthdate = null;

    #[ORM\Column(name: 'cat_description', type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(name: 'cat_img', length: 255)]
    private ?string $img = null;

    #[ORM\Column(name: 'cat_gender', enumType: CatGender::class)]
    private ?CatGender $gender = null;

    #[ORM\Column(name: 'cat_created_at')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'cat_updated_at', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(name: 'cat_deleted_at', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\ManyToOne(inversedBy: 'cats')]
    #[ORM\JoinColumn(name: 'cat_product', referencedColumnName: 'product_id', nullable: true)]
    private ?Product $product = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeImmutable
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeImmutable $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getGender(): ?CatGender
    {
        return $this->gender;
    }

    public function setGender(CatGender $gender): static
    {
        $this->gender = $gender;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
