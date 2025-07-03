<?php
// Fichier : app/Controllers/HomeController.php
namespace Codez\DistrictLounge\Controllers;

class HomeController {
    public function index() {
        require_once __DIR__ . '/../Views/home.php';
    }
}

