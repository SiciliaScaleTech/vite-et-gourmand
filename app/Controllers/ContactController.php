<?php
// app/Controllers/ContactController.php

class ContactController {
    private $pdo;

    public function __construct() {
        // Initialisation propre de la BDD
        $this->pdo = getDBConnection();
    }

    // --- AFFICHER LA PAGE DE CONTACT ---
    public function index() {
        require_once __DIR__ . '/../Views/pages/contact.php';
    }

    // --- TRAITER LE FORMULAIRE ---
    public function process() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // Nettoyage des données
            $nom     = htmlspecialchars(trim($_POST['nom'] ?? ''), ENT_QUOTES, 'UTF-8');
            $prenom  = htmlspecialchars(trim($_POST['prenom'] ?? ''), ENT_QUOTES, 'UTF-8');
            $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $sujet   = htmlspecialchars(trim($_POST['sujet'] ?? ''), ENT_QUOTES, 'UTF-8');
            $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');

            // Validation des champs requis
            if (empty($nom) || empty($prenom) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header("Location: " . BASE_URL . "index.php?page=contact&status=error");
                exit;
            }

            // Enregistrement en base de données
            try {
                $sql = "INSERT INTO contact_messages (nom, prenom, email, sujet, message) 
                        VALUES (:nom, :prenom, :email, :sujet, :message)";
                
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':nom'     => $nom,
                    ':prenom'  => $prenom,
                    ':email'   => $email,
                    ':sujet'   => $sujet,
                    ':message' => $message
                ]);
            } catch (PDOException $e) {
                error_log("Erreur enregistrement contact : " . $e->getMessage());
                header("Location: " . BASE_URL . "index.php?page=contact&status=server_error");
                exit;
            }

            // Configuration et envoi de l'email
            $to      = "julie@vite-gourmand.fr";
            $subject = "Site Web - Nouveau message de : $nom $prenom";
            $body    = "Client : $nom $prenom\nEmail : $email\nSujet : $sujet\n\nMessage :\n$message";

            $headers  = "From: no-reply@vite-gourmand.fr\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

            // On envoie le mail (et on redirige vers le succès même si le serveur de mail local échoue)
            @mail($to, $subject, $body, $headers);

            header("Location: " . BASE_URL . "index.php?page=contact&status=success");
            exit;

        } else {
            header("Location: " . BASE_URL . "index.php?page=contact");
            exit;
        }
    }
}