

<section class="hero-banner text-center py-5">
    <div class="container my-auto">
        <h1 class="display-5 display-md-3 fw-bold mb-3">Vite & Gourmand</h1>
        <p class="fs-5 fs-md-4 mb-4">Nos Menus Thématiques</p>
        <a href="#menu-container" class="btn btn-cheddar btn-lg px-4 px-md-5 py-2 py-md-3 rounded-pill fw-bold w-100 w-sm-auto">
            Commander maintenant
        </a>
    </div>
</section>

<main class="container py-5">
    <section class="card shadow-sm border-0 bg-mimolette-light p-4 mb-5">
        <form id="filterForm" method="GET" action="<?= BASE_URL ?>index.php" class="row g-3 align-items-end">
            <input type="hidden" name="page" value="menus">
            
            <div class="col-md-3">
                <label class="form-label fw-bold">Thème</label>
                <select name="theme" class="form-select border-0 shadow-sm" onchange="filterMenus()">
                    <option value="">Tous les thèmes</option>
                    <option value="Noel" <?= ($filters['theme'] == 'Noel') ? 'selected' : '' ?>>Noël</option>
                    <option value="Paques" <?= ($filters['theme'] == 'Paques') ? 'selected' : '' ?>>Pâques</option>
                    <option value="Halloween" <?= ($filters['theme'] == 'Halloween') ? 'selected' : '' ?>>Halloween</option>
                    <option value="Classique" <?= ($filters['theme'] == 'Classique') ? 'selected' : '' ?>>Classique</option>
                    <option value="Mariage" <?= ($filters['theme'] == 'Mariage') ? 'selected' : '' ?>>Mariage</option>
                    <option value="Bapteme" <?= ($filters['theme'] == 'Bapteme') ? 'selected' : '' ?>>Baptême</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-bold">Prix Max</label>
                <input type="number" name="prix_max" class="form-control border-0 shadow-sm" value="<?= htmlspecialchars($filters['prix_max']) ?>" oninput="filterMenus()">
            </div>

            <div class="col-md-2">
                <label class="form-label fw-bold">Sans allergène</label>
                <select name="allergene" class="form-select border-0 shadow-sm" onchange="filterMenus()">
                    <option value="">Aucun</option>
                    <option value="gluten" <?= ($filters['allergene'] == 'gluten') ? 'selected' : '' ?>>Gluten</option>
                    <option value="lactose" <?= ($filters['allergene'] == 'lactose') ? 'selected' : '' ?>>Lactose</option>
                    <option value="oeufs" <?= ($filters['allergene'] == 'oeufs') ? 'selected' : '' ?>>Œufs</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-bold">Pers. Min</label>
                <input type="number" name="pers_min" class="form-control border-0 shadow-sm" value="<?= htmlspecialchars($filters['pers_min']) ?>" oninput="filterMenus()">
            </div>

            <div class="col-12 text-center mt-4">
                <button type="submit" class="btn btn-cheddar rounded-pill px-5 fw-bold shadow">
                    Actualiser les menus
                </button>
            </div>
        </form>
    </section>

    <div class="container my-5">
        <h2 class="text-center mb-5 fw-bold">Nos Menus</h2>

        <div class="row g-4" id="menu-container">
            <?php if (empty($menus)): ?>
                <p class="text-center text-muted">Aucun menu disponible en base de données pour le moment.</p>
            <?php else: ?>
                <?php foreach ($menus as $menu): 
                    $galerie = explode('|', $menu['galerie']);
                    $imageVignette = !empty($galerie[0]) ? BASE_URL . trim($galerie[0]) : BASE_URL . 'assets/images/placeholder.jpg';
                ?>
                <div class="col-md-4 menu-item" 
                     data-theme="<?= htmlspecialchars($menu['nom_technique']) ?>" 
                     data-prix="<?= htmlspecialchars($menu['prix_pers']) ?>"
                     data-pers-min="<?= htmlspecialchars($menu['pers_min']) ?>"
                     data-allergenes="<?= htmlspecialchars(strtolower($menu['allergene'] ?? '')) ?>">

                    <div class="card h-100 shadow-sm border-0 card-hover">
                        <img src="<?= $imageVignette ?>" class="card-img-top" alt="<?= htmlspecialchars($menu['titre']) ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($menu['titre']) ?></h5>
                            <p class="card-text text-muted flex-grow-1">
                                <?= htmlspecialchars(mb_strimwidth($menu['description'], 0, 100, "...")) ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="h5 mb-0 text-primary fw-bold"><?= number_format($menu['prix_pers'], 2, ',', ' ') ?>€ <small class="text-muted fs-6">/ pers</small></span>
                                <a href="<?= BASE_URL ?>index.php?page=details-menu&id=<?= $menu['id'] ?>" class="btn btn-outline-primary rounded-pill">Voir le détail</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="no-result-message" class="text-center py-5" style="display: none;">
            <h3 class="mt-3 fw-bold text-muted">Aucun menu ne correspond à vos critères</h3>
            <p class="text-secondary">Modifier vos filtres pour découvrir de nouvelles saveurs !</p>
        </div>
    </div>
</main>

<?php foreach ($menus_details as $id => $info) : ?>
<div class="modal fade" id="modal<?= $id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-mimolette border-0">
                <h5 class="modal-title fw-bold"><?= htmlspecialchars($info['titre']); ?></h5>
                <button type="button" class="btn-close" data-bs-toggle="collapse" data-bs-target="#modal<?= $id; ?>"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="main-img-container mb-3">
                            <img src="<?= BASE_URL . $info['galerie'][0]; ?>" id="mainImg<?= $id; ?>" class="img-fluid rounded-4 shadow-sm w-100" style="height: 250px; object-fit: cover;">
                        </div>
                        <div class="d-flex gap-2">
                            <?php foreach($info['galerie'] as $img) : ?>
                                <img src="<?= BASE_URL . $img; ?>" class="img-thumbnail rounded-3 thumb-gallery" style="width: 60px; height: 45px; object-fit: cover; cursor: pointer;">
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Description :</h6>
                        <p class="small text-muted"><?= htmlspecialchars($info['description']); ?></p>
                        <h6 class="fw-bold mt-3">Composition :</h6>
                        <ul class="small mb-3">
                            <?php foreach($info['plats'] as $plat): ?>
                                <li><?= htmlspecialchars($plat); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="bg-light p-3 rounded-3">
                            <p class="small mb-1"><strong>👥 Personnes minimum :</strong> <?= $info['pers_min']; ?></p>
                            <p class="small mb-1"><strong>🕒 Conditions :</strong> <?= htmlspecialchars($info['conditions']); ?></p>
                            <p class="small mb-0"><strong>⚠️ Allergènes :</strong> <span class="text-danger"><?= htmlspecialchars($info['allergene']); ?></span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-mimolette-light py-3">
                <div class="d-flex justify-content-between align-items-center w-100 px-3">
                    <div>
                        <span class="text-muted small">Total pour <?= $info['pers_min']; ?> pers. min :</span>
                        <h4 class="fw-bold text-cheddar mb-0"><?= ($info['prix_pers'] * $info['pers_min']); ?> €</h4>
                    </div>
                    <button type="button" class="btn btn-cheddar rounded-pill px-4 fw-bold shadow-sm">Réserver ce menu</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>


<script src="<?= BASE_URL ?>styles/js/nos-menus.js"></script>