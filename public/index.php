<?php
require_once '../vendor/autoload.php';

use Codez\DistrictLounge\Controllers\HomeController;

$controller = new HomeController();
$controller->index();

use Codez\DistrictLounge\Core\Database;

try {
    $db = Database::getInstance()->getConnexion();
    echo "Connexion réussie !";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}