<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// 1. On affiche TOUJOURS le header commun en haut
require_once __DIR__ . '/../app/Views/partials/header.php';

try {
    switch ($page) {
        case 'home':
            echo "<div class='container py-5'><h1>Accueil Vite & Gourmand</h1></div>";
            break;

        case 'login':
            $controller = new App\Controllers\AuthController();
            $controller->login();
            break;

        case 'register':
            $controller = new App\Controllers\AuthController();
            $controller->register();
            break;

        case 'logout':
            $controller = new App\Controllers\AuthController();
            $controller->logout();
            break;

        default:
            http_response_code(404);
            echo "<div class='container py-5'><h1>404 - Page introuvable</h1></div>";
            break;
    }
} catch (\Throwable $e) {
    // Si quoi que ce soit plante, le site affiche proprement l'erreur mais reste en ligne
    http_response_code(500);
    echo "<div class='container py-5'><h1>Une erreur temporaire est survenue</h1><p>Le reste du site fonctionne.</p></div>";
}

// 2. On affiche TOUJOURS le footer commun en bas
require_once __DIR__ . '/../app/Views/partials/footer.php';