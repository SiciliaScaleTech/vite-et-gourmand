<?php
// Gestion du panier
$total_articles = 0;
if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    $total_articles = array_sum($_SESSION['panier']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/css/nos-menus.css">
</head>
<body>
    <header class="navbar-custom sticky-top shadow-sm">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="<?= BASE_URL ?>index.php">
                    <img src="<?= BASE_URL ?>assets/Logo-Vite&Gourmand.png" alt="Logo" width="70">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php">Accueil</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=menus">Menus</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=contact">Contact</a></li>
                    </ul>
                </div>

                <div class="d-flex align-items-center">
                    <a href="<?= BASE_URL ?>index.php?page=panier" class="cart-icon me-3 position-relative">🛒
                        <?php if ($total_articles > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger small">
                                <?= $total_articles ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <a class="btn btn-outline-dark rounded-pill dropdown-toggle border-0 fw-bold" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= htmlspecialchars($_SESSION['user_prenom'] ?? 'Mon Compte') ?> 👤
                            </a>
                            
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li>
                                    <a class="dropdown-item py-2" href="<?= BASE_URL ?>index.php?page=profil">Mon profil</a>
                                </li>
                                
                                <?php if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['employe', 'admin'])): ?>
                                    <li>
                                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                            <a class="dropdown-item py-2 fw-bold text-success" href="<?= BASE_URL ?>index.php?page=admin-dashboard">Dashboard Admin</a>
                                        <?php else: ?>

                                        <a class="dropdown-item py-2 fw-bold text-primary" href="<?= BASE_URL ?>index.php?page=employe-dashboard">Dashboard Employé</a>                                       
                                        <?php endif; ?>
                                    </li>
                                <?php endif; ?>
                                
                                <li><hr class="dropdown-divider"></li>
                                
                                <li>
                                    <a class="dropdown-item py-2 text-danger" href="<?= BASE_URL ?>index.php?page=logout">Déconnexion</a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>index.php?page=login" class="btn btn-dark rounded-pill px-4">Connexion</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>