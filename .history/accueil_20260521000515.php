<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_auth();

$user = current_user($pdo);
if (!$user) {
    session_destroy();
    redirect('connexion.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil | <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php require __DIR__ . '/includes/header.php'; ?>
<main class="page-main">
    <section class="card card-wide">
        <h1>Bienvenue sur votre espace</h1>

        <?php if (!empty($user['photo_profil'])): ?>
            <img src="<?= e($user['photo_profil']) ?>" alt="Photo de profil" class="avatar">
        <?php endif; ?>

        <p class="chip"><?= e($user['email']) ?></p>
        <p>Bonjour <strong><?= e($user['prenom']) ?></strong>, vous etes connecte avec succes.</p>
        <p class="muted">Membre depuis le <?= e(date('d/m/Y', strtotime((string) $user['date_inscription']))) ?></p>

        <div class="actions-inline">
            <a href="profil.php" class="btn btn-inline">Modifier mon profil</a>
            <?php if (($user['role'] ?? '') === 'admin'): ?><a href="admin.php" class="btn btn-inline btn-secondary">Administration</a><?php endif; ?>
            <a href="deconnexion.php" class="btn btn-inline btn-secondary">Se deconnecter</a>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
