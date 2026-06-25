<?php
/** @var array $menu */
/** @var int $menuId */
/** @var string $message */
/** @var string $messageClass */
?>
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="mb-4">
                <a href="<?= BASE_URL ?>index.php?page=employe-carte" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    ← Retour à la carte
                </a>
            </div>

            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-mimolette text-dark p-4">
                    <h2 class="h4 mb-1 fw-bold">Modifier le menu</h2>
                    <p class="mb-0 text-muted small">ID du menu : #<?= $menu['id'] ?> | Nom : <?= htmlspecialchars($menu['titre']) ?></p>
                </div>

                <div class="card-body p-4">
                    
                    <?php if (!empty($message)): ?>
                        <div class="alert <?= $messageClass ?> alert-dismissible fade show" role="alert">
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= BASE_URL ?>index.php?page=employe-modifier-menu&id=<?= $menuId ?>" class="row g-4">
                        
                        <div class="col-12">
                            <label class="form-label fw-bold text-secondary">Titre du menu</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($menu['titre']) ?>" disabled>
                        </div>

                        <div class="col-md-6">
                            <label for="prix_pers" class="form-label fw-bold">Prix par personne (€) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" name="prix_pers" id="prix_pers" class="form-control border-secondary-subtle shadow-sm" value="<?= htmlspecialchars($menu['prix_pers']) ?>" required>
                                <span class="input-group-text bg-light border-secondary-subtle">€</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="stock" class="form-label fw-bold">Quantité en stock <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="stock" id="stock" class="form-control border-secondary-subtle shadow-sm" value="<?= htmlspecialchars($menu['stock']) ?>" required>
                        </div>

                        <div class="col-12">
                            <label for="allergene" class="form-label fw-bold">Allergènes présents</label>
                            <input type="text" name="allergene" id="allergene" class="form-control border-secondary-subtle shadow-sm" placeholder="Ex: Gluten, Lactose, Fruits à coque (ou 'aucun')" value="<?= htmlspecialchars($menu['allergene']) ?>">
                            <div class="form-text">Séparez les allergènes par des virgules.</div>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-bold">Description du menu <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="5" class="form-control border-secondary-subtle shadow-sm" required><?= htmlspecialchars($menu['description']) ?></textarea>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2 mt-5">
                            <a href="<?= BASE_URL ?>index.php?page=employe-dashboard" class="btn btn-light rounded-pill px-4">Annuler</a>
                            <button type="submit" class="btn btn-cheddar rounded-pill px-5 fw-bold shadow-sm">
                                Enregistrer les modifications
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</main>