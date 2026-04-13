<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class BootstrapModal
{
    private string $_strId;
    private string $_strTitle;
    private string $_strMessage;
    private string $_strAction;
    private string $_strCsrfToken;

    /**
     * Mount the component in the DOM
     * @param string $id The unique ID of the modal*
     * @param string $title The title of the modal
     * @param string $message The confirmation message of the modal
     * @param string $action The URL to submit the form to
     * @param string $token The CSRF token key
     * 
     */
    public function mount(string $id, string $title, string $message, string $action, string $token): void
    {
        $this->_strId    = $id;
        $this->_strTitle = $title;
        $this->_strMessage = $message;
        $this->_strAction = $action;
        $this->_strCsrfToken = $token;
    }

    /**
     * Collecting the modal id's
     * @return string the modal id's object
     */
    public function getId(): string
    {
        return $this->_strId;
    }

    /**
     * Collecting the modal title's
     * @return string the modal title's object
     */
    public function getTitle(): string
    {
        return $this->_strTitle;
    }

    /**
     * Collecting the modal message's
     * @return string the modal message's object
     */
    public function getMessage(): string
    {
        return $this->_strMessage;
    }

    /**
     * Collecting the modal action's
     * @return string the modal action's object
     */
    public function getAction(): string
    {
        return $this->_strAction;
    }

    /**
     * Collecting the modal csrf token's
     * @return string the modal csrf token's object
     */
    public function getToken(): string
    {
        return $this->_strCsrfToken;
    }
}
