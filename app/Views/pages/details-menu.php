

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <a href="<?= BASE_URL ?>index.php?page=menus" class="btn btn-outline-primary rounded-pill mb-4">⬅️ Retour aux menus</a>

            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-6">
                        <?php 
                            $galerie = !empty($menu['galerie']) ? explode('|', $menu['galerie']) : [];
                            $image_principale = !empty($galerie[0]) ? BASE_URL . trim($galerie[0]) : BASE_URL . 'assets/images/placeholder.jpg';
                        ?>
                        <img src="<?= htmlspecialchars($image_principale) ?>" class="img-fluid w-100 h-100" style="object-fit: cover; min-height: 350px;" alt="<?= htmlspecialchars($menu['titre']) ?>">
                    </div>

                    <div class="col-md-6 p-5 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge bg-primary text-uppercase mb-2"><?= htmlspecialchars($menu['categorie'] ?? 'Menu') ?></span>
                            <h1 class="fw-bold mb-3"><?= htmlspecialchars($menu['titre']) ?></h1>
                            
                            <h6 class="fw-bold text-secondary">Description :</h6>
                            <p class="text-muted small mb-4"><?= htmlspecialchars($menu['description']) ?></p>

                            <h6 class="fw-bold text-secondary">Composition / Plats :</h6>
                            <div class="bg-light p-3 rounded-3 mb-4">
                                <ul class="list-unstyled mb-0 small text-dark">
                                    <?php 
                                    if (!empty($menu['plats'])) {
                                        $liste_plats = explode('|', $menu['plats']);
                                        foreach ($liste_plats as $un_plat) {
                                            $un_plat = trim($un_plat);
                                            if (!empty($un_plat)) {
                                                if (strpos($un_plat, ':') !== false) {
                                                    list($titre, $details) = explode(':', $un_plat, 2);
                                                    echo "<li class='mb-2'><strong class='text-capitalize'>" . htmlspecialchars(trim($titre)) . " :</strong>" . htmlspecialchars($details) . "</li>";
                                                } else {
                                                    echo "<li class='mb-2'>" . htmlspecialchars($un_plat) . "</li>";
                                                }
                                            }
                                        }
                                    } else {
                                        echo "<li class='text-muted italic'>Aucune composition spécifiée pour le moment.</li>";
                                    }
                                    ?>
                                </ul>
                            </div>

                            <div class="p-3 rounded-3 border mb-4 bg-white shadow-sm">
                                <p class="small mb-1"><strong>Personnes minimum :</strong> <?= htmlspecialchars($menu['pers_min']) ?> pers.</p>
                                <p class="small mb-1"><strong>Allergènes :</strong> <span class="text-danger fw-bold"><?= htmlspecialchars($menu['allergene'] ?? 'Néant') ?></span></p>
                                <p class="small mb-0">
                                    <strong>Disponibilité actuelle :</strong> 
                                    <?php if ($menu['stock'] <= 0): ?>
                                        <span class="text-danger fw-bold">Victime de son succès (Épuisé)</span>
                                    <?php else: ?>
                                        <span class="text-success fw-bold">En stock (<?= htmlspecialchars($menu['stock']) ?> dispo)</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <div>
                                <span class="text-muted small">Prix par personne :</span>
                                <h3 class="fw-bold text-primary mb-0"><?= number_format($menu['prix_pers'], 2, ',', ' ') ?> €</h3>
                            </div>
                            
                            <?php if ($menu['stock'] <= 0): ?>
                                <button class="btn btn-secondary btn-lg rounded-pill px-4 fw-bold shadow-sm" disabled>Indisponible</button>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>index.php?page=panier-add&id=<?= $menu['id'] ?>" class="btn btn-cheddar">
                                    Réserver ce menu
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

