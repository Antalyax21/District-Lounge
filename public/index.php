<?php
require_once '../core/init.php';

use Codez\DistrictLounge\Controllers\HomeController;
use Codez\DistrictLounge\Core\Database;

try {
    $db = Database::getInstance()->getConnexion();
    echo "Connexion réussie !";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}

$controller = new HomeController();
$controller->index();
