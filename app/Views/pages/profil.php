<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h2 class="mb-0 fw-bold">Mon Espace Client</h2>
            <p class="text-muted mb-0">Ravi de vous revoir, <?= htmlspecialchars($user['prenom']) ?> !</p>
        </div>
        <span class="badge bg-secondary px-3 py-2 text-uppercase rounded-pill shadow-sm">
            Statut : <?= htmlspecialchars($user['role']) ?>
        </span>
    </div>

    <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill fw-bold me-2" id="pills-profil-tab" data-bs-toggle="pill" data-bs-target="#pills-profil" type="button" role="tab">
                👤 Mes Informations
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-bold" id="pills-commandes-tab" data-bs-toggle="pill" data-bs-target="#pills-commandes" type="button" role="tab">
                📦 Mes Commandes (<?= count($commandes) ?>)
            </button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="pills-profil" role="tabpanel">
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">
                            <?= $message ?>
                            <form method="POST" action="<?= BASE_URL ?>index.php?page=profil-update">
                                <h4 class="mb-3 text-muted fs-5 fw-bold">Informations personnelles</h4>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Prénom</label>
                                        <input type="text" name="prenom" class="form-control rounded-pill" value="<?= htmlspecialchars($user['prenom'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Nom</label>
                                        <input type="text" name="nom" class="form-control rounded-pill" value="<?= htmlspecialchars($user['nom'] ?? '') ?>" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Adresse Email</label>
                                        <input type="email" name="email" class="form-control rounded-pill" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Numéro de téléphone</label>
                                        <input type="tel" name="telephone" class="form-control rounded-pill" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>" required>
                                    </div>
                                </div>

                                <hr class="my-4">
                                <h4 class="mb-3 text-muted fs-5 fw-bold">Adresse de livraison</h4>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Adresse</label>
                                    <input type="text" name="adresse" class="form-control rounded-4" value="<?= htmlspecialchars($user['adresse'] ?? '') ?>" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-4">
                                        <label class="form-label fw-bold">Code Postal</label>
                                        <input type="text" name="code_postal" class="form-control rounded-pill" value="<?= htmlspecialchars($user['code_postal'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-8 mb-4">
                                        <label class="form-label fw-bold">Ville</label>
                                        <input type="text" name="ville" class="form-control rounded-pill" value="<?= htmlspecialchars($user['ville'] ?? '') ?>" required>
                                    </div>
                                </div>
                                <button type="submit" name="update_profile" class="btn btn-cheddar rounded-pill w-100 fw-bold shadow-sm">Enregistrer les modifications</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-cheddar mb-3">Donnez votre avis</h5>
                            <?= $message_avis ?>
                            <form action="<?= BASE_URL ?>index.php?page=profil-avis" method="POST">
                                <div class="mb-3">
                                    <select name="note" class="form-select rounded-pill" required>
                                        <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                        <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                        <option value="3">⭐⭐⭐ (3/5)</option>
                                        <option value="2">⭐⭐ (2/5)</option>
                                        <option value="1">⭐ (1/5)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <textarea name="commentaire" class="form-control rounded-4" rows="3" placeholder="Votre expérience avec Julie et José..." required></textarea>
                                </div>
                                <button type="submit" name="submit_avis" class="btn btn-outline-dark btn-sm rounded-pill w-100 fw-bold">Envoyer mon avis</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-commandes" role="tabpanel">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <?php if (empty($commandes)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted fs-5">Vous n'avez pas encore passé de commande.</p>
                            <a href="<?= BASE_URL ?>index.php?page=menus" class="btn btn-cheddar rounded-pill fw-bold px-4">Découvrir la carte</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($commandes as $cmd): ?>
                                        <tr>
                                            <td><strong>#<?= $cmd['id'] ?></strong></td>
                                            <td><?= date('d/m/Y H:i', strtotime($cmd['date_commande'])) ?></td>
                                            <td class="fw-bold"><?= number_format($cmd['total'], 2) ?> €</td>
                                            <td>
                                                <?php 
                                                    $badgeColor = 'bg-warning text-dark';
                                                    if($cmd['statut'] === 'livrée' || $cmd['statut'] === 'payée') $badgeColor = 'bg-success text-white';
                                                    if($cmd['statut'] === 'annulée') $badgeColor = 'bg-danger text-white';
                                                ?>
                                                <span class="badge rounded-pill <?= $badgeColor ?>">
                                                    <?= htmlspecialchars($cmd['statut']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalCmd<?= $cmd['id'] ?>">
                                                    Détails
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (in_array($user['role'] ?? '', ['employe', 'admin'])): ?>
        <div class="mt-4 text-center">
            <a href="employe/employe-dashboard.php" class="btn btn-dark rounded-pill fw-bold shadow-sm px-5 py-2">
                Accéder au Tableau de Bord Professionnel
            </a>
        </div>
    <?php endif; ?>
</main>

<?php foreach ($commandes as $cmd): ?>
<div class="modal fade" id="modalCmd<?= $cmd['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold">Détails de la commande #<?= $cmd['id'] ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-close="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-3"><small>Passée le : <?= date('d/m/Y à H:i', strtotime($cmd['date_commande'])) ?></small></p>
                <ul class="list-group list-group-flush mb-3">
                    <?php if (isset($details_commandes[$cmd['id']])): ?>
                        <?php foreach ($details_commandes[$cmd['id']] as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <span class="fw-bold text-primary"><?= $item['quantite'] ?>x</span> 
                                    <?= htmlspecialchars($item['titre']) ?>
                                </div>
                                <span class="text-muted"><?= number_format($item['prix_unitaire'] * $item['quantite'], 2) ?> €</span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-muted px-0">Aucun détail disponible pour cette commande.</li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex justify-content-between font-weight-bold fs-5 border-top pt-3">
                    <strong>Total payé :</strong>
                    <strong class="text-success"><?= number_format($cmd['total'], 2) ?> €</strong>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>