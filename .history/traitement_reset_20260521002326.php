<?php

require_once 'config/connexion.php';

$email = trim($_POST['email'] ?? '');

/* RECHERCHE USER */

$stmt = $pdo->prepare(
    "SELECT id
     FROM utilisateurs
     WHERE email = :email
     LIMIT 1"
);

$stmt->execute([
    ':email' => $email
]);

$user = $stmt->fetch();

if (!$user) {

    die("Aucun compte trouvé.");

}

/* CREATION TOKEN */

$token = bin2hex(random_bytes(32));

/* EXPIRATION */

$expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

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

$lien = "http://localhost/dossierP/nouveau_mot_de_passe.php?token=$token";

echo "<div style='padding:30px;'>";

echo "<h2>Lien temporaire :</h2>";

echo "<a href='$lien'>$lien</a>";

echo "</div>";