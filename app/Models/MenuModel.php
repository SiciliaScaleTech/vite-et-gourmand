<?php

require_once __DIR__ . '/../../config/config.php';

class MenuModel {
    private $db;

    public function __construct() {
        $this->db = getDBConnection();
    }

    /**
     * Récupère tous les menus de la base de données
     */
    public function getAllMenus() {
        if (!$this->db) return [];
        
        try {
            $query = $this->db->query("SELECT * FROM menu ORDER BY id ASC");
            return $query->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur MenuModel::getAllMenus : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère un menu spécifique par son ID
     */
    public function getMenuById($id) {
        if (!$this->db) return null;

        try {
            $stmt = $this->db->prepare("SELECT * FROM menu WHERE id = ?");
            $stmt->execute([(int)$id]);
            return $stmt->fetch() ?: null;
        } catch (PDOException $e) {
            error_log("Erreur MenuModel::getMenuById : " . $e->getMessage());
            return null;
        }
    }
}