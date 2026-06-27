<?php

class HomeController {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    public function index() {
        // Récupérer les 5 derniers avis VALIDÉS uniquement
        $stmt = $this->pdo->prepare("
            SELECT a.*, u.prenom, u.nom 
            FROM avis a 
            JOIN utilisateurs u ON a.id_utilisateur = u.id 
            WHERE a.statut = 'valide' 
            ORDER BY a.date_avis DESC 
            LIMIT 5
        ");
        $stmt->execute();
        $avis_valides = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../Views/pages/home.php';
    }
}