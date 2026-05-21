<?php

session_start();

require_once 'config/connexion.php';

/* RECUPERATION EMAIL */

$email = trim($_POST['email'] ?? '');

/* VERIFICATION EMAIL */

$stmt = $pdo->prepare(
    "SELECT id, email
     FROM utilisateurs
     WHERE email = :email
     LIMIT 1"
);

$stmt->execute([
    ':email' => $email
]);

$user = $stmt->fetch();

/* SI EMAIL INEXISTANT */

if (!$user) {

    $_SESSION['erreur_reset'] =
        "Cette adresse email n'existe pas.";

    header('Location: mot_de_passe_oublie.php');

    exit;
}

/* CREATION TOKEN */

$token = bin2hex(random_bytes(32));

/* EXPIRATION */

$expiration = date(
    'Y-m-d H:i:s',
    strtotime('+1 hour')
);

/* UPDATE SQL */

$stmt = $pdo->prepare(
    "UPDATE utilisateurs
     SET reset_token = :token,
         reset_expiration = :expiration
     WHERE id = :id"
);

$stmt->execute([

    ':token' => $token,

    ':expiration' => $expiration,

    ':id' => $user['id']

]);

/* LIEN */

$lien =
    "http://localhost/dossierprincipal/nouveau_mot_de_passe.php?token=$token";

/* MESSAGE SUCCES */

$_SESSION['succes_reset'] =
    "Lien de reinitialisation genere avec succes.";

/* AFFICHAGE LIEN */

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

Cliquez sur le lien ci-dessous :

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