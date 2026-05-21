<?php

session_start();

require_once 'config/connexion.php';

$token = $_POST['token'] ?? '';

$mdp = trim($_POST['mot_de_passe'] ?? '');

/* VERIFICATION TOKEN */

$stmt = $pdo->prepare(
    "SELECT id
     FROM utilisateurs
     WHERE reset_token = :token
     AND reset_expiration > NOW()
     LIMIT 1"
);

$stmt->execute([
    ':token' => $token
]);

$user = $stmt->fetch();

if (!$user) {

    die("Token invalide ou expiré.");

}

/* VERIFICATION MDP */

if (strlen($mdp) < 8) {

    die("Le mot de passe doit contenir au moins 8 caracteres.");

}

/* HASH */

$mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);

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

/* MESSAGE SUCCES */

$_SESSION['succes_profil'] = "Mot de passe modifie avec succes.";

/* REDIRECTION */

header('Location: profil.php');

exit;