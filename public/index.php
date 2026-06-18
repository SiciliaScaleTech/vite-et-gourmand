<?php
// public/index.php

// 1. Démarrage de la session pour connecter les utilisateurs
session_start();

// 2. Inclusion de l'autoloader officiel de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// 3. Inclusion de la configuration globale
require_once __DIR__ . '/../config/config.php';

// 4. Récupération de la page demandée (ex: index.php?page=login)
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

try {
    switch ($page) {
        case 'home':
            echo "<h1>Accueil Vite & Gourmand</h1><p>L'architecture MVC est prête !</p>";
            break;

        case 'login':
            // Prochaine étape : appeler le AuthController
            echo "<h1>Page de connexion</h1>";
            break;

        default:
            http_response_code(404);
            echo "<h1>Page introuvable (404)</h1>";
            break;
    }
} catch (\Throwable $e) {
    // Si la BDD ou le code a un problème, le site affiche une erreur propre sans crasher
    http_response_code(500);
    echo "<h1>Une erreur temporaire est survenue</h1>";
    echo "<p>Le site reste accessible, mais cette fonctionnalité est indisponible.</p>";
}