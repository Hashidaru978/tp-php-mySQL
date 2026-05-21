<?php

session_start();

require_once 'config/connexion.php';

/* RECUPERER EMAIL UTILISATEUR CONNECTE */

$stmt = $pdo->prepare(
    "SELECT email
     FROM utilisateurs
     WHERE id = :id
     LIMIT 1"
);

$stmt->execute([

    ':id' => $_SESSION['utilisateur_id']

]);

$user = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Mot de passe oublié</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container">

    <h1>Réinitialisation mot de passe</h1>

    <p class="subtitle">

        Entrez votre adresse email.

    </p>

    <!-- MESSAGE ERREUR -->

    <?php if (!empty($_SESSION['erreur_reset'])) : ?>

        <div class="alerte alerte-erreur">

            <?= htmlspecialchars($_SESSION['erreur_reset']) ?>

        </div>

        <?php unset($_SESSION['erreur_reset']); ?>

    <?php endif; ?>

    <!-- FORMULAIRE -->

    <form action="traitement_reset.php" method="POST">

        <div class="form-group">

            <label>Email</label>

            <input
                type="email"
                name="email"
                required
            >

        </div>

        <button type="submit" class="btn">

            Envoyer le lien

        </button>

    </form>

    <br>

    <p style="color:#999; font-size:14px;">

        Email attendu :
        <strong>

            <?= htmlspecialchars($user['email']) ?>

        </strong>

    </p>

</div>

</body>
</html>