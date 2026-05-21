<?php

session_start();

require_once 'config/connexion.php';

/* ── Protection page ───────────────────────── */

if (empty($_SESSION['utilisateur_id'])) {

    header('Location: connexion.php');

    exit;
}

/* ── Recuperer utilisateur ─────────────────── */

$stmt = $pdo->prepare(
    "SELECT prenom,
            email,
            date_inscription,
            photo_profil
     FROM utilisateurs
     WHERE id = :id
     LIMIT 1"
);

$stmt->execute([

    ':id' => $_SESSION['utilisateur_id']

]);

$user = $stmt->fetch();

/* ── Utilisateur inexistant ────────────────── */

if (!$user) {

    session_destroy();

    header('Location: connexion.php');

    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Accueil</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<!-- NAVBAR -->

<nav>

    <a class="logo" href="#">

        MonSite

    </a>

    <div>

        <span style="color:#a8d8ea; margin-right:10px;">

            Bonjour, <?= htmlspecialchars($user['prenom']) ?> !

        </span>

        <a href="deconnexion.php">

            Se deconnecter

        </a>

    </div>

</nav>

<!-- CONTENU -->

<div class="welcome-box">

    <h1>Bienvenue sur votre espace</h1>

    <!-- PHOTO -->

    <?php if (!empty($user['photo_profil'])) : ?>

        <div style="text-align:center; margin-bottom:20px;">

            <img
                src="<?= htmlspecialchars($user['photo_profil']) ?>"
                alt="Photo profil"
                style="
                    width:120px;
                    height:120px;
                    border-radius:50%;
                    object-fit:cover;
                "
            >

        </div>

    <?php endif; ?>

    <!-- EMAIL -->

    <div class="badge-email">

        <?= htmlspecialchars($user['email']) ?>

    </div>

    <!-- TEXTE -->

    <p>

        Bonjour
        <strong>

            <?= htmlspecialchars($user['prenom']) ?>

        </strong>,

        vous etes connecte avec succes.

    </p>

    <!-- DATE -->

    <p style="color:#aaa; font-size:0.85rem;">

        Membre depuis le

        <?= date('d/m/Y', strtotime($user['date_inscription'])) ?>

    </p>

    <!-- BOUTON PROFIL -->

    <br><br>

    <a
        href="profil.php"
        class="btn"
        style="
            max-width:220px;
            display:inline-block;
            margin-right:10px;
        "
    >

        Modifier mon profil

    </a>

    <!-- DECONNEXION -->

    <a
        href="deconnexion.php"
        class="btn"
        style="
            max-width:220px;
            display:inline-block;
        "
    >

        Se deconnecter

    </a>

</div>

</body>
</html>