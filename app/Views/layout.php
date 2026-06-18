<?php
$total_articles = isset($_SESSION['panier']) ? array_sum($_SESSION['panier']) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand | Nos menus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/css/style.css">
</head>
<body>
    <header class="navbar-custom sticky-top shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="index.php">Vite & Gourmand</a>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=menus">Menus</a></li>
                    </ul>
                </div>
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="me-3">Bonjour, <?= htmlspecialchars($_SESSION['user_prenom']) ?></span>
                        <a href="index.php?page=logout" class="btn btn-sm btn-outline-danger">Déconnexion</a>
                    <?php else: ?>
                        <a href="index.php?page=login" class="btn btn-sm btn-dark">Connexion</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>