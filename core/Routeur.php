<?php

namespace Codez\DistrictLounge\Core;

class Routeur
{
    public function routeRequest()
    {
        $url = $_GET['url'] ?? '/';
        $urlParts = explode('/', trim($url, '/'));

        // Nettoyage des segments pour éviter les injections
        $controllerSegment = preg_replace('/[^a-zA-Z0-9]/', '', $urlParts[0] ?? '');
        $methodSegment = preg_replace('/[^a-zA-Z0-9]/', '', $urlParts[1] ?? '');

        // Détermination du nom de classe et de méthode
        $controllerName = !empty($controllerSegment) ? ucfirst($controllerSegment) . 'Controller' : 'HomeController';
        $action = !empty($methodSegment) ? $methodSegment : 'index';

        // Namespace complet du contrôleur
        $fullControllerClass = "Codez\\DistrictLounge\\Controllers\\$controllerName";


        // Vérification si la classe existe via l'autoloading de Composer
        if (!class_exists($fullControllerClass)) {
            http_response_code(404);
            include __DIR__ . '/../app/Views/errors/404.php'; // adapte le chemin si nécessaire
            error_log("Contrôleur non trouvé : $fullControllerClass");
            return;
        }
        // Instanciation du contrôleur

        $controller = new $fullControllerClass();
        $httpMethod = $_SERVER['REQUEST_METHOD'];
       $actionMethod = $action . ($httpMethod === 'GET' ? '' : ucfirst(strtolower($httpMethod)));


        echo "Contrôleur : $fullControllerClass - Méthode : $action / $actionMethod";



        $params = array_slice($urlParts, 2);

        if (method_exists($controller, $actionMethod)) {
            $reflection = new \ReflectionMethod($controller, $actionMethod);
            $requiredParams = $reflection->getNumberOfRequiredParameters();

            if (count($params) >= $requiredParams) {
                call_user_func_array([$controller, $actionMethod], $params);
            } else {
                http_response_code(404);
                echo "Erreur 404 - Paramètres insuffisants pour la méthode!";
            }
        } elseif (method_exists($controller, $action)) {
            $reflection = new \ReflectionMethod($controller, $action);
            $requiredParams = $reflection->getNumberOfRequiredParameters();

            if (count($params) >= $requiredParams) {
                call_user_func_array([$controller, $action], $params);
            } else {
                http_response_code(404);
                echo "Erreur 404 - Paramètres insuffisants pour la méthode!";
            }
        } else {
            http_response_code(404);
            echo "Erreur 404 - Méthode non trouvée!";
        }
    }
}
