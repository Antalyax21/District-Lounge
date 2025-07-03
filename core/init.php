<?php

// Charger config
require_once __DIR__ . '/config.php';

// Charger composer autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Démarrer la mise en mémoire tampon
ob_start();

// Fonction utilitaire pour récupérer le type d'utilisateur
function getUserType($typeId): string
{
    $types = [
        1 => 'Admin',
        2 => 'Commercial', 
        3 => 'Client'
    ];
    
    return $types[$typeId] ?? 'Visitor'; // Valeur par défaut
}

// Fonction pour initialiser les données d'authentification
function initAuthData(): array
{
    return [
        'isConnected' => isset($_SESSION['users_id']) && !empty($_SESSION['users_id']),
        'isClient' => isset($_SESSION['type_libelle']) && $_SESSION['type_libelle'] === 'Client',
        'isCommercialOrAdmin' => isset($_SESSION['type_libelle']) && in_array($_SESSION['type_libelle'], ['Commercial', 'Admin']),
        'isAdmin' => isset($_SESSION['type_libelle']) && $_SESSION['type_libelle'] === 'Admin',
        'isVisitor' => !isset($_SESSION['users_id']) || empty($_SESSION['users_id']),
        'type_libelle' => $_SESSION['type_libelle'] ?? null,
        'userId' => $_SESSION['users_id'] ?? null,
        'userEmail' => $_SESSION['user_email'] ?? null
    ];
}

// Fonction pour mettre à jour les données d'authentification
function updateAuthData(): void
{
    $GLOBALS['authData'] = initAuthData();
    
    // Redéfinir les constantes (si ce n'est pas déjà fait)
    if (!defined('IS_CONNECTED')) {
        define('IS_CONNECTED', $GLOBALS['authData']['isConnected']);
        define('IS_CLIENT', $GLOBALS['authData']['isClient']);
        define('IS_COMMERCIAL_OR_ADMIN', $GLOBALS['authData']['isCommercialOrAdmin']);
        define('IS_ADMIN', $GLOBALS['authData']['isAdmin']);
        define('IS_VISITOR', $GLOBALS['authData']['isVisitor']);
    }
}

// Fonction pour obtenir les données d'auth fraîches
function getAuthData(): array
{
    return initAuthData();
}

// Initialiser les données d'authentification
updateAuthData();

// Classe pour gérer les constantes dynamiques 
class AuthConstants
{
    public static function isConnected(): bool
    {
        return isset($_SESSION['users_id']) && !empty($_SESSION['users_id']);
    }
    
    public static function isClient(): bool
    {
        return isset($_SESSION['type_libelle']) && $_SESSION['type_libelle'] === 'Client';
    }
    
    public static function isAdmin(): bool
    {
        return isset($_SESSION['type_libelle']) && $_SESSION['type_libelle'] === 'Admin';
    }
    
    public static function isCommercialOrAdmin(): bool
    {
        return isset($_SESSION['type_libelle']) && in_array($_SESSION['type_libelle'], ['Commercial', 'Admin']);
    }
    
    public static function isVisitor(): bool
    {
        return !isset($_SESSION['users_id']) || empty($_SESSION['users_id']);
    }

    // Dans AuthConstants (dans init.php)
public static function getUserEmail(): ?string
{
    return $_SESSION['user_email'] ?? null;
}
}

// Définir les constantes initiales
if (!defined('IS_CONNECTED')) {
    define('IS_CONNECTED', AuthConstants::isConnected());
    define('IS_CLIENT', AuthConstants::isClient());
    define('IS_COMMERCIAL_OR_ADMIN', AuthConstants::isCommercialOrAdmin());
    define('IS_ADMIN', AuthConstants::isAdmin());
    define('IS_VISITOR', AuthConstants::isVisitor());
}
