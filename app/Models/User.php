<?php
namespace App\Models;

class User {
    /**
     * Recherche un utilisateur actif par son email
     */
    public static function findByEmail($email) {
        $db = getDBConnection();
        if (!$db) return null; // Sécurité si la BDD est en panne

        $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE email = ? AND actif = 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Crée un nouvel utilisateur
     */
    public static function create($nom, $prenom, $email, $telephone, $adresse, $code_postal, $ville, $password_hashed) {
        $db = getDBConnection();
        if (!$db) return false;

        $stmt = $db->prepare("INSERT INTO utilisateurs (nom, prenom, email, telephone, adresse, code_postal, ville, mot_de_passe, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'utilisateur')");
        return $stmt->execute([$nom, $prenom, $email, $telephone, $adresse, $code_postal, $ville, $password_hashed]);
    }
}