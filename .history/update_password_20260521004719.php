<?php

session_start();

require_once 'config/connexion.php';

/* TOKEN */

$token = $_POST['token'] ?? '';

/* NOUVEAU MDP */

$mdp = trim($_POST['mot_de_passe'] ?? '');

/* RECHERCHE UTILISATEUR */

$stmt = $pdo->prepare(
    "SELECT id,
            mot_de_passe
     FROM utilisateurs
     WHERE reset_token = :token
     AND reset_expiration > NOW()
     LIMIT 1"
);

$stmt->execute([

    ':token' => $token

]);

$user = $stmt->fetch();

/* TOKEN INVALIDE */

if (!$user) {

    die("Token invalide ou expiré.");

}

/* LONGUEUR MDP */

if (strlen($mdp) < 8) {

    die("Le mot de passe doit contenir au moins 8 caracteres.");

}

/* VERIFIER SI IDENTIQUE */

if (password_verify($mdp, $user['mot_de_passe'])) {

    die("Le nouveau mot de passe doit etre different de l'actuel.");

}

/* HASH */

$mdp_hache = password_hash(
    $mdp,
    PASSWORD_DEFAULT
);

/* UPDATE */

$stmt = $pdo->prepare(
    "UPDATE utilisateurs
     SET mot_de_passe = :mdp,
         reset_token = NULL,
         reset_expiration = NULL
     WHERE id = :id"
);

$stmt->execute([

    ':mdp' => $mdp_hache,

    ':id' => $user['id']

]);

/* MESSAGE */

$_SESSION['succes_profil'] =
    "Mot de passe modifie avec succes.";

/* REDIRECTION */

header('Location: profil.php');

exit;