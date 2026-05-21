<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MonSite</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav>

    <a class="logo" href="#">

        MonSite

    </a>

    <div>

        <?php if (!empty($_SESSION['utilisateur_id'])) : ?>

            <a href="accueil.php">

                Accueil

            </a>

            <a href="profil.php">

                Profil

            </a>

            <a href="deconnexion.php">

                Se deconnecter

            </a>

        <?php else : ?>

            <a href="inscription.php">

                S'inscrire

            </a>

            <a href="connexion.php">

                Se connecter

            </a>

        <?php endif; ?>

    </div>

</nav>
