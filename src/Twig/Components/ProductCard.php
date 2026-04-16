<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ProductCard
{
    private string $_strName;
    private float $_flPrice;
    private string $_strImg;
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
    public function mount(string $name, float $price, string $img, string $season = '', array $actions = []): void
    {
        $this->_strName         = $name;
        $this->_flPrice         = $price;
        $this->_strImg          = $img;
        $this->_strSeasonName   = $season;
        $this->_arrActions      = $actions;
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
