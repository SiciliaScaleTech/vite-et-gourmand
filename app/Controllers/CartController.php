<?php


class CartController {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    // --- AFFICHER LE PANIER ---
    public function index() {
        $panier_details = [];
        $total_general = 0;

        if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
            $ids = array_keys($_SESSION['panier']);
            $comma_separated_ids = implode(',', array_fill(0, count($ids), '?'));

            $stmt = $this->pdo->prepare("SELECT id, titre, prix_pers, galerie FROM menu WHERE id IN ($comma_separated_ids)");
            $stmt->execute($ids);
            $menus_bdd = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($menus_bdd as $menu) {
                $quantite = $_SESSION['panier'][$menu['id']];
                $sous_total = $menu['prix_pers'] * $quantite;
                $total_general += $sous_total;

                // Gestion de l'image
                $galerie_nettoyee = str_replace('assets/', '', $menu['galerie']);
                $images_tableau = explode('|', $galerie_nettoyee);
                $premiere_image = $images_tableau[0];

                $panier_details[] = [
                    'id' => $menu['id'],
                    'titre' => $menu['titre'],
                    'prix' => $menu['prix_pers'],
                    'img' => $premiere_image,
                    'qte' => $quantite,
                    'sous_total' => $sous_total
                ];
            }
        }

        require_once __DIR__ . '/../Views/pages/panier.php';
    }

    // --- AJOUTER UN ELEMENT AU PANIER ---
    public function add() {
        $id = $_GET['id'] ?? null;

        if ($id) {
            if (!isset($_SESSION['panier'])) {
                $_SESSION['panier'] = [];
            }

            if (isset($_SESSION['panier'][$id])) {
                $_SESSION['panier'][$id]++;
            } else {
                $_SESSION['panier'][$id] = 1;
            }
        }
        header("Location: " . BASE_URL . "index.php?page=menus&statut=ajoute");
        exit();
    }

    public function update() {
        $id_modif = $_GET['id'] ?? null;
        $action = $_GET['action'] ?? null;

        if ($id_modif && isset($_SESSION['panier'][$id_modif])) {
            if ($action === 'augmenter') {
                $_SESSION['panier'][$id_modif]++;
            } elseif ($action === 'diminuer') {
                $_SESSION['panier'][$id_modif]--;
                if ($_SESSION['panier'][$id_modif] <= 0) {
                    unset($_SESSION['panier'][$id_modif]);
                }
            }
        }
        header("Location: " . BASE_URL . "index.php?page=panier");
        exit();
    }

    // --- SUPPRIMER ENTIÈREMENT UN ÉLÉMENT ---
    public function delete() {
        $id_a_supprimer = $_GET['id'] ?? null;

        if ($id_a_supprimer && isset($_SESSION['panier'][$id_a_supprimer])) {
            unset($_SESSION['panier'][$id_a_supprimer]);
        }
        header("Location: " . BASE_URL . "index.php?page=panier");
        exit();
    }

    // --- VALIDER LA COMMANDE ---
    public function validate() {
        // Sécurité : Si pas connecté ou panier vide
        if (!isset($_SESSION['user_id']) || empty($_SESSION['panier'])) {
            header("Location: " . BASE_URL . "index.php?page=login");
            exit();
        }

        try {
            $this->pdo->beginTransaction();

            $total_commande = 0;
            foreach ($_SESSION['panier'] as $id_menu => $quantite) {
                $stmt = $this->pdo->prepare("SELECT prix_pers FROM menu WHERE id = ?");
                $stmt->execute([$id_menu]);
                $menu = $stmt->fetch(PDO::FETCH_ASSOC);
                $total_commande += $menu['prix_pers'] * $quantite;
            }

            $stmt = $this->pdo->prepare("INSERT INTO commandes (id_utilisateur, total) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $total_commande]);
            $id_commande = $this->pdo->lastInsertId();

            foreach ($_SESSION['panier'] as $id_menu => $quantite) {
                $stmt = $this->pdo->prepare("SELECT prix_pers FROM menu WHERE id = ?");
                $stmt->execute([$id_menu]);
                $menu = $stmt->fetch(PDO::FETCH_ASSOC);

                $stmt = $this->pdo->prepare("INSERT INTO details_commandes (id_commande, id_menu, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
                $stmt->execute([$id_commande, $id_menu, $quantite, $menu['prix_pers']]);
            }

            $this->pdo->commit();
            unset($_SESSION['panier']);

            header("Location: " . BASE_URL . "index.php?page=confirmation&id=" . $id_commande);
            exit();

        } catch (Exception $e) {
            $this->pdo->rollBack();
            die("Erreur lors de la commande : " . $e->getMessage());
        }
    }

    // --- PAGE DE CONFIRMATION ---
    public function confirmation() {
        $id_commande = $_GET['id'] ?? 'inconnue';
        require_once __DIR__ . '/../Views/pages/confirmation.php';
    }
}