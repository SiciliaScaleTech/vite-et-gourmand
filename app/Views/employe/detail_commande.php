<main class="container py-5">
    <a href="<?= BASE_URL ?>index.php?page=employe-dashboard" class="btn btn-outline-secondary rounded-pill mb-4">⬅️ Retour au Tableau de Bord</a>
    
    <?php if (!empty($message)): ?>
        <div class="alert <?= $messageClass ?> alert-dismissible fade show fw-bold" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow border-0 rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-dark text-white p-4 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-0 fw-bold">Détails de la Commande #<?= $commande['id'] ?></h3>
                <small class="text-light-50">Client : <?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?></small>
            </div>
            <span class="badge bg-warning text-dark text-uppercase px-3 py-2 fw-bold"><?= htmlspecialchars($commande['statut']) ?></span>
        </div>
        
        <div class="card-body p-4">
            <h5 class="fw-bold text-secondary mb-3">Articles commandés & Stocks</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Menu</th>
                            <th class="text-center">Quantité Commandée</th>
                            <th class="text-center">Stock Actuel Restant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($liste_plats as $plat): ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars($plat['titre']) ?></td>
                                <td class="text-center fs-5 fw-bold text-primary">x<?= htmlspecialchars($plat['quantite']) ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $plat['stock_restant'] > 5 ? 'bg-success' : 'bg-danger' ?> fs-6">
                                        <?= htmlspecialchars($plat['stock_restant']) ?> en stock
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="bg-light p-3 rounded-3 d-flex justify-content-between align-items-center mb-4 border border-2">
                <span class="fs-5 fw-bold text-dark">Montant Total à régler :</span>
                <span class="fs-3 fw-bold text-success"><?= number_format($commande['total'], 2, ',', ' ') ?> €</span>
            </div>

            <hr class="my-4">

            <h5 class="fw-bold text-secondary mb-3">Mettre à jour le Statut de la Commande</h5>
            <form method="POST" class="row g-3">
                
                <div class="col-12">
                    <label class="form-label fw-bold">Sélectionnez le nouveau statut :</label>
                    <select name="statut" id="statutSelect" class="form-select form-select-lg border-2" onchange="toggleAnnulationBlock()">
                        <option value="reçue" <?= $commande['statut'] === 'reçue' ? 'selected' : '' ?>>Reçue (En attente)</option>
                        <option value="accepté" <?= $commande['statut'] === 'accepté' ? 'selected' : '' ?>>Accepté (Stocks déduits)</option>
                        <option value="en préparation" <?= $commande['statut'] === 'en préparation' ? 'selected' : '' ?>>En préparation</option>
                        <option value="en cours de livraison" <?= $commande['statut'] === 'en cours de livraison' ? 'selected' : '' ?>>En cours de livraison (Julie)</option>
                        <option value="livré" <?= $commande['statut'] === 'livré' ? 'selected' : '' ?>>Livré</option>
                        <option value="en attente du retour de matériel" <?= $commande['statut'] === 'en attente du retour de matériel' ? 'selected' : '' ?>>Attente matériel (Frais 600€)</option>
                        <option value="terminée" <?= $commande['statut'] === 'terminée' ? 'selected' : '' ?>>Terminée</option>
                        <option value="annulée" <?= $commande['statut'] === 'annulée' ? 'selected' : '' ?>>Annulée</option>
                    </select>
                </div>

                <div id="blocAnnulation" class="col-12 d-none">
                    <div class="card border-danger bg-light-danger p-3 rounded-3">
                        <h6 class="text-danger fw-bold mb-2">Informations d'annulation requises :</h6>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Mode de contact :</label>
                                <select name="mode_contact" class="form-select border-danger">
                                    <option value="">-- Choisir --</option>
                                    <option value="Appel GSM" <?= ($commande['mode_contact'] ?? '') === 'Appel GSM' ? 'selected' : '' ?>>Appel GSM</option>
                                    <option value="Email direct" <?= ($commande['mode_contact'] ?? '') === 'Email direct' ? 'selected' : '' ?>>Email direct</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small fw-bold">Motif de l'annulation :</label>
                                <textarea name="motif_annulation" class="form-control border-danger" rows="2" placeholder="Expliquez pourquoi la commande est annulée..."><?= htmlspecialchars($commande['motif_annulation'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" name="update_status" class="btn btn-dark btn-lg w-100 rounded-pill fw-bold fs-5 shadow-sm">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>

        </div>
    </div>
</main>

<script>
function toggleAnnulationBlock() {
    const select = document.getElementById('statutSelect');
    const bloc = document.getElementById('blocAnnulation');
    
    if (select.value === 'annulée') {
        bloc.classList.remove('d-none');
    } else {
        bloc.classList.add('d-none');
    }
}

document.addEventListener("DOMContentLoaded", toggleAnnulationBlock);
</script>