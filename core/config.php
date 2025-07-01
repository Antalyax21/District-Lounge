<?php
// config.php

if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
    // Protocole HTTP ou HTTPS
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    
    // URL racine locale 
    $url = $protocol . $_SERVER['HTTP_HOST'] . "/district_lounge";
    define('ROOTPATH', $url);

    // Configuration base de données Docker
    define('DB_HOST', 'db');        // Nom du service MySQL dans docker-compose
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'root');  // Mot de passe simple pour test
    define('DB_NAME', 'district_lounge');

} else {
    // En production, adapter ici si besoin
    define('ROOTPATH', '');

    // Par défaut, config vide ou à modifier pour la prod
    define('DB_HOST', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'district_lounge');
}

