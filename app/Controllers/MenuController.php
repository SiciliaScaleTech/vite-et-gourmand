<?php

require_once __DIR__ . '/../Models/MenuModel.php';

class MenuController {
    private $menuModel;

    public function __construct() {
        $this->menuModel = new MenuModel();
    }

    
     // Gère la page du catalogue des menus
    
    public function index() {
        // Sauvegarde des filtres en session si présents dans l'URL
        if (!empty($_GET)) {
            $_SESSION['f_theme'] = $_GET['theme'] ?? '';
            $_SESSION['f_prix']  = $_GET['prix_max'] ?? '';
            $_SESSION['f_pers']  = $_GET['pers_min'] ?? '';
            $_SESSION['f_aller'] = $_GET['allergene'] ?? '';
        }

        $filters = [
            'theme'     => $_SESSION['f_theme'] ?? '',
            'prix_max'  => $_SESSION['f_prix'] ?? '',
            'pers_min'  => $_SESSION['f_pers'] ?? '',
            'allergene' => $_SESSION['f_aller'] ?? ''
        ];

        $menus = $this->menuModel->getAllMenus();

        $menus_details = $this->getStaticMenusDetails();

        require_once __DIR__ . '/../Views/pages/menus.php';
    }

    
    // Gère la page de détails d'un menu
    
    public function showDetails() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . 'index.php?page=menus');
            exit();
        }

        $menu = $this->menuModel->getMenuById($id);

        if (!$menu) {
            die("Le menu demandé n'existe pas.");
        }

        require_once __DIR__ . '/../Views/pages/details-menu.php';
    }

     
    private function getStaticMenusDetails() {
        return [
            'Noel' => [
                'titre' => 'Menu de Noël gourmet',
                'galerie' => ['assets/noel1-img-details.jpg', 'assets/noel2-img-detail.jpg', 'assets/noel3-img-detail.jpeg'],
                'plats' => ['Entrée: Foi gras', 'plat: Chapon aux marrons', 'Dessert: Bûche'],
                'prix_pers'   => 45,
                'pers_min'    => 6,
                'description' => 'Un festin traditionnel et chaleureux pour vos fêtes de fin d\'année.',
                'conditions'  => 'Nécessite de commander 10 jours avant le réveillon.',
                'allergene' => 'Gluten, Fruits à coque'
            ],
            'Paques' => [
                'titre' => 'Menu de Pâques',
                'galerie' => ['assets/paque1-img-detail.webp', 'assets/paque2-img-detail.jpg', 'assets/paque3-img-detail.webp'],
                'plats' => ['Entrée: Asperges', 'plat: Agneau pascale', 'Dessert: Gateau avec sa poule en chocolat'],
                'prix_pers'   => 38,
                'pers_min'    => 4,
                'description' => 'Célébrez le printemps avec des saveurs authentiques et de saison.',
                'conditions'  => 'Commande possible jusqu\'à 5 jours avant.',
                'allergene' => 'Lactose, oeufs'
            ],
            'Halloween' => [
                'titre' => 'Menu d\'halloween',
                'galerie' => ['assets/halloween1-img-detail.jpg', 'assets/halloween2-img-detail.jpg', 'assets/halloween3-img-detail.png'],
                'plats' => ['Entrée: velouté de courge', 'plat: citrouille farcis', 'Dessert: citrouille avec son coulis mystère'],
                'prix_pers'   => 40,
                'pers_min'    => 6,
                'description' => 'Célébrez halloween en famille ou entre amis avec des saveurs mystérieuse.',
                'conditions'  => 'Commande possible jusqu\'à 8 jours avant.',
                'allergene' => 'neant'
            ],
            'Classique' => [
                'titre' => 'Menu classique',
                'galerie' => ['assets/classique1-img-detail.jpeg', 'assets/classique2-img-detail.webp', 'assets/classique3-img-detail.webp'],
                'plats' => ['Entrée: salade et tomates cerises', 'plat: tranches de boeuf avec pomme de terre', 'Dessert: gateaux aux noix'],
                'prix_pers'   => 25,
                'pers_min'    => 2,
                'description' => 'Une repas équilibré qaund vous n\'avez pas eu le temps de cuisiner.',
                'conditions'  => 'Commande possible jusqu\'à 3 jours avant.',
                'allergene' => 'arachide, noix'
            ],
            'Mariage' => [
                'titre' => 'Menu de mariage',
                'galerie' => ['assets/mariage1-img-detail.png', 'assets/mariage2-img-detail.jpeg', 'assets/mariage3-img-detail.jpg'],
                'plats' => ['Entrée: jambon sec/crevette rose', 'plat: roulés au jambon, galette de légumes, rôti', 'Dessert: pièce montée'],
                'prix_pers'   => 60,
                'pers_min'    => 20,
                'description' => 'Une repas digne du plus beau jour de votre vie.',
                'conditions'  => 'Commande possible jusqu\'à 1 mois avant.',
                'allergene' => 'crustacés'
            ],
            'Bapteme' => [
                'titre' => 'Menu de bapteme',
                'galerie' => ['assets/bapteme1-img-detail.jpg', 'assets/bapteme2-img-detail.jpg', 'assets/bapteme3-img-detail.webp'],
                'plats' => ['Entrée: saumons sur toast', 'plat: velouté de tomate et roulés aux lard avec ses oeufs', 'Dessert: Cupcake'],
                'prix_pers'   => 30,
                'pers_min'    => 15,
                'description' => 'Un jour important pour vous et votre enfant, laissez nous préparer votre repas pour profiter pleinement de ce jour si particulier.',
                'conditions'  => 'Commande possible jusqu\'à 3 semaines avant.',
                'allergene' => 'oeufs, saumon'
            ]
        ];
    }
}