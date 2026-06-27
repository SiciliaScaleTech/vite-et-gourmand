<?php
namespace App\Controllers;

class LegalController {

    public function mentionsLegales() {
        require_once __DIR__ . '/../Views/legal/mentions_legales.php';
    }

    public function politiqueConfidentialite() {
        require_once __DIR__ . '/../Views/legal/politique_confidentialite.php';
    }

    public function cgu() {
        require_once __DIR__ . '/../Views/legal/cgu.php';
    }
}