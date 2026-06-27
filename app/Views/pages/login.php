<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-body p-5 text-center">
                    <h2 class="mb-4">Connexion</h2>
                    <?= $erreur ?? '' ?>
                    <form method="POST">
                        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                        <input type="password" name="password" class="form-control mb-4" placeholder="Mot de passe" required>
                        <div class="mb-3 text-end">
                        <a href="index.php?page=forgot-password" class="text-muted small">Mot de passe oublié ?</a>
                    </div>
                        <button type="submit" class="btn btn-dark w-100 rounded-pill mb-3">Se connecter</button>
                    </form>
                    
                    <p class="small text-muted">Pas encore de compte ? <a href="index.php?page=register">Inscrivez-vous</a></p>
                </div>
            </div>
        </div>
    </div>
</main>