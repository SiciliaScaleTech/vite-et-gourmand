# Vite & Gourmand

Application web de gestion et de commande de menus traiteur développée en PHP selon une architecture MVC.

## Documentation du projet

Les livrables officiels au format PDF sont consultables directement dans le dépôt :
- [Manuel d'utilisation](./Documentations/manuel_utilisation.pdf)
- [Charte graphique (avec maquettes)](./documentation/charte_graphique.pdf)
- [Documentation technique et Gestion de projet](./documentation/Gestion_de_projet.pdf)

## Fonctionnalités

- Authentification avec hachage des mots de passe (bcrypt) et réinitialisation de mot de passe.
- Consultation des menus par catégories (Fêtes, Événements, Classique) et affichage des détails.
- Panier avec ajout, modification, suppression et validation de commandes.
- Espace Client pour la mise à jour des informations personnelles et la soumission d'avis.
- Espace Employé pour le traitement des commandes et la validation des avis.
- Espace Administrateur avec accès aux statistiques globales.
- Pages légales : CGU, mentions légales et politique de confidentialité.

## Technologies utilisées

- Backend : PHP 8.x (Architecture MVC, POO, PDO)
- Frontend : HTML5, CSS3, Bootstrap 5, JavaScript
- Gestionnaire de dépendances : Composer
- Base de données : MySQL / MongoDB

## Installation

1. Cloner le dépôt :
   ```bash
   git clone vite-et-gourmand
Importer les fichiers SQL situés dans database/sql/ dans la base de données :

structure.sql

seeds.sql

Configurer les identifiants de connexion à la base de données dans le fichier de configuration.

Installer les dépendances :

Bash
composer install
Comptes de test (Seeds)
Admin : jose.santos@gmail.com

Employé : julie.latoure@gmail.com

Utilisateur : fuse.carole@gmail.com


Une fois le fichier créé et enregistré sur GitHub, exécutez la commande suivante dans le terminal de votre machine locale pour synchroniser votre projet :

```bash
git checkout main
git pull origin main
