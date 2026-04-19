<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class FormCard
{
    private string $_strTitle;
    private string $_strSubTitle = ''; // <-- initialize it to make it not mandatory

    /**
     * Mount the component in the DOM
     * @param string $title The title of the form
     * @param string $subtitle The subtitle of the form
     */
    public function mount(string $title, string $subtitle = ''): void
    {
        $this->_strTitle    = $title;
        $this->_strSubTitle = $subtitle;
    }

    /**
     * Collecting the title's form
     * @return string the title's form object
     */
    public function getTitle(): string
    {
        return $this->_strTitle;
    }

    /**
     * Collecting the subtitle's form
     * @return string the subtitle's form object
     */
    public function getSubTitle(): string
    {
        return $this->_strSubTitle;
    }
}
