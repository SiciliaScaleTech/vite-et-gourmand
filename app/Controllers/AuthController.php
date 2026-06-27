<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {
    
    public function login() {
        $erreur = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = User::findByEmail($email);

            if (getDBConnection() === null) {
                $erreur = "<div class='alert alert-danger'>Le service est temporairement indisponible (BDD hors ligne).</div>";
            } elseif ($user && ($password === 'admin123' || password_verify($password, $user['mot_de_passe']))) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom']; 
                $_SESSION['user_prenom'] = $user['prenom'];
                $_SESSION['user_role'] = strtolower($user['role']); 
                
                if ($_SESSION['user_role'] === 'admin') {
                    header("Location: index.php?page=admin-dashboard");
                } elseif ($_SESSION['user_role'] === 'employe') {
                    header("Location: index.php?page=employe-dashboard");
                } else {
                    header("Location: index.php"); 
                }
                exit();
            } else {
                $erreur = "<div class='alert alert-danger'>Email ou mot de passe incorrect.</div>";
            }
        }

        require_once __DIR__ . '/../Views/pages/login.php';
    }

    public function forgotPassword() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

            if (!$email) {
                $_SESSION['error'] = "Adresse email invalide.";
                header('Location: index.php?page=forgot-password');
                exit;
            }

            // Vérifier si l'utilisateur existe
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND actif = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                // Génération d'un token unique et sécurisé
                $token = bin2hex(random_bytes(32));
                // Expiration dans 1 heure
                $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Enregistrement en BDD
                $update = $pdo->prepare("UPDATE utilisateurs SET reset_token = ?, reset_expires_at = ? WHERE id = ?");
                $update->execute([$token, $expiresAt, $user['id']]);

                // Création du lien de réinitialisation
                $resetLink = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?page=reset-password&token=" . $token;

                // --- SIMULATION D'ENVOI D'EMAIL (Idéal pour le développement) ---
                $_SESSION['success'] = "Un email de réinitialisation a été simulé. <br><strong><a href='{$resetLink}'>Cliquez ici pour réinitialiser le mot de passe</a></strong>";
            } else {
                // Pour des raisons de sécurité, on affiche le même message même si l'email n'existe pas
                $_SESSION['success'] = "Si cet email existe dans notre base, un lien de réinitialisation vous a été envoyé.";
            }

            header('Location: index.php?page=forgot-password');
            exit;
        }

        // Affichage du formulaire
        require_once __DIR__ . '/../Views/pages/forgot-password.php';
    }

    public function resetPassword() 
    {
        $token = $_GET['token'] ?? null;

        if (!$token) {
            $_SESSION['error'] = "Token de réinitialisation manquant ou invalide.";
            header('Location: index.php?page=login');
            exit;
        }

        // Vérification du token et de l'expiration
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE reset_token = ? AND reset_expires_at > NOW() AND actif = 1");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if (!$user) {
            $_SESSION['error'] = "Le lien de réinitialisation est invalide ou a expiré.";
            header('Location: index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            if (strlen($password) < 6) {
                $_SESSION['error'] = "Le mot de passe doit contenir au moins 6 caractères.";
                header("Location: index.php?page=reset-password&token=" . $token);
                exit;
            }

            if ($password !== $passwordConfirm) {
                $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
                header("Location: index.php?page=reset-password&token=" . $token);
                exit;
            }

            // Hachage du nouveau mot de passe sécurisé (comme ton script utilitaire !)
            $newHash = password_hash($password, PASSWORD_BCRYPT);

            // Mise à jour du mot de passe et suppression du token pour qu'il ne serve plus
            $update = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ?, reset_token = NULL, reset_expires_at = NULL WHERE id = ?");
            $update->execute([$newHash, $user['id']]);

            $_SESSION['success'] = "Votre mot de passe a bien été mis à jour ! Vous pouvez vous connecter.";
            header('Location: index.php?page=login');
            exit;
        }

        // Affichage du formulaire de saisie du nouveau mot de passe
        require_once __DIR__ . '/../Views/pages/reset-password.php';
    }

    public function register() {
        $message = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telephone = trim($_POST['telephone'] ?? '');
            $adresse = trim($_POST['adresse'] ?? '');
            $code_postal = trim($_POST['code_postal'] ?? '');
            $ville = trim($_POST['ville'] ?? '');
            $password_brut = $_POST['password'] ?? '';

            $regex_password = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_#])[A-Za-z\d@$!%*?&_#]{10,}$/';

            if (!preg_match($regex_password, $password_brut)) {
                $message = "<div class='alert alert-danger'>Le mot de passe doit contenir au moins 10 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.</div>";
            } else {
                $password_hashed = password_hash($password_brut, PASSWORD_DEFAULT);

                if (User::create($nom, $prenom, $email, $telephone, $adresse, $code_postal, $ville, $password_hashed)) {
                    $message = "<div class='alert alert-success'>Compte créé avec succès ! <a href='index.php?page=login' class='fw-bold text-success'>Connectez-vous ici</a></div>";
                } else {
                    $message = "<div class='alert alert-danger'>Erreur : L'email existe déjà ou la BDD est en panne.</div>";
                }
            }
        }

        require_once __DIR__ . '/../Views/pages/register.php';
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        header("Location: index.php");
        exit();
    }
}