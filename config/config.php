<?php

define('BASE_URL', '/vite-et-gourmand/public/');
define('APP_NAME', 'Vite & Gourmand');


 // Fonction globale pour récupérer la connexion PDO 

function getDBConnection() {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    // Identifiants 
    $host     = getenv('DB_HOST') ?: '127.0.0.1;port=3306';
    $dbname   = getenv('MYSQLDATABASE') ?: 'sc3miju5980_vite-et-gourmand'; 
    $username = getenv('DB_USER') ?: 'fastdev';
    $password = getenv('DB_PASS') ?: 'FastDev123!'; 

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
        error_log("Erreur BDD : " . $e->getMessage());
        return null; // Renvoie null pour que le contrôleur gère la panne proprement
    }
}


function getMongoConnection() {
    static $manager = null;

    if ($manager !== null) {
        return $manager;
    }

    try {
        $uri = "mongodb+srv://siciliascaletech_db_user:LexaZack11082020@cluster1.nymkcqx.mongodb.net/"; 
        $manager = new MongoDB\Driver\Manager($uri);
        return $manager;
    } catch (Exception $e) {
        error_log("Erreur MongoDB : " . $e->getMessage());
        return null;
    }
}

define('MONGO_COLLECTION_MENUS', 'viteetgourmand.menu'); // bdd.collection