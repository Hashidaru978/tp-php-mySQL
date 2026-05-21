# AuthSys PHP

Un système d’authentification moderne en PHP/MySQL conçu pour XAMPP, avec gestion des utilisateurs, sécurité renforcée et administration.

---

## Description

Ce projet implémente un système d’authentification complet en PHP 8 et MySQL.

Il offre un parcours utilisateur sécurisé pour :

- l’inscription
- la connexion
- la gestion de profil
- la réinitialisation de mot de passe

Le projet inclut également :
- une protection des routes privées
- un système de sécurité contre les tentatives de connexion abusives
- un espace administrateur


---

## Fonctionnalités clés

- Inscription sécurisée avec validation côté serveur
- Connexion avec gestion de session PHP
- Déconnexion et purge des données de session
- Réinitialisation de mot de passe via token expirant
- Téléversement d’avatar utilisateur dans `uploads/`
- Protection des pages privées pour les utilisateurs authentifiés
- Requêtes préparées PDO pour toutes les interactions SQL
- Contrôle de rôle basique (admin / user)

---

## Technologies utilisées

- PHP 8
- MySQL
- HTML / CSS
- XAMPP
- Git / GitHub

---

## Structure des dossiers

```text
/                      # racine du projet
├─ accueil.php          # page d’accueil
├─ admin.php            # interface admin
├─ connexion.php        # page de connexion
├─ deconnexion.php      # déconnexion
├─ inscription.php      # formulaire d’inscription
├─ mot_de_passe_oublie.php  # demande reset
├─ nouveau_mot_de_passe.php # saisie nouveau mot de passe
├─ profil.php           # gestion du profil
├─ traitement_*         # logique des formulaires
├─ config/connexion.php # configuration de la base de données
├─ css/style.css        # styles globaux
├─ includes/            # header + footer partagés
│  ├─ header.php
│  └─ footer.php
├─ uploads/             # fichiers uploadés (profil, images)
└─ .gitignore           # fichiers ignorés par Git
```

---

## Installation

1. Installer XAMPP avec Apache et MySQL.
2. Copier le projet dans `C:\xampp\htdocs\dossierprincipal`.
3. Démarrer Apache et MySQL via le panneau XAMPP.
4. Accéder à l’application depuis le navigateur :
   - `http://localhost/dossierprincipal/`

---

## Configuration de la base de données

1. Créer une base de données dans phpMyAdmin.
   - Exemple : `authsys_db`
2. Exécuter la requête SQL suivante pour créer la table `utilisateurs` :

```sql
CREATE TABLE utilisateurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  prenom VARCHAR(100) NOT NULL,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(180) NOT NULL UNIQUE,
  mot_de_passe VARCHAR(255) NOT NULL,
  photo_profil VARCHAR(255) DEFAULT NULL,
  role VARCHAR(50) DEFAULT 'user',
  tentatives_connexion INT DEFAULT 0,
  compte_bloque_jusqua DATETIME DEFAULT NULL,
  reset_token VARCHAR(255) DEFAULT NULL,
  reset_expiration DATETIME DEFAULT NULL,
  date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

3. Mettre à jour `config/connexion.php` avec les paramètres de connexion MySQL :

```php
<?php
$host = 'localhost';
$db   = 'authsys_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  die('Connexion base de données impossible : ' . $e->getMessage());
}
```

4. Vérifier que les tables suivantes existent :
   - `utilisateurs`

---

## Utilisation Git

```bash
git add README.md .gitignore
git commit -m "Mise à jour du README"
git push origin main
```

---

## Sécurité

- Hachage des mots de passe avec `password_hash()`.
- Vérification des connexions avec `password_verify()`.
- Requêtes préparées PDO pour toutes les opérations SQL.
- Vérification de session sur chaque page privée.
- Protection contre les accès non autorisés.
- Ne pas inclure de configurations sensibles dans le dépôt.

---

## Captures d’écran

Ajoute ici des images pour :
- la page de connexion
- la page profil
- la page de réinitialisation de mot de passe
- l’interface admin

---

## Auteur

- GitHub : https://github.com/Hashidaru978

## Licence

Projet éducatif pour l’apprentissage PHP/MySQL.
