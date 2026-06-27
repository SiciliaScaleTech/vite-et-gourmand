<?php
// commands/hashPassword.php

echo "--- GÉNÉRATEUR DE MOT DE PASSE VITE & GOURMAND ---\n";
echo "Saisissez le mot de passe à hacher : ";

// Attend que le mot de passe soit tapé dans le terminal
$passwordEnClair = trim(fgets(STDIN));

if (empty($passwordEnClair)) {
    die("Erreur : Le mot de passe ne peut pas être vide.\n");
}

$passwordHache = password_hash($passwordEnClair, PASSWORD_DEFAULT);

echo "\nVoici ton mot de passe haché (prêt à être copié dans SQL) :\n";
echo $passwordHache . "\n";