<?php
namespace App\Controllers;

use PDO;
use PDOException;
use Exception;

class AdminController {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: index.php?page=login');
            exit();
        }
    }

    
      // TABLEAU DE BORD PRINCIPAL ADMIN
     
    public function dashboard() {
        $pdo = getDBConnection();
        $managerMongoDB = getMongoConnection();
        $collectionMenus = MONGO_COLLECTION_MENUS;

        if (!$pdo || !$managerMongoDB) {
            die("<div class='alert alert-danger'>Erreur : Impossible de se connecter aux bases de données.</div>");
        }

        $message = "";
        $messageClass = "";

        // AJOUTER UN EMPLOYÉ
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['creer_employe'])) {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $nom = trim($_POST['nom'] ?? 'Nom'); 
            $prenom = trim($_POST['prenom'] ?? 'Employé');

            if (empty($email) || empty($password)) {
                $message = "L'email et le mot de passe sont obligatoires.";
                $messageClass = "alert-danger";
            } else {
                try {
                    $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
                    $check->execute([$email]);
                    
                    if ($check->rowCount() > 0) {
                        $message = "Cet email est déjà utilisé par un autre compte.";
                        $messageClass = "alert-danger";
                    } else {
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, actif) VALUES (?, ?, ?, ?, 'employe', 1)");
                        $stmt->execute([$nom, $prenom, $email, $passwordHash]);

                        $message = "🎉 Compte employé créé avec succès !";
                        $messageClass = "alert-success";
                    }
                } catch (PDOException $e) {
                    $message = "Erreur lors de la création : " . $e->getMessage();
                    $messageClass = "alert-danger";
                }
            }
        }

        // ACTIVER / DÉSACTIVER UN COMPTE EMPLOYÉ
        if (isset($_GET['action']) && isset($_GET['id_user'])) {
            $id_user = (int)$_GET['id_user'];
            $action = $_GET['action'];
            
            if ($id_user === (int)$_SESSION['user_id']) {
                header('Location: index.php?page=admin-dashboard&msg=self_error');
                exit();
            }

            $nouvel_etat = ($action === 'desactiver') ? 0 : 1;
            
            try {
                $stmt = $pdo->prepare("UPDATE utilisateurs SET actif = ? WHERE id = ? AND role = 'employe'");
                $stmt->execute([$nouvel_etat, $id_user]);
                header('Location: index.php?page=admin-dashboard&msg=status_updated');
                exit();
            } catch (PDOException $e) {
                die("Erreur de mise à jour : " . $e->getMessage());
            }
        }

        // RÉCUPÉRATION DE LA LISTE DES EMPLOYÉS
        try {
            $stmt = $pdo->query("SELECT id, nom, prenom, email, actif FROM utilisateurs WHERE role = 'employe' ORDER BY nom ASC");
            $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur BDD : " . $e->getMessage());
        }

        // CHARGEMENT INITIAL DES DONNÉES DEPUIS MONGODB
        $chiffre_affaires = 0;
        $labels_graphique = [];
        $donnees_graphique = [];

        $date_debut = $_GET['date_debut'] ?? '';
        $date_fin = $_GET['date_fin'] ?? '';

        try {
            $query = new \MongoDB\Driver\Query([]);
            $cursor = $managerMongoDB->executeQuery($collectionMenus, $query);

            foreach ($cursor as $menu) {
                $menuData = (array)$menu;
                $nom_menu = $menuData['titre'] ?? '';
                $prix_unitaire = (float)($menuData['prix_pers'] ?? 0);
                $ventes_menu_periode = 0;

                if (isset($menuData['stats'])) {
                    $stats = (array)$menuData['stats'];
                    if (isset($stats['dernieres_commandes'])) {
                        foreach ((array)$stats['dernieres_commandes'] as $commande) {
                            $cmdData = (array)$commande;
                            
                            $date_commande = date('Y-m-d', strtotime($cmdData['date'] ?? ''));
                            $quantite = (int)($cmdData['quantite'] ?? 1);

                            // Filtrage par date
                            if (!empty($date_debut) && $date_commande < $date_debut) continue;
                            if (!empty($date_fin) && $date_commande > $date_fin) continue;

                            $ventes_menu_periode += $quantite;
                            $chiffre_affaires += ($prix_unitaire * $quantite);
                        }
                    }
                }

                if ($ventes_menu_periode > 0) {
                    $labels_graphique[] = $nom_menu;
                    $donnees_graphique[] = $ventes_menu_periode;
                }
            }
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            echo "<div class='alert alert-danger'>Erreur Atlas : " . htmlspecialchars($e->getMessage()) . "</div>";
        }

        require_once __DIR__ . '/../Views/admin/dashboard.php';
    }

    
    // PASSERELLE ASYNC POUR LE FILTRAGE JS / CHART.JS
     
    public function apiFiltreStats() {
        header('Content-Type: application/json');

        $pdo = getDBConnection();
        $managerMongoDB = getMongoConnection();
        $collectionMenus = MONGO_COLLECTION_MENUS;

        if (!$pdo || !$managerMongoDB) {
            echo json_encode(['status' => 'error', 'message' => 'Connexion BDD échouée']);
            exit();
        }

        $data_input = json_decode(file_get_contents('php://input'), true);
        $date_debut = $data_input['date_debut'] ?? '';
        $date_fin = $data_input['date_fin'] ?? '';

        try {
            $sql = "
                SELECT 
                    c.id as commande_id, c.date_commande, d.quantite, 
                    m.nom_technique, m.titre, m.prix_pers
                FROM details_commandes d
                INNER JOIN commandes c ON d.id_commande = c.id
                INNER JOIN menu m ON d.id_menu = m.id
                WHERE c.statut = 'terminée'
            ";
            
            $query_ventes = $pdo->query($sql);
            $ventes_reelles = $query_ventes->fetchAll(PDO::FETCH_ASSOC);

            // Injection vers MongoDB Atlas
            if (!empty($ventes_reelles)) {
                foreach ($ventes_reelles as $vente) {
                    $bulk = new \MongoDB\Driver\BulkWrite;
                    
                    $donnees_initiales = [
                        'code' => $vente['nom_technique'],
                        'titre' => $vente['titre'],
                        'prix_pers' => (float)$vente['prix_pers']
                    ];

                    $bulk->update(
                        ['code' => $vente['nom_technique']],
                        [
                            '$setOnInsert' => $donnees_initiales,
                            '$push' => [
                                'stats.dernieres_commandes' => [
                                    'date' => date('Y-m-d H:i:s', strtotime($vente['date_commande'])), 
                                    'quantite' => (int)$vente['quantite']
                                ]
                            ]
                        ],
                        ['multi' => false, 'upsert' => true]
                    );
                    $managerMongoDB->executeBulkWrite($collectionMenus, $bulk);

                    // Archivage local MySQL
                    $update_sql = $pdo->prepare("UPDATE commandes SET statut = 'Archivée' WHERE id = ?");
                    $update_sql->execute([$vente['commande_id']]);
                }
            }

            $labels_graphique = [];
            $donnees_graphique = [];
            $chiffre_affaires = 0;

            $query_mongo = new \MongoDB\Driver\Query([]);
            $cursor = $managerMongoDB->executeQuery($collectionMenus, $query_mongo);

            foreach ($cursor as $menu) {
                $menuData = (array)$menu;
                $nom_menu = $menuData['titre'] ?? '';
                $prix_unitaire = (float)($menuData['prix_pers'] ?? 0);
                $ventes_menu_periode = 0;

                if (isset($menuData['stats'])) {
                    $stats = (array)$menuData['stats'];
                    if (isset($stats['dernieres_commandes'])) {
                        foreach ((array)$stats['dernieres_commandes'] as $commande) {
                            $cmdData = (array)$commande;
                            
                            $date_commande = date('Y-m-d', strtotime($cmdData['date'] ?? ''));
                            $quantite = (int)($cmdData['quantite'] ?? 1);

                            if (!empty($date_debut) && $date_commande < $date_debut) continue;
                            if (!empty($date_fin) && $date_commande > $date_fin) continue;

                            $ventes_menu_periode += $quantite;
                            $chiffre_affaires += ($prix_unitaire * $quantite);
                        }
                    }
                }

                if ($ventes_menu_periode > 0) {
                    $labels_graphique[] = $nom_menu;
                    $donnees_graphique[] = $ventes_menu_periode;
                }
            }

            echo json_encode([
                'status' => 'success',
                'labels' => $labels_graphique,
                'donnees' => $donnees_graphique,
                'ca' => number_format($chiffre_affaires, 2, ',', ' ') . ' €'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit();
    }
}