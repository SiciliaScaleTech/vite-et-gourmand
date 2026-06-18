<?php
// config/config.php

define('BASE_URL', '/vite-et-gourmand/public/');
define('APP_NAME', 'Vite & Gourmand');

/**
 * Fonction globale pour récupérer la connexion PDO en toute sécurité
 */
function getDBConnection() {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    // Identifiants (Configurés pour ton local, s'adaptera sur o2switch via getenv)
    $host     = getenv('DB_HOST') ?: '127.0.0.1;port=3307';
    $dbname   = getenv('MYSQLDATABASE') ?: 'viteetgourmand'; 
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS') ?: ''; 

    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 3 // Abandonne après 3 secondes si la BDD est en panne
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        // On n'utilise pas die(), on enregistre juste l'erreur dans les logs
        error_log("Erreur BDD : " . $e->getMessage());
        return null; // Renvoie null pour que le contrôleur gère la panne proprement
    }
}