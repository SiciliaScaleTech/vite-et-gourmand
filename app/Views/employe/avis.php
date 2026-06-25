<main class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <div class="d-flex flex-column-reverse flex-md-row justify-content-between align-items-stretch align-items-md-center mb-4 gap-3">
                <a href="<?= BASE_URL ?>index.php?page=employe-dashboard" class="btn btn-outline-secondary rounded-pill btn-sm py-2 px-3 text-center" style="font-size: 0.85rem;">
                    ⬅ Retour au Tableau de bord
                </a>
                <h2 class="mb-0 fw-bold fs-3 fs-md-2 text-md-end">Modération des Avis Clients</h2>
            </div>

            <?= $message ?>

            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white fw-bold py-3">
                    Avis reçus en attente de modération (<?= count($avis_en_attente) ?>)
                </div>
                <div class="card-body p-0">
                    <?php if (empty($avis_en_attente)): ?>
                        <div class="text-center py-5 text-muted">
                            <p class="fs-4 mb-1">Tout est propre !</p>
                            <p class="mb-0">Il n'y a aucun avis en attente de validation.</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($avis_en_attente as $av): ?>
                                <div class="list-group-item p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="mb-1 fw-bold"><?= htmlspecialchars($av['prenom'] . ' ' . $av['nom']) ?></h5>
                                            <small class="text-muted">Posté le <?= date('d/m/Y à H:i', strtotime($av['date_avis'])) ?></small>
                                        </div>
                                        <div class="text-warning fs-5">
                                            <?= str_repeat('★', $av['note']) ?><?= str_repeat('☆', 5 - $av['note']) ?>
                                        </div>
                                    </div>
                                    
                                    <p class="mb-3 text-dark bg-light p-3 rounded custom-italic">
                                        " <?= htmlspecialchars($av['commentaire']) ?> "
                                    </p>

                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="<?= BASE_URL ?>index.php?page=employe-avis&action=refuser&id=<?= $av['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Refuser cet avis ?');">
                                            Refuser
                                        </a>
                                        <a href="<?= BASE_URL ?>index.php?page=employe-avis&action=accepter&id=<?= $av['id'] ?>" class="btn btn-sm btn-success rounded-pill px-3">
                                            Valider pour l'accueil
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</main>