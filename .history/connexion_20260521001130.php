<?php

declare(strict_types=1);


if (is_logged_in()) {
    redirect('accueil.php');
}

$successMessage = flash_get('success');
$errorMessage = flash_get('error');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php require __DIR__ . '/includes/header.php'; ?>
<main class="page-main">
    <section class="card">
        <h1>Connexion</h1>
        <p class="subtitle">Heureux de vous revoir.</p>

        <?php if ($successMessage): ?><div class="alert alert-success"><?= e($successMessage) ?></div><?php endif; ?>
        <?php if ($errorMessage): ?><div class="alert alert-error"><?= e($errorMessage) ?></div><?php endif; ?>

        <form action="traitement_connexion.php" method="POST" class="form-grid">
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" value="<?= e(old_get('email_connexion')) ?>" required>
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>

            <label class="remember-line" for="souvenir">
                <span>Se souvenir de moi (30 jours)</span>
                <input type="checkbox" name="souvenir" id="souvenir">
            </label>

            <button type="submit" class="btn">Se connecter</button>
        </form>

        <p class="form-footer">Pas encore de compte ? <a href="inscription.php">S'inscrire</a></p>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
