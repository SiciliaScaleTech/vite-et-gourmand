<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Mot de passe oublié</h2>
                    <p class="text-muted text-center small mb-4">Entrez votre adresse email. Si elle correspond à un compte, nous vous afficherons le lien de réinitialisation.</p>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>

                    <form action="index.php?page=forgot-password" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse Email</label>
                            <input type="email" name="email" id="email" class="form-content form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Envoyer la demande</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="index.php?page=login" class="small">Retour à la connexion</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>