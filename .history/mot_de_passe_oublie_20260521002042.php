<?php

session_start();

$errorMessage = flash_get('error');
$successMessage = flash_get('success');
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
