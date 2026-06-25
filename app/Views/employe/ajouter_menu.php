<?php
/** @var string $message */
?>
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-4 gap-2">
                <a href="<?= BASE_URL ?>index.php?page=employe-carte" class="btn btn-outline-secondary rounded-pill btn-sm" style="font-size: 0.75rem;">⬅ Annuler</a>
                <h2 class="mb-0 fw-bold fs-4 fs-sm-2">Ajouter un nouveau menu</h2>
            </div>

            <?= $message ?>

            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>index.php?page=employe-ajouter-menu" method="POST" enctype="multipart/form-data">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nom / Titre du menu *</label>
                                <input type="text" name="titre" class="form-control rounded-3" placeholder="Ex: Menu Gourmet" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nom technique (Thème pour le filtre)</label>
                                <input type="text" name="nom_technique" class="form-control rounded-3" placeholder="Ex: Classique, Noel, Halloween..." required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Catégorie *</label>
                                <input type="text" name="categorie" class="form-control rounded-3" value="menu" placeholder="Ex: menu" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Image du menu / Plat *</label>
                                <input type="file" name="galerie" class="form-control rounded-3" accept="image/*" required>
                                <small class="text-muted">Formats acceptés : JPG, PNG, WEBP (Max 2 Mo)</small>
                            </div>

                            <hr class="my-3 text-muted">
                            <h5 class="text-primary fw-bold mb-2"> Composition du Menu</h5>
                            
                            <div class="col-12">
                                <label class="form-label fw-semibold">Entrée</label>
                                <input type="text" name="composition_entree" class="form-control rounded-3" placeholder="Ex: Saumons sur toast" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Plat principal</label>
                                <input type="text" name="composition_plat" class="form-control rounded-3" placeholder="Ex: Velouté de tomate" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Dessert</label>
                                <input type="text" name="composition_dessert" class="form-control rounded-3" placeholder="Ex: Cupcake" required>
                            </div>

                            <hr class="my-3 text-muted">

                            <div class="col-12">
                                <label class="form-label fw-bold">Description du menu</label>
                                <textarea name="description" class="form-control rounded-3" rows="3" placeholder="Ex: Un jour important pour vous..." required></textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Prix (€ / pers) *</label>
                                <input type="number" step="0.01" name="prix_pers" class="form-control rounded-3" placeholder="30" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Pers. minimum *</label>
                                <input type="number" name="pers_min" class="form-control rounded-3" placeholder="15" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Stock *</label>
                                <input type="number" name="stock" class="form-control rounded-3" placeholder="10" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Conditions de commande</label>
                                <input type="text" name="conditions" class="form-control rounded-3" placeholder="Ex: Commande possible jusqu'à 3 semaines avant." required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Allergènes</label>
                                <input type="text" name="allergene" class="form-control rounded-3" placeholder="Ex: oeufs, saumon ou neant" required>
                            </div>

                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-success btn-lg rounded-pill fw-bold px-5 shadow-sm btn-enregistrer">Enregistrer le menu</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</main>