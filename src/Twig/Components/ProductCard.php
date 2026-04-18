<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ProductCard
{
    private int $_intId;
    private string $_strName;
    private float $_flPrice;
    private string $_strImg;
    private string $_strCategory;
    private ?string $_strSeasonName = '';
    private array $_arrActions;

    /**
     * Mount the component in the DOM
     * @param string $name The name of the product
     * @param float $price The price of the product
     * @param string $img The image of the product
     * @param string $season The season name of the product
     * @param array $actions The differents buttons depend on the user roles
     */
    public function mount(int $id, string $name, float $price, string $img, string $category, string $season = '', array $actions = []): void
    {
        $this->_intId           = $id;
        $this->_strName         = $name;
        $this->_flPrice         = $price;
        $this->_strImg          = $img;
        $this->_strCategory     = $category;
        $this->_strSeasonName   = $season;
        $this->_arrActions      = $actions;
    }

    /**
     * Collecting the product id's
     * @return int the product id's object
     */
    public function getId(): int
    {
        return $this->_intId;
    }

    /**
     * Collecting the product name's
     * @return string the product name's object
     */
    public function getName(): string
    {
        return $this->_strName;
    }

    /**
     * Collecting the product price's
     * @return float the product price's object
     */
    public function getPrice(): float
    {
        return $this->_flPrice;
    }

    /**
     * Collecting the product image's
     * @return string the product image's object
     */
    public function getImg(): string
    {
        return $this->_strImg;
    }

    /**
     * Collecting the product category's
     * @return string the product category's object
     */
    public function getCategory(): string
    {
        return $this->_strCategory;
    }

    /**
     * Collecting the product season name's
     * @return string the product season name's object
     */
    public function getSeasonName(): ?string
    {
        return $this->_strSeasonName;
    }

    /**
     * Collecting the product buttons actions
     * @return array the product buttons actions object
     */
    public function getActions(): array
    {
        return $this->_arrActions;
    }
}
