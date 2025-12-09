<?php

namespace App\Core;

class Controller
{
    protected function view(string $template, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../../views/layouts/header.php';
        include __DIR__ . '/../../views/' . $template . '.php';
        include __DIR__ . '/../../views/layouts/footer.php';
    }
}
