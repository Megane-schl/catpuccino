<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class BootstrapButton
{
    private string $_strText;
    private string $_strType = ""; // Initialise an empty string in case outlined = false
    private string $_strLink;
    private string $_strSize = "";

    /**
     * Mount the component in the DOM
     * 
     * @param string $text The text writes in the button
     * @param string $type Button type : success, primary, warning, info, secondary, danger, light, dark
     * @param string $link The URL the button points to 
     * @param bool $outlined If true, uses the outlined version of the button
     * @param string $size Size of the bootstrap button : lg, sm or empty '' for default (normal size)
     */
    public function mount(string $text, string $type, string $link, bool $outlined = false, string $size = ''): void
    {
        $this->_strText = $text;
        $this->_strLink = $link;
        $this->_strSize = $size;

        if ($outlined) {
            $this->_strType = 'outline-';
        }

        $this->_strType .= $type;
    }

    /**
     * Collecting the button text
     * @return string the button text object
     */
    public function getText(): string
    {
        return $this->_strText;
    }

    /**
     * Collecting the bootstrap class
     * @return string the bootstrap class object
     */
    public function getType(): string
    {
        return $this->_strType;
    }

    /**
     * Collecting the button's link
     * @return string the button's link URL
     */
    public function getLink(): string
    {
        return $this->_strLink;
    }

    /**
     * Collecting the bootstrap button size
     * @return string the bootstrap button size object
     */
    public function getSize(): string
    {
        return $this->_strSize;
    }
}
