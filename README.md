# 🔐 AuthSys PHP

Un système d’authentification moderne en PHP/MySQL conçu pour XAMPP, avec gestion des utilisateurs, sécurité renforcée et administration.

---

## Description

Ce projet implémente un système d’authentification complet en PHP 8 et MySQL. Il offre un parcours utilisateur sécurisé pour l’inscription, la connexion, la gestion de profil et la réinitialisation de mot de passe, tout en protégeant les routes sensibles et en incluant un panel admin.

---

## Fonctionnalités principales

- ✅ Inscription utilisateur
- ✅ Connexion sécurisée
- ✅ Déconnexion
- ✅ Sessions PHP pour garder l’utilisateur connecté
- ✅ Hashage des mots de passe avec `password_hash`
- ✅ Reset de mot de passe par token sécurisé
- ✅ Blocage de compte après 5 tentatives erronées
- ✅ Option “Se souvenir de moi”
- ✅ Upload de photo de profil
- ✅ Gestion du profil utilisateur
- ✅ Panel admin
- ✅ Protection des routes accessibles seulement aux utilisateurs connectés

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

## Installation avec XAMPP

1. Télécharge et installe XAMPP.
2. Copie le dossier du projet dans `C:\xampp\htdocs\`.
3. Lance Apache et MySQL depuis le panneau de contrôle XAMPP.
4. Ouvre ton navigateur et va sur :
   - `http://localhost/dossierprincipal/`

---

## Configuration de la base de données

1. Ouvre `http://localhost/phpmyadmin/`.
2. Crée une base de données, par exemple : `authsys_db`.
3. Exécute le script SQL pour créer les tables nécessaires.
4. Modifie `config/connexion.php` avec tes informations :

```php
<?php
$host = 'localhost';
$db   = 'authsys_db';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}
```

5. Assure-toi que les tables suivantes sont présentes :
   - `users`
   - `password_resets`
   - `login_attempts`

---

## Commandes Git utiles

```bash
git status

git add .
git commit -m "Ajout d'une fonctionnalité"
git push origin main

git pull origin main

git checkout -b feature/nom-du-feature
```

---

## Sécurité

- Utiliser `password_hash()` et `password_verify()` pour sécuriser les mots de passe.
- Protéger les routes avec des vérifications de session.
- Bloquer l’accès après 5 tentatives de connexion échouées.
- Empêcher l’accès aux pages administrateur sans authentification.
- Ne jamais stocker les mots de passe en clair.
- Ignorer les fichiers de configuration sensibles (`.env`) avec `.gitignore`.

---

## Captures d’écran

> Ajoute ici des captures d’écran du projet (connexion, profil, admin, formulaire de reset) une fois disponibles.

---

## Auteur

**Nom** : [Ton Nom]

**GitHub** : [https://github.com/Hashidaru978](https://github.com/Hashidaru978)

**Description** : Développeur PHP/MySQL, spécialisé dans les applications web sécurisées.
