<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[ORM\Table(name: 'ingredients')]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'ingredient_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'ingredient_name', length: 50)]
    private ?string $name = null;

    #[ORM\Column(name: 'ingredient_is_vegan')]
    private ?bool $isVegan = null;

    #[ORM\Column(name: 'ingredient_created_at')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'ingredient_updated_at', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(name: 'ingredient_deleted_at', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    /**
     * @var Collection<int, Allergen>
     */
    #[ORM\JoinTable('ingredients_allergens')]
    #[ORM\JoinColumn(name: 'ing_alg_ingredient', referencedColumnName: 'ingredient_id')]
    #[ORM\InverseJoinColumn(name: 'ing_alg_allergen', referencedColumnName: 'allergen_id')]
    #[ORM\ManyToMany(targetEntity: Allergen::class, inversedBy: 'ingredients')]
    private Collection $allergen;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'ingredients')]
    private Collection $products;

    public function __construct()
    {
        $this->allergen = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    public function isVegan(): ?bool
    {
        return $this->isVegan;
    }

    public function setIsVegan(bool $isVegan): static
    {
        $this->isVegan = $isVegan;

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

    /**
     * @return Collection<int, Allergen>
     */
    public function getAllergen(): Collection
    {
        return $this->allergen;
    }

    public function addAllergen(Allergen $allergen): static
    {
        if (!$this->allergen->contains($allergen)) {
            $this->allergen->add($allergen);
        }

        return $this;
    }

    public function removeAllergen(Allergen $allergen): static
    {
        $this->allergen->removeElement($allergen);

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addIngredient($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            $product->removeIngredient($this);
        }

        return $this;
    }

    /**
     * Method to check if the ingredient contains a specific getAllergen
     * @param string $allergenName the allergen name to search for (gluten/lactose)
     * @return bool true if the allergen exist, else false
     */
    public function hasAllergen(string $allergenName): bool
    {
        //loop on all the allergens in the product
        foreach ($this->allergen as $oneAllergen) {

            if (strtoupper($oneAllergen->getName()) === strtoupper($allergenName)) {

                return true;
            }
        }
        return false;
    }

}
