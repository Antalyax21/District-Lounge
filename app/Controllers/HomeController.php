<?php
namespace Codez\DistrictLounge\Controllers;

class HomeController {
    public function index() {
        require_once __DIR__ . '/../View/home.php';
    }
}
