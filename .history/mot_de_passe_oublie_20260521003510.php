<?php

session_start();

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

    <!-- MESSAGE SUCCES -->

    <?php if (!empty($_SESSION['succes_reset'])) : ?>

        <div class="alerte alerte-succes">

            <?= htmlspecialchars($_SESSION['succes_reset']) ?>

        </div>

        <?php unset($_SESSION['succes_reset']); ?>

    <?php endif; ?>

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

</div>

</body>
</html>