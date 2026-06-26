<?php
/** @var array $employes */
/** @var array $labels_graphique */
/** @var array $donnees_graphique */
/** @var float $chiffre_affaires */
/** @var string $message */
/** @var string $messageClass */
/** @var string $date_debut */
/** @var string $date_fin */
?>
<main class="container py-4 py-md-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-4 mb-md-5 border-bottom pb-3">
        <div>
            <h1 class="fw-bold mb-1 fs-2 fs-md-1">Espace Administrateur</h1>
            <p class="text-muted mb-0 small">Gestion du personnel et analyse de l'activité</p>
        </div>
        <div class="d-flex flex-column flex-sm-row gap-2">
            <a href="<?= BASE_URL ?>index.php?page=employe-dashboard" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-2 fw-bold text-center">
                Gérer les Commandes (Vue Employé)
            </a>
            <a href="#statsSection" class="btn btn-sm btn-primary border-0 rounded-pill px-3 py-2 fw-bold shadow-sm text-center">
                Voir les Graphiques & CA
            </a>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert <?= $messageClass ?> alert-dismissible fade show fw-bold mb-4" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'status_updated'): ?>
        <div class="alert alert-success alert-dismissible fade show fw-bold mb-4" role="alert">
            Le statut du compte employé a bien été modifié.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow border-0 rounded-4 p-3 p-md-4">
                <h4 class="fw-bold text-dark mb-3 fs-5">Créer un compte Employé</h4>
                <form method="POST" action="<?= BASE_URL ?>index.php?page=admin-dashboard">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Prénom</label>
                        <input type="text" name="prenom" class="form-control" placeholder="Ex: Jean" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nom</label>
                        <input type="text" name="nom" class="form-control" placeholder="Ex: Dupont" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Adresse Email</label>
                        <input type="email" name="email" class="form-control" placeholder="employe@viteetgourmand.fr" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Mot de passe initial</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" name="creer_employe" class="btn btn-dark w-100 rounded-pill fw-bold py-2 mt-2">
                        Créer le compte & notifier
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow border-0 rounded-4 overflow-hidden h-100">
                <div class="card-header bg-dark text-white p-3">
                    <h5 class="mb-0 fw-bold py-1 fs-6">Comptes Employés existants</h5>
                </div>
                <div class="card-body p-0">
                    
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="p-3">Employé</th>
                                    <th>Email</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-center p-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($employes)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Aucun employé enregistré.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($employes as $emp): ?>
                                        <tr>
                                            <td class="p-3 fw-bold"><?= htmlspecialchars($emp['prenom'] . ' ' . $emp['nom']) ?></td>
                                            <td><?= htmlspecialchars($emp['email']) ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-<?= $emp['actif'] == 1 ? 'success' : 'danger' ?>">
                                                    <?= $emp['actif'] == 1 ? 'Actif' : 'Désactivé' ?>
                                                </span>
                                            </td>
                                            <td class="text-center p-3">
                                                <a href="<?= BASE_URL ?>index.php?page=admin-dashboard&action=<?= $emp['actif'] == 1 ? 'desactiver' : 'activer' ?>&id_user=<?= $emp['id'] ?>" 
                                                   class="btn btn-sm btn-outline-<?= $emp['actif'] == 1 ? 'danger' : 'success' ?> rounded-pill px-3"
                                                   onclick="return confirm('Confirmer l\'action sur ce compte ?');">
                                                    <?= $emp['actif'] == 1 ? 'Bloquer' : 'Réactiver' ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-block d-md-none p-3">
                        <?php if (empty($employes)): ?>
                            <div class="text-center text-muted py-3">Aucun employé enregistré.</div>
                        <?php else: ?>
                            <?php foreach ($employes as $emp): ?>
                                <div class="p-3 mb-2 border rounded-3 bg-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-dark"><?= htmlspecialchars($emp['prenom'] . ' ' . $emp['nom']) ?></span>
                                        <span class="badge bg-<?= $emp['actif'] == 1 ? 'success' : 'danger' ?> small">
                                            <?= $emp['actif'] == 1 ? 'Actif' : 'Désactivé' ?>
                                        </span>
                                    </div>
                                    <div class="text-muted small mb-3"><?= htmlspecialchars($emp['email']) ?></div>
                                    <a href="<?= BASE_URL ?>index.php?page=admin-dashboard&action=<?= $emp['actif'] == 1 ? 'desactiver' : 'activer' ?>&id_user=<?= $emp['id'] ?>" 
                                       class="btn btn-sm btn-outline-<?= $emp['actif'] == 1 ? 'danger' : 'success' ?> w-100 rounded-pill py-2"
                                       onclick="return confirm('Confirmer l\'action sur ce compte ?');">
                                        <?= $emp['actif'] == 1 ? '🔒 Bloquer le compte' : '🔓 Réactiver le compte' ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="statsSection" class="mt-5 pt-2">
        <hr class="my-4 my-md-5">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1 fs-4">Analyse des Ventes & CA</h3>
            </div>
            
            <div class="bg-success text-white px-4 py-3 rounded-4 shadow-sm text-center w-100 w-md-auto">
                <span class="text-uppercase small fw-bold d-block opacity-75" style="font-size: 0.75rem;">Chiffre d'Affaires</span>
                <span class="fs-3 fw-bold" id="affichage-ca"><?= number_format($chiffre_affaires, 2, ',', ' ') ?> €</span>
            </div>
        </div>

        <div class="card shadow border-0 rounded-4 p-3 p-md-4 mb-4 bg-light">
            <form method="GET" action="<?= BASE_URL ?>index.php" id="formFiltre" class="row g-3 align-items-end">
                <input type="hidden" name="page" value="admin-dashboard">
                <div class="col-sm-6 col-md-4">
                    <label class="form-label fw-bold small text-muted">Date de début</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control" value="<?= htmlspecialchars($date_debut) ?>">
                </div>
                <div class="col-sm-6 col-md-4">
                    <label class="form-label fw-bold small text-muted">Date de fin</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control" value="<?= htmlspecialchars($date_fin) ?>">
                </div>
                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100 rounded-pill fw-bold py-2">Filtrer</button>
                    <?php if(!empty($date_debut) || !empty($date_fin)): ?>
                        <a href="<?= BASE_URL ?>index.php?page=admin-dashboard#statsSection" class="btn btn-outline-secondary rounded-pill fw-bold py-2">Réinitialiser</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-12 col-md-10 mx-auto">
                <div class="card shadow border-0 rounded-4 p-3 p-md-4 text-center">
                    <h5 class="fw-bold text-dark mb-3 mb-md-4 fs-6">Volume des ventes par menu</h5>
                    <div style="position: relative; height:260px; width:100%">
                        <canvas id="chartMenus"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const labelsMenus = <?= json_encode($labels_graphique) ?>;
    const donneesVentes = <?= json_encode($donnees_graphique) ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= BASE_URL ?>styles/js/admin_dashboard.js"></script>