<?php

session_start();

require_once 'config/connexion.php';

/* ── Protection connexion ───────────────────── */

if (empty($_SESSION['utilisateur_id'])) {

    header('Location: connexion.php');
    exit;

}

/* ── Recuperer role utilisateur ─────────────── */

$stmt = $pdo->prepare(
    "SELECT role
     FROM utilisateurs
     WHERE id = :id
     LIMIT 1"
);

$stmt->execute([
    ':id' => $_SESSION['utilisateur_id']
]);

$user = $stmt->fetch();

/* ── Verifier admin ─────────────────────────── */

if (!$user || $user['role'] !== 'admin') {

    die("Acces refuse.");

}

/* ── Recuperer tous les utilisateurs ────────── */

$stmt = $pdo->query(
    "SELECT prenom, nom, email, role, date_inscription
     FROM utilisateurs
     ORDER BY id DESC"
);

$utilisateurs = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Administration</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav>

    <a class="logo" href="#">MonSite Admin</a>

    <div>

        <a href="accueil.php">Accueil</a>

        <a href="deconnexion.php">Se deconnecter</a>

    </div>

</nav>

<div class="container" style="width:90%;">

    <h1>Liste des utilisateurs</h1>

    <table style="width:100%; border-collapse:collapse;">

        <tr style="background:#007BFF; color:white;">

            <th style="padding:10px;">Prenom</th>

            <th style="padding:10px;">Nom</th>

            <th style="padding:10px;">Email</th>

            <th style="padding:10px;">Role</th>

            <th style="padding:10px;">Date inscription</th>

        </tr>

        <?php foreach ($utilisateurs as $u) : ?>

            <tr>

                <td style="padding:10px; border:1px solid #ccc;">

                    <?= htmlspecialchars($u['prenom']) ?>

                </td>

                <td style="padding:10px; border:1px solid #ccc;">

                    <?= htmlspecialchars($u['nom']) ?>

                </td>

                <td style="padding:10px; border:1px solid #ccc;">

                    <?= htmlspecialchars($u['email']) ?>

                </td>

                <td style="padding:10px; border:1px solid #ccc;">

                    <?= htmlspecialchars($u['role']) ?>

                </td>

                <td style="padding:10px; border:1px solid #ccc;">

                    <?= date('d/m/Y', strtotime($u['date_inscription'])) ?>

                </td>

            </tr>

        <?php endforeach; ?>

    </table>

</div>

</body>
</html>
