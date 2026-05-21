<?php

session_start();

require_once 'config/connexion.php';

/* ── Protection page ───────────────────────────── */

if (empty($_SESSION['utilisateur_id'])) { 

    header('Location: connexion.php');
    exit;

}

/* ── Recuperer utilisateur ─────────────────────── */

$stmt = $pdo->prepare(
    "SELECT prenom, photo_profil
     FROM utilisateurs
     WHERE id = :id
     LIMIT 1"
);

$stmt->execute([
    ':id' => $_SESSION['utilisateur_id']
]);

$user = $stmt->fetch();

/* ── Si formulaire envoye ─────────────────────── */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nouveau_prenom = trim($_POST['prenom'] ?? '');

    /* Verification prenom */

    if (empty($nouveau_prenom)) {

        $erreur = "Le prenom est obligatoire.";

    } elseif (strlen($nouveau_prenom) < 2) {

        $erreur = "Le prenom est trop court.";

    } else {

        /* UPDATE PRENOM */

        $stmt = $pdo->prepare(
            "UPDATE utilisateurs
             SET prenom = :prenom
             WHERE id = :id"
        );

        $resultat = $stmt->execute([

            ':prenom' => htmlspecialchars($nouveau_prenom, ENT_QUOTES, 'UTF-8'),

            ':id' => $_SESSION['utilisateur_id']

        ]);

        /* ── Upload image ───────────────────────── */

        if (!empty($_FILES['photo']['name'])) {

            $dossier = 'uploads/';

            $nom_image = time() . '_' . basename($_FILES['photo']['name']);

            $chemin = $dossier . $nom_image;

            move_uploaded_file($_FILES['photo']['tmp_name'], $chemin);

            /* UPDATE PHOTO */

            $stmt_photo = $pdo->prepare(
                "UPDATE utilisateurs
                 SET photo_profil = :photo
                 WHERE id = :id"
            );

            $stmt_photo->execute([

                ':photo' => $chemin,

                ':id' => $_SESSION['utilisateur_id']

            ]);

            $user['photo_profil'] = $chemin;
        }

        if ($resultat) {

            $_SESSION['utilisateur_prenom'] = $nouveau_prenom;

            $succes = "Profil modifie avec succes.";

            $user['prenom'] = $nouveau_prenom;

        } else {

            $erreur = "Erreur lors de la modification.";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Modifier profil</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav>

    <a class="logo" href="#">MonSite</a>

    <div>

        <a href="accueil.php">Accueil</a>

        <a href="deconnexion.php">Se deconnecter</a>

    </div>

</nav>

<div class="container">

    <h1>Modifier mon profil</h1>

    <!-- MESSAGE RESET MDP -->

    <?php if (!empty($_SESSION['succes_profil'])) : ?>

        <div class="alerte alerte-succes">

            <?= htmlspecialchars($_SESSION['succes_profil']) ?>

        </div>

        <?php unset($_SESSION['succes_profil']); ?>

    <?php endif; ?>

    <!-- PHOTO ACTUELLE -->

    <?php if (!empty($user['photo_profil'])) : ?>

        <div style="text-align:center; margin-bottom:20px;">

            <img 
                src="<?= htmlspecialchars($user['photo_profil']) ?>" 
                alt="Photo profil"
                style="width:120px; height:120px; border-radius:50%; object-fit:cover;"
            >

        </div>

    <?php endif; ?>

    <!-- MESSAGE ERREUR -->

    <?php if (!empty($erreur)) : ?>

        <div class="alerte alerte-erreur">

            <?= htmlspecialchars($erreur) ?>

        </div>

    <?php endif; ?>

    <!-- MESSAGE SUCCES -->

    <?php if (!empty($succes)) : ?>

        <div class="alerte alerte-succes">

            <?= htmlspecialchars($succes) ?>

        </div>

    <?php endif; ?>

    <!-- FORMULAIRE -->

    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">

            <label for="prenom">

                Nouveau prenom

            </label>

            <input
                type="text"
                id="prenom"
                name="prenom"
                value="<?= htmlspecialchars($user['prenom']) ?>"
                required
            >

        </div>

        <!-- PHOTO -->

        <div class="form-group">

            <label for="photo">

                Photo de profil

            </label>

            <input
                type="file"
                id="photo"
                name="photo"
                accept="image/*"
            >

        </div>

        <button type="submit" class="btn">

            Enregistrer

        </button>

    </form>

    <!-- MODIFIER MOT DE PASSE -->

    <br><br>

    <a href="mot_de_passe_oublie.php" class="btn">

        Modifier le mot de passe

    </a>

    <br><br>

    <a href="accueil.php">

        Retour accueil

    </a>

</div>

</body>
</html>
