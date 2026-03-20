<?php

namespace FlightBookingSystem\Presentation\View;

class View
{
    private string $page;
    private string $view;
    private array $data;

    /**
     * @param array<string,string> $data
     */
    public function __construct(string $page, string $view, array $data)
    {
        $this->page = $page;
        $this->view = $view;
        $this->data = $data;
    }

    // Only include the partial view
    public function render(): void
    {
        extract($this->data);
        include __DIR__ . '/../../../views/pages/' . $this->page . '/' . $this->view . '.php';
    }

    // Inlucde base and share specific view via variable with base.php
    public function renderFull(): void
    {
        $page = $this->page;
        $view = $this->view;
        extract($this->data);

        // INFO: variable scoping (or more specifically, function scope inheritance via include).
        // In PHP, include executes the file in the same scope as where it's called.
        // Think of include as copy-pasting the file's content directly into the spot where include is called.
        include __DIR__ . '/../../../views/templates/base.php';
    }
}
