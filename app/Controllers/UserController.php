<?php

class UserController {
    private $pdo;

    public function __construct() {
        if (isset($_SESSION) && !isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'index.php?page=connexion');
            exit();
        }
        $this->pdo = getDBConnection();
    }

    //PAGE PROFIL PRINCIPALE
    public function index() {
        $user_id = $_SESSION['user_id'];
        
        // Messages d'alerte
        $message = $_SESSION['profile_message'] ?? "";
        $message_avis = $_SESSION['avis_message'] ?? "";
        unset($_SESSION['profile_message'], $_SESSION['avis_message']);

        // Récupération des données utilisateur
        $stmt = $this->pdo->prepare("SELECT nom, prenom, email, telephone, adresse, code_postal, ville, role FROM utilisateurs WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupération des commandes
        $stmt_cmd = $this->pdo->prepare("SELECT * FROM commandes WHERE id_utilisateur = ? ORDER BY date_commande DESC");
        $stmt_cmd->execute([$user_id]);
        $commandes = $stmt_cmd->fetchAll(PDO::FETCH_ASSOC);

        $details_commandes = [];
        if (!empty($commandes)) {
            $stmt_details = $this->pdo->prepare("
                SELECT dc.id_commande, dc.quantite, dc.prix_unitaire, m.titre 
                FROM details_commandes dc
                JOIN menu m ON dc.id_menu = m.id
                WHERE dc.id_commande IN (SELECT id FROM commandes WHERE id_utilisateur = ?)
            ");
            $stmt_details->execute([$user_id]);
            while ($row = $stmt_details->fetch(PDO::FETCH_ASSOC)) {
                $details_commandes[$row['id_commande']][] = $row;
            }
        }

        require_once __DIR__ . '/../Views/pages/profil.php';
    }

    // TRAITEMENT MISE À JOUR DU PROFIL
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
            $user_id = $_SESSION['user_id'];
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telephone = trim($_POST['telephone'] ?? '');
            $adresse = trim($_POST['adresse'] ?? ''); 
            $code_postal = trim($_POST['code_postal'] ?? '');
            $ville = trim($_POST['ville'] ?? '');

            try {
                $stmt = $this->pdo->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ?, code_postal = ?, ville = ? WHERE id = ?");
                $stmt->execute([$nom, $prenom, $email, $telephone, $adresse, $code_postal, $ville, $user_id]);
                
                $_SESSION['user_prenom'] = $prenom;
                $_SESSION['profile_message'] = "<div class='alert alert-success rounded-pill border-0 text-center shadow-sm'>Informations mises à jour avec succès !</div>";
            } catch (PDOException $e) {
                $_SESSION['profile_message'] = "<div class='alert alert-danger rounded-pill border-0 text-center shadow-sm'>Erreur : L'adresse email est déjà utilisée.</div>";
            }
        }
        header("Location: " . BASE_URL . "index.php?page=profil");
        exit();
    }

    // TRAITEMENT ENVOI D'UN AVIS
    public function submitAvis() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_avis'])) {
            $user_id = $_SESSION['user_id'];
            $note = intval($_POST['note'] ?? 5);
            $commentaire = trim($_POST['commentaire'] ?? '');

            if (!empty($commentaire)) {
                try {
                    $stmt = $this->pdo->prepare("INSERT INTO avis (id_utilisateur, commentaire, note, statut, date_avis) 
                                                 VALUES (?, ?, ?, 'en attente', NOW())");
                    $stmt->execute([$user_id, $commentaire, $note]);
                    $_SESSION['avis_message'] = "<div class='alert alert-success fw-bold border-0 shadow-sm rounded-4'>Merci ! Votre avis a été envoyé. Il sera publié après validation.</div>";
                } catch (PDOException $e) {
                    $_SESSION['avis_message'] = "<div class='alert alert-danger border-0 shadow-sm'>Erreur lors de l'envoi de l'avis.</div>";
                }
            } else {
                $_SESSION['avis_message'] = "<div class='alert alert-warning border-0 shadow-sm'>Veuillez rédiger un commentaire.</div>";
            }
        }
        header("Location: " . BASE_URL . "index.php?page=profil");
        exit();
    }
}