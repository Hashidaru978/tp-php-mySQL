<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$errorMessage = flash_get('error');
$successMessage = flash_get('success');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reinitialiser mot de passe | <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php require __DIR__ . '/includes/header.php'; ?>
<main class="page-main">
    <section class="card">
        <h1>Mot de passe oublie</h1>
        <p class="subtitle">Entrez votre email pour recevoir un lien de reinitialisation.</p>
        <?php if ($errorMessage): ?><div class="alert alert-error"><?= e($errorMessage) ?></div><?php endif; ?>
        <?php if ($successMessage): ?><div class="alert alert-success"><?= e($successMessage) ?></div><?php endif; ?>
        <form action="traitement_reset.php" method="POST" class="form-grid">
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="btn">Generer le lien</button>
        </form>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
