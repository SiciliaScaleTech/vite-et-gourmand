<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Créer un compte</h2>
                    <?= $message ?? '' ?>
                    <form method="POST">
                        <h5 class="text-muted mb-3">Identité</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Prénom</label><input type="text" name="prenom" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Nom</label><input type="text" name="nom" class="form-control" required></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Téléphone</label><input type="tel" name="telephone" class="form-control" placeholder="0612345678" required></div>
                        </div>
                        <hr class="my-4">
                        <h5 class="text-muted mb-3">Coordonnées de livraison</h5>
                        <div class="mb-3"><label class="form-label">Adresse (Numéro et rue)</label><input type="text" name="adresse" class="form-control" required></div>
                        <div class="row">
                            <div class="col-md-4 mb-3"><label class="form-label">Code Postal</label><input type="text" name="code_postal" class="form-control" required></div>
                            <div class="col-md-8 mb-3"><label class="form-label">Ville</label><input type="text" name="ville" class="form-control" required></div>
                        </div>
                        <hr class="my-4">
                        <h5 class="text-muted mb-3">Sécurité</h5>
                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 rounded-pill mt-3">S'inscrire</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>