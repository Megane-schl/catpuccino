<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class CatCard
{
    private int $_intId;
    private string $_strName;
    private string $_strImg;
    private string $_strDescription;
    private string $_strGender;
    private string $_strAge;
    private string $_strProduct = '';

    /**
     * Mount the component in the DOM
     * @param int $id The cat id
     * @param string $name The cat name
     * @param string $img The cat img
     * @param string $gender The cat gender
     * @param string $description The cat description
     * @param string $age The cat age
     * @param string $product The favorite cat product
     */
    public function mount(int $id, string $name, string $img, string $description, string $gender, string $age, ?string $product = ''): void
    {
        $this->_intId           = $id;
        $this->_strName         = $name;
        $this->_strImg          = $img;
        $this->_strDescription  = $description;
        $this->_strGender       = $gender;
        $this->_strAge          = $age;
        $this->_strProduct      = $product;
    }

    /**
     * Collecting the cat's id
     * @return string The cat's id object
     */
    public function getId(): int
    {
        return $this->_intId;
    }

    /**
     * Collecting the cat's name
     * @return string The cat's name object
     */
    public function getName(): string
    {
        return $this->_strName;
    }

    /**
     * Collecting the cat's img
     * @return string The cat's img object
     */
    public function getImg(): string
    {
        return $this->_strImg;
    }

    /**
     * Collecting the cat's desription
     * @return string The cat's description object
     */
    public function getDescription(): string
    {
        return $this->_strDescription;
    }

    /**
     * Collecting the cat's gender
     * @return string The cat's gender object
     */
    public function getGender(): string
    {
        return $this->_strGender;
    }

    /**
     * Collecting the cat's age
     * @return string The cat's age object
     */
    public function getAge(): string
    {
        return $this->_strAge;
    }

    /**
     * Collecting the cat's favorite product
     * @return string The cat's favorite product object
     */
    public function getProduct(): ?string
    {
        return $this->_strProduct;
    }
}
