<?php

require 'config.php';

class Router
{
    public function routeRequest()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : '/';
        $urlParts = explode('/', trim($url, '/'));

        // Validation simple pour éviter l'injection dans le nom du contrôleur et de l'action
        $controllerSegment = preg_replace('/[^a-zA-Z0-9]/', '', $urlParts[0] ?? '');
        $methodSegment = preg_replace('/[^a-zA-Z0-9]/', '', $urlParts[1] ?? '');

        $controllerName = !empty($controllerSegment) ? ucfirst($controllerSegment) . 'Controller' : 'HomeController';
        $action = !empty($methodSegment) ? $methodSegment : 'index';

        $controllerPath = 'Controllers/' . $controllerName . '.php';

        if (!file_exists($controllerPath)) {
            http_response_code(404);
            // Affiche une page d’erreur générique ou redirige vers une page 404
            include 'views/errors/404.php';
            // Loguer l’erreur pour analyse interne
            error_log("Contrôleur non trouvé : $controllerPath");
            return;
        }


        require_once($controllerPath);
// includ 404 au lieu de echo 
        if (!class_exists($controllerName)) {
            http_response_code(404);
            echo "Erreur 404 - Classe non trouvée!";
            return;
        }

        $controller = new $controllerName();

        // Récupération de la méthode HTTP (GET, POST, etc.)
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $actionMethod = $action . ucfirst(strtolower($httpMethod));

        // Récupération des paramètres supplémentaires passés dans l'URL
        $params = array_slice($urlParts, 2);

        // Vérification avec Reflection de la méthode à appeler
        if (method_exists($controller, $actionMethod)) {
            $reflectionMethod = new ReflectionMethod($controller, $actionMethod);
            $methodParams = $reflectionMethod->getParameters();

            if (count($methodParams) > 0) {
                if (count($params) >= count($methodParams)) {
                    call_user_func_array([$controller, $actionMethod], $params);
                } else {
                    http_response_code(404);
                    echo "Erreur 404 - Paramètres insuffisants pour la méthode!";
                }
            } else {
                $controller->$actionMethod();
            }
        } elseif (method_exists($controller, $action)) {
            // Fallback : méthode sans distinction HTTP
            $reflectionMethod = new ReflectionMethod($controller, $action);
            $methodParams = $reflectionMethod->getParameters();

            if (count($methodParams) > 0) {
                if (count($params) >= count($methodParams)) {
                    call_user_func_array([$controller, $action], $params);
                } else {
                    http_response_code(404);
                    echo "Erreur 404 - Paramètres insuffisants pour la méthode!";
                }
            } else {
                $controller->$action();
            }
        } else {
            http_response_code(404);
            echo "Erreur 404 - Méthode non trouvée!";
        }
    }
}
