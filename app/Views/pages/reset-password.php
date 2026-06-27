<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Nouveau mot de passe</h2>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <form action="index.php?page=reset-password&token=<?= htmlspecialchars($_GET['token'] ?? '') ?>" method="POST">
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="password" id="password" class="form-control" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Confirmez le mot de passe</label>
                            <input type="password" name="password_confirm" id="password_confirm" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Mettre à jour le mot de passe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>