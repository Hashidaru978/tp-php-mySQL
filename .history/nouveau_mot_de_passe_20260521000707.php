<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$token = (string) ($_GET['token'] ?? '');

if ($token === '') {
    flash_set('error', 'Token de reinitialisation manquant.');
    redirect('mot_de_passe_oublie.php');
}

$stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE reset_token = :token AND reset_expiration > NOW() LIMIT 1');
$stmt->execute([':token' => $token]);
$user = $stmt->fetch();

if (!$user) {
    flash_set('error', 'Lien invalide ou expire.');
    redirect('mot_de_passe_oublie.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe | <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php require __DIR__ . '/includes/header.php'; ?>
<main class="page-main">
    <section class="card">
        <h1>Nouveau mot de passe</h1>
        <form action="update_password.php" method="POST" class="form-grid">
            <input type="hidden" name="token" value="<?= e($token) ?>">
            <div class="form-group">
                <label for="mot_de_passe">Nouveau mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" minlength="8" required>
            </div>
            <button type="submit" class="btn">Modifier</button>
        </form>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
