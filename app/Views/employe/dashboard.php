<main class="container py-4 py-md-5">
    <div class="mb-4 mb-md-5 border-bottom pb-3">
        <h1 class="fw-bold mb-3 fs-3 fs-md-1">Tableau de bord - Employé</h1>
        
        <div class="d-flex flex-wrap align-items-center gap-2">
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <a href="<?= BASE_URL ?>index.php?page=admin-dashboard" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-2 fw-bold shadow-sm text-center">
                    ⬅Espace Admin
                </a>
            <?php endif; ?>
            
            <a href="index.php?page=employe-carte" class="btn btn-sm btn-dark border-0 rounded-pill px-3 py-2 fw-bold shadow-sm text-center">
                Gestion de la Carte
            </a>

            <a href="index.php?page=employe-avis" class="btn btn-sm btn-warning border-0 rounded-pill px-3 py-2 fw-bold shadow-sm text-dark text-center">
                Gérer les Avis Clients
            </a>

            <a href="index.php?page=employe-messages" class="btn btn-sm btn-info border-0 rounded-pill px-3 py-2 fw-bold shadow-sm text-dark text-center">
                Messages Reçus
            </a>
        </div>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show fw-bold mb-4" role="alert">
            La commande a été supprimée avec succès.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow border-0 rounded-4 overflow-hidden d-none d-lg-block">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="p-3">N° Commande</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th class="text-center p-3" style="width: 220px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($commandes)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Aucune commande pour le moment.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($commandes as $c): ?>
                                <?php $classe_couleur = $couleurs_statut[$c['statut']] ?? 'bg-secondary text-white'; ?>
                                <tr>
                                    <td class="p-3 fw-bold">#<?= $c['id'] ?></td>
                                    <td><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($c['date_commande'])) ?></td>
                                    <td class="fw-bold"><?= number_format($c['total'], 2, ',', ' ') ?> €</td>
                                    <td>
                                        <span class="badge <?= $classe_couleur ?> text-uppercase px-3 py-2 shadow-sm" style="font-size: 0.85rem;">
                                            <?= htmlspecialchars($c['statut']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center p-3">
                                        <div class="d-flex gap-2 justify-content-center">
                                        <a href="<?= BASE_URL ?>index.php?page=employe-details-commande&id=<?= $c['id'] ?>" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
                                            Modifier
                                        </a>       
                                        <a href="index.php?page=employe-dashboard&action=supprimer&id_commande=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Supprimer définitivement la commande #<?= $c['id'] ?> ?');">Supprimer</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-block d-lg-none">
        <?php if (empty($commandes)): ?>
            <div class="card shadow border-0 p-4 text-center text-muted rounded-4">Aucune commande pour le moment.</div>
        <?php else: ?>
            <?php foreach ($commandes as $c): ?>
                <?php $classe_couleur = $couleurs_statut[$c['statut']] ?? 'bg-secondary text-white'; ?>
                <div class="card shadow border-0 mb-3 rounded-4 overflow-hidden">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <span class="fw-bold text-dark fs-5">#<?= $c['id'] ?></span>
                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($c['date_commande'])) ?></small>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between my-1">
                                <span class="text-muted small">Client :</span>
                                <span class="fw-semibold text-dark"><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></span>
                            </div>
                            <div class="d-flex justify-content-between my-1">
                                <span class="text-muted small">Montant total :</span>
                                <span class="fw-bold text-success fs-5"><?= number_format($c['total'], 2, ',', ' ') ?> €</span>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <span class="badge <?= $classe_couleur ?> text-uppercase w-100 py-2 shadow-sm">
                                <?= htmlspecialchars($c['statut']) ?>
                            </span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="index.php?page=employe-details-commande&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-dark rounded-pill w-50 py-2 fw-bold">Modifier</a>
                            <a href="index.php?page=employe-dashboard&action=supprimer&id_commande=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill w-50 py-2" onclick="return confirm('Supprimer la commande #<?= $c['id'] ?> ?');">Supprimer</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>