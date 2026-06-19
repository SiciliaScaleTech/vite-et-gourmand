<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

$pdo = getDBConnection();

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

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

        case 'menus':
            require_once __DIR__ . '/../app/Controllers/MenuController.php';
            $controller = new MenuController();
            $controller->index();
            break;

        case 'details-menu':
            require_once __DIR__ . '/../app/Controllers/MenuController.php';
            $controller = new MenuController();
            $controller->showDetails();
            break;

       case 'panier':
            require_once __DIR__ . '/../app/Controllers/CartController.php';
            $controller = new CartController();
            $controller->index();
            break;

        case 'panier-add':
            require_once __DIR__ . '/../app/Controllers/CartController.php';
            $controller = new CartController();
            $controller->add();
            break;

        case 'panier-update':
            require_once __DIR__ . '/../app/Controllers/CartController.php';
            $controller = new CartController();
            $controller->update();
            break;

        case 'panier-delete':
            require_once __DIR__ . '/../app/Controllers/CartController.php';
            $controller = new CartController();
            $controller->delete();
            break;

        case 'panier-validate':
            require_once __DIR__ . '/../app/Controllers/CartController.php';
            $controller = new CartController();
            $controller->validate();
            break;

        case 'confirmation':
            require_once __DIR__ . '/../app/Controllers/CartController.php';
            $controller = new CartController();
            $controller->confirmation();
            break;

        case 'contact':
            require_once __DIR__ . '/../app/Controllers/ContactController.php';
            $controller = new ContactController();
            $controller->index();
            break;

        case 'contact-process':
            require_once __DIR__ . '/../app/Controllers/ContactController.php';
            $controller = new ContactController();
            $controller->process();
            break;

        default:
            http_response_code(404);
            echo "<div class='container py-5'><h1>404 - Page introuvable</h1></div>";
            break;
    }
} catch (\Throwable $e) {
    http_response_code(500);
    echo "<div class='container py-5'><h1>Une erreur temporaire est survenue</h1><p>Le reste du site fonctionne.</p></div>";
}

require_once __DIR__ . '/../app/Views/partials/footer.php';