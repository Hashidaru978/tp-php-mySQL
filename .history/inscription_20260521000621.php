<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (is_logged_in()) {
    redirect('accueil.php');
}

$errorMessage = flash_get('error');
$successMessage = flash_get('success');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription | <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php require __DIR__ . '/includes/header.php'; ?>
<main class="page-main">
    <section class="card">
        <h1>Creer un compte</h1>
        <p class="subtitle">Rejoignez-nous en quelques secondes.</p>

        <?php if ($errorMessage): ?><div class="alert alert-error"><?= e($errorMessage) ?></div><?php endif; ?>
        <?php if ($successMessage): ?><div class="alert alert-success"><?= e($successMessage) ?></div><?php endif; ?>

        <form action="traitement_inscription.php" method="POST" class="form-grid">
            <div class="form-group">
                <label for="prenom">Prenom</label>
                <input type="text" id="prenom" name="prenom" value="<?= e(old_get('prenom')) ?>" required>
            </div>

            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?= e(old_get('nom')) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" value="<?= e(old_get('email')) ?>" required>
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" minlength="8" required>
            </div>

            <div class="form-group">
                <label for="confirmer_mdp">Confirmer le mot de passe</label>
                <input type="password" id="confirmer_mdp" name="confirmer_mdp" minlength="8" required>
            </div>

            <button type="submit" class="btn">Creer mon compte</button>
        </form>

        <p class="form-footer">Vous avez deja un compte ? <a href="connexion.php">Se connecter</a></p>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
