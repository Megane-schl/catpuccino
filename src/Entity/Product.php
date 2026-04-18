<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'product_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'product_name', length: 100)]
    private ?string $name = null;

    #[ORM\Column(name: 'product_price', type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(name: 'product_img', length: 255)]
    private ?string $img = null;

    #[ORM\Column(name: 'product_description', type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(name: 'product_season_name', length: 100, nullable: true)]
    private ?string $seasonName = null;

    #[ORM\Column(name: 'product_period_start', type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $periodStart = null;

    #[ORM\Column(name: 'product_period_end', type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $periodEnd = null;

    #[ORM\Column(name: 'product_created_at')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'product_updated_at', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(name: 'product_deleted_at', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'product_category', referencedColumnName: 'category_id', nullable: false)]


    private ?Category $category = null;

    /**
     * @var Collection<int, Cat>
     */
    #[ORM\OneToMany(targetEntity: Cat::class, mappedBy: 'product')]
    private Collection $cats;

    /**
     * @var Collection<int, Ingredient>
     */
    #[ORM\JoinTable('products_ingredients')]
    #[ORM\JoinColumn(name: 'prd_ing_product', referencedColumnName: 'product_id')]
    #[ORM\InverseJoinColumn(name: 'prd_ing_ingredient', referencedColumnName: 'ingredient_id')]
    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'products')]
    private Collection $ingredients;

    public function __construct()
    {
        $this->cats = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
    }

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSeasonName(): ?string
    {
        return $this->seasonName;
    }

    public function setSeasonName(?string $seasonName): static
    {
        $this->seasonName = $seasonName;

        return $this;
    }

    public function getPeriodStart(): ?\DateTimeImmutable
    {
        return $this->periodStart;
    }

    public function setPeriodStart(?\DateTimeImmutable $periodStart): static
    {
        $this->periodStart = $periodStart;

        return $this;
    }

    public function getPeriodEnd(): ?\DateTimeImmutable
    {
        return $this->periodEnd;
    }

    public function setPeriodEnd(?\DateTimeImmutable $periodEnd): static
    {
        $this->periodEnd = $periodEnd;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Cat>
     */
    public function getCats(): Collection
    {
        return $this->cats;
    }

    public function addCat(Cat $cat): static
    {
        if (!$this->cats->contains($cat)) {
            $this->cats->add($cat);
            $cat->setProduct($this);
        }

        return $this;
    }

    public function removeCat(Cat $cat): static
    {
        if ($this->cats->removeElement($cat)) {
            // set the owning side to null (unless already changed)
            if ($cat->getProduct() === $this) {
                $cat->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): static
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    /**
     * Check if the product is vegan
     * @return bool True if all the ingredients in the product are vegan, else -> false
     */
    public function isVegan(): bool
    {

        //loop on all the ingredients in the product
        foreach ($this->ingredients as $ingredient) {

            if ($ingredient->isVegan() === false) {

                return false;
            }
        }
        return true;
    }

    /**
     * Check if the product is gluten free
     * @return bool True if all the ingredients in the product are without gluten, else -> false
     */
    public function isGlutenFree(): bool
    {
        //loop on all the allergens in the ingrédients product
        foreach ($this->ingredients as $ingredient) {

            if ($ingredient->hasAllergen('Gluten')) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if the product is gluten free
     * @return bool True if all the ingredients in the product are without lactose, else -> false
     */
    public function isLactoseFree(): bool
    {
        //loop on all the allergens in the ingrédients product
        foreach ($this->ingredients as $ingredient) {

            if ($ingredient->hasAllergen('Lait')) {
                return false;
            }
        }
        return true;
    }
}
