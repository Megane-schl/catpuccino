<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class SearchBar
{
    private string $_strAction;
    private string $_strPlaceholder;
    private string $_strValue = '';

    /**
     * Mount the component in the DOM
     * @param string $title The action of thesearchbar
     * @param string $subtitle The placeholder of the searchbar
     * @param string $value The value in the searchbar
     */

    public function mount(string $action, string $placeholder, string $value = ''): void
    {
        $this->_strAction       = $action;
        $this->_strPlaceholder  = $placeholder;
        $this->_strValue        = $value;
    }

    /**
     * Collecting the action from the searchbar
     * @return string the action searchbar object
     */
    public function getAction(): string
    {
        return $this->_strAction;
    }

    /**
     * Collecting the placeholder from the searchbar
     * @return string the placeholder searchbar object
     */
    public function getPlaceholder(): string
    {
        return $this->_strPlaceholder;
    }

    /**
     * Collecting the value enter from the searchbar
     * @return string the value enter searchbar object
     */
    public function getValue(): ?string
    {
        return $this->_strValue;
    }
}
