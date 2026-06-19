<main class="container py-5">
    <h1 class="mb-4">Mon Panier 🛒</h1>

    <?php if (empty($panier_details)): ?>
        <div class="alert alert-cheddar shadow-sm border-0">
            Votre panier est vide. <a href="<?= BASE_URL ?>index.php?page=menus" class="fw-bold text-dark">Découvrir nos menus</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Menu</th>
                        <th>Prix Unitaire</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($panier_details as $item): ?>
                    <tr>
                        <td data-label="Menu">
                            <div class="d-flex align-items-center justify-content-md-start justify-content-end">
                                <img src="<?= BASE_URL ?>assets/<?= $item['img'] ?>" alt="<?= htmlspecialchars($item['titre']) ?>" style="width: 50px; height: 50px; object-fit: cover;" class="rounded me-3">
                                <span class="fw-bold"><?= $item['titre'] ?></span>
                            </div>
                        </td>
                        
                        <td data-label="Prix Unitaire"><?= number_format($item['prix'], 2) ?> €</td>
                        
                        <td data-label="Quantité">
                            <div class="d-flex align-items-center justify-content-md-start justify-content-end gap-3">
                                <a href="<?= BASE_URL ?>index.php?page=panier-update&id=<?= $item['id'] ?>&action=diminuer" 
                                class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center" 
                                style="width: 28px; height: 28px; text-decoration: none;">
                                -
                                </a>

                                <span class="fw-bold fs-5" style="min-width: 20px; text-align: center;">
                                    <?= $item['qte'] ?>
                                </span>

                                <a href="<?= BASE_URL ?>index.php?page=panier-update&id=<?= $item['id'] ?>&action=augmenter" 
                                class="btn btn-sm btn-outline-success rounded-circle d-flex align-items-center justify-content-center" 
                                style="width: 28px; height: 28px; text-decoration: none;">
                                +
                                </a>
                            </div>
                        </td>
                        
                        <td data-label="Sous-total" class="fw-bold"><?= number_format($item['sous_total'], 2) ?> €</td>
                        
                        <td data-label="Action">
                            <a href="<?= BASE_URL ?>index.php?page=panier-delete&id=<?= $item['id'] ?>" class="text-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce menu ?')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <td colspan="3" class="text-end fw-bold fs-5 d-none d-md-table-cell">Total Général :</td>
                        <td colspan="2" class="text-primary fw-bold fs-5 text-end"><?= number_format($total_general, 2) ?> €</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="alert alert-info d-inline-block mt-2 border-0 shadow-sm">
            <i class="bi bi-info-circle-fill me-2"></i> 
            <strong>Paiement sécurisé :</strong> Le règlement s'effectue directement lors du retrait de votre commande.
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="<?= BASE_URL ?>index.php?page=menus" class="btn btn-outline-dark rounded-pill">Continuer mes achats</a>
            <a href="<?= BASE_URL ?>index.php?page=panier-validate" class="btn btn-cheddar rounded-pill px-5 fw-bold">Valider la commande</a>
        </div>
    <?php endif; ?>
</main>