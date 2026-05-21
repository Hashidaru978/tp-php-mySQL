<?php

require_once 'config/connexion.php';

$token = $_GET['token'] ?? ''; 

$stmt = $pdo->prepare(
    "SELECT id
     FROM utilisateurs
     WHERE reset_token = :token
     AND reset_expiration > NOW()
     LIMIT 1"
);

$stmt->execute([
    ':token' => $token
]);

$user = $stmt->fetch();

if (!$user) {

    die("Lien invalide ou expiré.");

}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Nouveau mot de passe</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container">

    <h1>Nouveau mot de passe</h1>

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

            Modifier

        </button>

    </form>

</div>

</body>
</html>
