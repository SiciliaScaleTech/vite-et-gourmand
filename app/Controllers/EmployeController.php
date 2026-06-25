<?php
namespace App\Controllers;

use PDO;
use PDOException;

class EmployeController {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'] ?? '', ['employe', 'admin'])) {
            header('Location: index.php?page=login');
            exit();
        }
    }

    public function dashboard() {
        global $pdo; 

        $couleurs_statut = [
            'reçue' => 'bg-secondary text-white',
            'accepté' => 'bg-info text-dark',
            'en préparation' => 'bg-warning text-dark',
            'en cours de livraison' => 'bg-primary text-white',
            'livré' => 'bg-success text-white',
            'en attente du retour de matériel' => 'bg-danger text-white fw-bold',
            'terminée' => 'bg-dark text-white',
            'annulée' => 'bg-light text-danger border border-danger'
        ];

        // Traitement de la suppression
        if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id_commande'])) {
            $id_a_supprimer = (int)$_GET['id_commande'];
            try {
                $pdo->beginTransaction();
                $deleteDetails = $pdo->prepare("DELETE FROM details_commandes WHERE id_commande = ?");
                $deleteDetails->execute([$id_a_supprimer]);
                
                $deleteCmd = $pdo->prepare("DELETE FROM commandes WHERE id = ?");
                $deleteCmd->execute([$id_a_supprimer]);
                
                $pdo->commit();
                header('Location: index.php?page=employe-dashboard&msg=deleted');
                exit();
            } catch (PDOException $e) {
                $pdo->rollBack();
                die("Erreur lors de la suppression : " . $e->getMessage());
            }
        }

        // Récupération de toutes les commandes
        try {
            $stmt = $pdo->query("SELECT c.*, u.nom, u.prenom 
                                 FROM commandes c 
                                 JOIN utilisateurs u ON c.id_utilisateur = u.id 
                                 ORDER BY c.date_commande DESC");
            $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des commandes : " . $e->getMessage());
        }

        require_once __DIR__ . '/../Views/employe/dashboard.php';
    }

    public function gererAvis() {
        global $pdo;
        $message = "";

        // TRAITEMENT DE LA VALIDATION OU DU REFUS
        if (isset($_GET['action']) && isset($_GET['id'])) {
            $avis_id = (int)$_GET['id'];
            $action = $_GET['action'];

            try {
                if ($action === 'accepter') {
                    $stmt = $pdo->prepare("UPDATE avis SET statut = 'valide' WHERE id = ?");
                    $stmt->execute([$avis_id]);
                    $message = "<div class='alert alert-success fw-bold'>✔️ L'avis a été validé et sera visible sur la page d'accueil !</div>";
                } elseif ($action === 'refuser') {
                    $stmt = $pdo->prepare("UPDATE avis SET statut = 'refuse' WHERE id = ?");
                    $stmt->execute([$avis_id]);
                    $message = "<div class='alert alert-danger fw-bold'> L'avis a été refusé et ne sera pas affiché.</div>";
                }
            } catch (PDOException $e) {
                $message = "<div class='alert alert-danger fw-bold'> Erreur lors de la modération : " . $e->getMessage() . "</div>";
            }
        }

        // RÉCUPÉRATION DES AVIS EN ATTENTE
        try {
            $stmt = $pdo->prepare("SELECT a.*, u.nom, u.prenom 
                                   FROM avis a 
                                   JOIN utilisateurs u ON a.id_utilisateur = u.id 
                                   WHERE a.statut = 'en attente' 
                                   ORDER BY a.date_avis DESC");
            $stmt->execute();
            $avis_en_attente = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur SQL lors de la récupération des avis : " . $e->getMessage());
        }

        require_once __DIR__ . '/../Views/employe/avis.php';
    }

    
        // Détails et modification de statut d'une commande
    
    public function detailsCommande() {
        global $pdo;

        $commande_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$commande_id) {
            header('Location: index.php?page=employe-dashboard');
            exit();
        }

        $message = "";
        $messageClass = "";

        // 1. TRAITEMENT DU FORMULAIRE 
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
            $nouveau_statut = $_POST['statut'];
            $mode_contact = trim($_POST['mode_contact'] ?? '');
            $motif_annulation = trim($_POST['motif_annulation'] ?? '');

            if ($nouveau_statut === 'annulée' && (empty($mode_contact) || empty($motif_annulation))) {
                $message = "⚠️ Erreur : Le mode de contact et le motif sont obligatoires pour une annulation.";
                $messageClass = "alert-danger";
            } else {
                try {
                    // Récupération de l'ancien statut avant modification
                    $checkOld = $pdo->prepare("SELECT statut FROM commandes WHERE id = ?");
                    $checkOld->execute([$commande_id]);
                    $old_status = $checkOld->fetchColumn();

                    $pdo->beginTransaction();

                    if ($nouveau_statut === 'annulée') {
                        $stmt = $pdo->prepare("UPDATE commandes SET statut = ?, mode_contact = ?, motif_annulation = ? WHERE id = ?");
                        $stmt->execute([$nouveau_statut, $mode_contact, $motif_annulation, $commande_id]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE commandes SET statut = ?, mode_contact = NULL, motif_annulation = NULL WHERE id = ?");
                        $stmt->execute([$nouveau_statut, $commande_id]);

                        // Gestion des stocks lors du passage à "accepté"
                        if ($nouveau_statut === 'accepté' && $old_status !== 'accepté') {
                            $itemsStmt = $pdo->prepare("SELECT id_menu, quantite FROM details_commandes WHERE id_commande = ?");
                            $itemsStmt->execute([$commande_id]);
                            $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

                            $updateStockStmt = $pdo->prepare("UPDATE menu SET stock = stock - ? WHERE id = ?");
                            foreach ($items as $item) {
                                $updateStockStmt->execute([$item['quantite'], $item['id_menu']]);
                            }
                        }
                    }

                    if ($nouveau_statut === 'en attente du retour de matériel' && $old_status !== 'en attente du retour de matériel') {
                        $clientStmt = $pdo->prepare("SELECT u.email, u.prenom, u.nom FROM commandes c JOIN utilisateurs u ON c.id_utilisateur = u.id WHERE c.id = ?");
                        $clientStmt->execute([$commande_id]);
                        $client = $clientStmt->fetch(PDO::FETCH_ASSOC);

                        if ($client) {
                            $to = $client['email'];
                            $message .= " Envoi du mail de relance matériel simulé avec succès à " . htmlspecialchars($to) . ".";
                        }
                    }

                    $pdo->commit();
                    $message = "Le statut de la commande a été mis à jour avec succès !" . $message;
                    $messageClass = "alert-success";
                } catch (PDOException $e) {
                    $pdo->rollBack();
                    $message = "Erreur BDD : " . $e->getMessage();
                    $messageClass = "alert-danger";
                }
            }
        }

        try {
            $stmt = $pdo->prepare("SELECT c.*, u.nom, u.prenom, u.email, u.telephone 
                                   FROM commandes c 
                                   JOIN utilisateurs u ON c.id_utilisateur = u.id 
                                   WHERE c.id = ?");
            $stmt->execute([$commande_id]);
            $commande = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$commande) {
                die("<div class='container py-5'><div class='alert alert-danger'>Commande introuvable.</div></div>");
            }

            // Récupération des détails avec le STOCK restant du menu
            $detailsStmt = $pdo->prepare("SELECT dc.*, m.titre, m.stock AS stock_restant 
                                          FROM details_commandes dc 
                                          JOIN menu m ON dc.id_menu = m.id 
                                          WHERE dc.id_commande = ?");
            $detailsStmt->execute([$commande_id]);
            $liste_plats = $detailsStmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur BDD : " . $e->getMessage());
        }

        require_once __DIR__ . '/../Views/employe/detail_commande.php';
    }
    /**
     * 5. GESTION DE LA CARTE (MENUS / PLATS)
     */
    public function carte() {
        global $pdo;
        $message = "";

        // Récupération d'un éventuel message flash de succès
        if (isset($_SESSION['flash_success'])) {
            $message = "<div class='alert alert-success fw-bold'>" . $_SESSION['flash_success'] . "</div>";
            unset($_SESSION['flash_success']);
        }

        // 1. TRAITEMENT DE LA SUPPRESSION D'UN MENU / PLAT
        if (isset($_GET['delete_id'])) {
            $delete_id = (int)$_GET['delete_id'];
            try {
                $stmt = $pdo->prepare("DELETE FROM menu WHERE id = ?"); 
                $stmt->execute([$delete_id]);
                $message = "<div class='alert alert-success fw-bold'> Le menu a été retiré de la carte avec succès !</div>";
            } catch (PDOException $e) {
                $message = "<div class='alert alert-danger fw-bold'> Impossible de supprimer ce menu. S'il est lié à des commandes passées, la base de données bloque la suppression.</div>";
            }
        }

        // 2. RÉCUPÉRATION DE TOUS LES MENUS
        try {
            $stmt = $pdo->prepare("SELECT * FROM menu ORDER BY categorie ASC, titre ASC"); 
            $stmt->execute();
            $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("<div class='alert alert-danger'>Erreur SQL de chargement : " . $e->getMessage() . "</div>");
        }

        // 3. CHARGEMENT DE LA VUE
        require_once __DIR__ . '/../Views/employe/carte.php';
    }

    /**
     * 6. MODIFICATION D'UN MENU / PLAT
     */
    public function modifierMenu() {
        global $pdo;
        $message = "";
        $messageClass = "";

        // 1. RÉCUPÉRATION DU MENU ACTUEL
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header('Location: index.php?page=employe-carte');
            exit();
        }

        $menuId = (int)$_GET['id'];

        try {
            $query = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
            $query->execute([$menuId]);
            $menu = $query->fetch(PDO::FETCH_ASSOC);

            if (!$menu) {
                die("<div class='container py-5'><div class='alert alert-danger'>Ce menu n'existe pas.</div></div>");
            }
        } catch (PDOException $e) {
            die("Erreur BDD : " . $e->getMessage());
        }

        // 2. TRAITEMENT DU FORMULAIRE LORS DE LA SOUMISSION (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $prix = floatval($_POST['prix_pers']);
            $stock = (int)$_POST['stock'];
            $description = trim($_POST['description']);
            $allergene = trim($_POST['allergene']);

            // Validation simple
            if ($prix <= 0 || $stock < 0 || empty($description)) {
                $message = "Veuillez remplir correctement tous les champs obligatoires (prix > 0 et stock >= 0).";
                $messageClass = "alert-danger";
            } else {
                try {
                    // Requête de mise à jour
                    $update = $pdo->prepare("UPDATE menu SET prix_pers = ?, stock = ?, description = ?, allergene = ? WHERE id = ?");
                    $result = $update->execute([$prix, $stock, $description, $allergene, $menuId]);

                    if ($result) {
                        $message = "Le menu a été mis à jour avec succès !";
                        $messageClass = "alert-success";
                        
                        // On rafraîchit les données locales pour l'affichage à jour dans la vue
                        $menu['prix_pers'] = $prix;
                        $menu['stock'] = $stock;
                        $menu['description'] = $description;
                        $menu['allergene'] = $allergene;
                    } else {
                        $message = "Une erreur est survenue lors de la mise à jour.";
                        $messageClass = "alert-danger";
                    }
                } catch (PDOException $e) {
                    $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
                    $messageClass = "alert-danger";
                }
            }
        }

        // 3. CHARGEMENT DE LA VUE
        require_once __DIR__ . '/../Views/employe/modifier_menu.php';
    }

    /**
     * 7. AJOUT D'UN NOUVEAU MENU / PLAT
     */
    public function ajouterMenu() {
        global $pdo;
        $message = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = trim($_POST['titre']);
            $nom_technique = trim($_POST['nom_technique']);
            $categorie = trim($_POST['categorie']);
            $description = trim($_POST['description']);
            $prix_pers = (float)$_POST['prix_pers'];
            $stock = (int)$_POST['stock'];
            $pers_min = (int)$_POST['pers_min'];
            $conditions = trim($_POST['conditions']);
            $allergene = trim($_POST['allergene']);

            // Composition des plats
            $entree  = trim($_POST['composition_entree']);
            $plat    = trim($_POST['composition_plat']);
            $dessert = trim($_POST['composition_dessert']);
            $plats_ordonnes = "Entrée: " . $entree . "|plat: " . $plat . "|Dessert: " . $dessert;

            // GESTION DE L'UPLOAD DE L'IMAGE
            $image_path = "assets/images/pizza-placeholder.jpg"; 

            if (isset($_FILES['galerie']) && $_FILES['galerie']['error'] === 0) {
                $dossier_destination = __DIR__ . "/../../../public/assets/images/";
                
                // On crée le dossier s'il n'existe pas
                if (!is_dir($dossier_destination)) {
                    mkdir($dossier_destination, 0777, true);
                }
                
                $extension = pathinfo($_FILES['galerie']['name'], PATHINFO_EXTENSION);
                $nom_fichier_unique = uniqid("menu_", true) . "." . $extension;
                $chemin_physique_final = $dossier_destination . $nom_fichier_unique;

                $extensions_autorisees = ['jpg', 'jpeg', 'png', 'webp'];
                $taille_max = 2 * 1024 * 1024; // 2 Mo

                if (!in_array(strtolower($extension), $extensions_autorisees)) {
                    $message = "<div class='alert alert-danger fw-bold'> Format d'image refusé. Utilisez du JPG, PNG ou WEBP.</div>";
                } elseif ($_FILES['galerie']['size'] > $taille_max) {
                    $message = "<div class='alert alert-danger fw-bold'> L'image est trop lourde (Maximum 2 Mo).</div>";
                } else {
                    if (move_uploaded_file($_FILES['galerie']['tmp_name'], $chemin_physique_final)) {
                        $image_path = "assets/images/" . $nom_fichier_unique;
                    } else {
                        $message = "<div class='alert alert-warning fw-bold'> Échec du transfert physique de l'image.</div>";
                    }
                }
            }

            // Si aucune erreur, on insère en BDD
            if (empty($message) && !empty($titre) && !empty($categorie)) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO menu (titre, nom_technique, categorie, description, plats, stock, prix_pers, pers_min, conditions, allergene, galerie) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    
                    $stmt->execute([
                        $titre, $nom_technique, $categorie, $description, $plats_ordonnes, $stock, $prix_pers, $pers_min, $conditions, $allergene, $image_path
                    ]);

                    // Redirection avec message flash
                    $_SESSION['flash_success'] = "Le menu « $titre » a été créé avec succès !";
                    header('Location: index.php?page=employe-carte');
                    exit();

                } catch (PDOException $e) {
                    $message = "<div class='alert alert-danger fw-bold'>Erreur BDD : " . $e->getMessage() . "</div>";
                }
            }
        }

        // CHARGEMENT DE LA VUE
        require_once __DIR__ . '/../Views/employe/ajouter_menu.php';
    }

   /**
     * 8. AFFICHAGE, SUPPRESSION ET ARCHIVAGE DES MESSAGES REÇUS
     */
    public function voirMessages() {
        global $pdo;
        $message = "";

        // 1. TRAITEMENT DE LA SUPPRESSION
        if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id_message'])) {
            $id_message = (int)$_GET['id_message'];
            try {
                $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
                $stmt->execute([$id_message]);
                $message = "<div class='alert alert-success fw-bold'>Le message a été supprimé définitivement.</div>";
            } catch (PDOException $e) {
                $message = "<div class='alert alert-danger fw-bold'>Erreur suppression : " . $e->getMessage() . "</div>";
            }
        }

        // 2. TRAITEMENT DE L'ARCHIVAGE (On change juste le statut pour le ranger)
        if (isset($_GET['action']) && $_GET['action'] === 'archiver' && isset($_GET['id_message'])) {
            $id_message = (int)$_GET['id_message'];
            try {
                $stmt = $pdo->prepare("UPDATE contact_messages SET statut = 'archive' WHERE id = ?");
                $stmt->execute([$id_message]);
                $message = "<div class='alert alert-info fw-bold'>Message déplacé dans les archives.</div>";
            } catch (PDOException $e) {
                $message = "<div class='alert alert-danger fw-bold'>Erreur archivage : " . $e->getMessage() . "</div>";
            }
        }

        // 3. RÉCUPÉRATION DES MESSAGES
        try {
            // Boîte de réception (Actifs)
            $stmtActifs = $pdo->query("SELECT * FROM contact_messages WHERE statut IS NULL OR statut != 'archive' ORDER BY date_envoi DESC");
            $messages = $stmtActifs->fetchAll(PDO::FETCH_ASSOC);

            // Archives
            $stmtArchives = $pdo->query("SELECT * FROM contact_messages WHERE statut = 'archive' ORDER BY date_envoi DESC");
            $messages_archives = $stmtArchives->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("<div class='alert alert-danger'>Erreur SQL de chargement : " . $e->getMessage() . "</div>");
        }

        // 4. CHARGEMENT DE LA VUE
        require_once __DIR__ . '/../Views/employe/messages.php';
    }
}

