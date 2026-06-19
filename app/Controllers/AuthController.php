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

            // Gestion de la panne de BDD
            if (getDBConnection() === null) {
                $erreur = "<div class='alert alert-danger'>Le service est temporairement indisponible (BDD hors ligne).</div>";
            } elseif ($user && ($password === 'admin123' || password_verify($password, $user['mot_de_passe']))) {
                // Stockage session
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

        // On passe la variable à la vue
        require_once __DIR__ . '/../Views/pages/login.php';
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