<?php

session_start();

require_once 'config/connexion.php';

/* TOKEN */

$token = $_POST['token'] ?? '';

/* MOT DE PASSE */

$mdp = trim($_POST['mot_de_passe'] ?? '');

/* RECHERCHE UTILISATEUR */

$stmt = $pdo->prepare(
    "SELECT id,
            mot_de_passe
     FROM utilisateurs
     WHERE reset_token = :token
     AND reset_expiration > NOW()
     LIMIT 1"
);

$stmt->execute([

    ':token' => $token

]);

$user = $stmt->fetch();

/* TOKEN INVALIDE */

if (!$user) {

    die("Token invalide ou expiré.");

}

/* ERREUR */

$erreur = '';

/* LONGUEUR */

if (strlen($mdp) < 8) {

    $erreur =
        "Le mot de passe doit contenir au moins 8 caracteres.";
}

/* MEME MDP */

elseif (password_verify($mdp, $user['mot_de_passe'])) {

    $erreur =
        "Le nouveau mot de passe doit etre different de l'actuel.";
}

/* SI ERREUR */

if (!empty($erreur)) {

    ?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>

        <meta charset="UTF-8">

        <title>Erreur</title>

        <link rel="stylesheet" href="css/style.css">

    </head>

    <body>

    <div class="container">

        <h1>Nouveau mot de passe</h1>

        <div class="alerte alerte-erreur">

            <?= htmlspecialchars($erreur) ?>

        </div>

        <form action="update_password.php" method="POST">

            <input
                type="hidden"
                name="token"
                value="<?= htmlspecialchars($token) ?>"
            >

            <div class="form-group">

                <label>Nouveau mot de passe</label>

                <input
                    type="password"
                    name="mot_de_passe"
                    required
                >

            </div>

            <button type="submit" class="btn">

                Modifier le mot de passe

            </button>

        </form>

    </div>

    </body>
    </html>

    <?php

    exit;
}

/* HASH */

$mdp_hache = password_hash(
    $mdp,
    PASSWORD_DEFAULT
);

/* UPDATE */

$stmt = $pdo->prepare(
    "UPDATE utilisateurs
     SET mot_de_passe = :mdp,
         reset_token = NULL,
         reset_expiration = NULL
     WHERE id = :id"
);

$stmt->execute([

    ':mdp' => $mdp_hache,

    ':id' => $user['id']

]);

/* MESSAGE SUCCES */

$_SESSION['succes_profil'] =
    "Mot de passe modifie avec succes.";

/* REDIRECTION */

header('Location: profil.php');

exit;
