<?php
require_once '../core/init.php';
use Codez\DistrictLounge\Controllers\HomeController;
use Codez\DistrictLounge\Core\Database;
use Codez\DistrictLounge\Core\Routeur;
ob_start(); // Démarre la mise en tampon de sortie


 try {
 $db = Database::getInstance()->getConnexion();
 echo "Connexion réussie !";
 } catch (Exception $e) {
 echo "Erreur : " . $e->getMessage();
} 


$controller = new HomeController();
$controller->index();

$router = new Routeur();
$router->routeRequest();

ob_end_flush(); // Envoie le contenu du tampon de sortie
