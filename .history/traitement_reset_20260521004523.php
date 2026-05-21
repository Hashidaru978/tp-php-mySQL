<?php

session_start();

require_once 'config/connexion.php';

/* EMAIL ENTRE */

$email = trim($_POST['email'] ?? '');

/* EMAIL UTILISATEUR CONNECTE */

$stmt = $pdo->prepare(
    "SELECT id, email
     FROM utilisateurs
     WHERE id = :id
     LIMIT 1"
);

$stmt->execute([

    ':id' => $_SESSION['utilisateur_id']

]);

$utilisateur = $stmt->fetch();

/* SI EMAIL DIFFERENT */

if (!$utilisateur || $email !== $utilisateur['email']) {

    $_SESSION['erreur_reset'] =
        "Ce n'est pas l'email de votre compte.";

    header('Location: mot_de_passe_oublie.php');

    exit;
}

/* TOKEN */

$token = bin2hex(random_bytes(32));

/* EXPIRATION */

$expiration = date(
    'Y-m-d H:i:s',
    strtotime('+1 hour')
);

/* UPDATE */

$stmt = $pdo->prepare(
    "UPDATE utilisateurs
     SET reset_token = :token,
         reset_expiration = :expiration
     WHERE id = :id"
);

$stmt->execute([

    ':token' => $token,

    ':expiration' => $expiration,

    ':id' => $utilisateur['id']

]);

/* LIEN */

$lien =
    "http://localhost/dossierprincipal/nouveau_mot_de_passe.php?token=$token";

/* AFFICHAGE */

echo "

<!DOCTYPE html>

<html lang='fr'>

<head>

<meta charset='UTF-8'>

<title>Lien reset</title>

<link rel='stylesheet' href='css/style.css'>

</head>

<body>

<div class='container'>

<h1>Lien temporaire</h1>

<p>

Cliquez sur le lien :

</p>

<a href='$lien'>

$lien

</a>

<br><br>

<a href='profil.php' class='btn'>

Retour profil

</a>

</div>

</body>

</html>

";