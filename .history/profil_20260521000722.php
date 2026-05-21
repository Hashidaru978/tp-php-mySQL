<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_auth();

$user = current_user($pdo);
if (!$user) {
    session_destroy();
    redirect('connexion.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newFirstname = trim((string) ($_POST['prenom'] ?? ''));

    if (mb_strlen($newFirstname) < 2) {
        flash_set('error', 'Le prenom doit contenir au moins 2 caracteres.');
        redirect('profil.php');
    }

    [$validUpload, $uploadResult] = validate_image_upload($_FILES['photo'] ?? []);
    if (!$validUpload) {
        flash_set('error', (string) $uploadResult);
        redirect('profil.php');
    }

    $stmt = $pdo->prepare('UPDATE utilisateurs SET prenom = :prenom WHERE id = :id');
    $stmt->execute([':prenom' => $newFirstname, ':id' => $user['id']]);

    if (!empty($_FILES['photo']['name']) && is_string($uploadResult)) {
        $uploadDirectory = __DIR__ . '/uploads';
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0775, true);
        }

        $filename = sprintf('%d_%s.%s', $user['id'], bin2hex(random_bytes(8)), $uploadResult);
        $relativePath = 'uploads/' . $filename;
        $absolutePath = __DIR__ . '/' . $relativePath;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $absolutePath)) {
            $stmt = $pdo->prepare('UPDATE utilisateurs SET photo_profil = :photo WHERE id = :id');
            $stmt->execute([':photo' => $relativePath, ':id' => $user['id']]);
        }
    }

    $_SESSION['utilisateur_prenom'] = $newFirstname;
    flash_set('success', 'Profil mis a jour avec succes.');
    redirect('profil.php');
}

$successMessage = flash_get('success');
$errorMessage = flash_get('error');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil | <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php require __DIR__ . '/includes/header.php'; ?>
<main class="page-main">
    <section class="card">
        <h1>Modifier mon profil</h1>
        <?php if ($successMessage): ?><div class="alert alert-success"><?= e($successMessage) ?></div><?php endif; ?>
        <?php if ($errorMessage): ?><div class="alert alert-error"><?= e($errorMessage) ?></div><?php endif; ?>

        <?php if (!empty($user['photo_profil'])): ?><img src="<?= e($user['photo_profil']) ?>" alt="Photo de profil" class="avatar"><?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="form-grid">
            <div class="form-group">
                <label for="prenom">Nouveau prenom</label>
                <input type="text" id="prenom" name="prenom" value="<?= e((string) $user['prenom']) ?>" required>
            </div>
            <div class="form-group">
                <label for="photo">Photo de profil</label>
                <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
            </div>
            <button type="submit" class="btn">Enregistrer</button>
        </form>

        <div class="actions-stack">
            <a href="mot_de_passe_oublie.php">Modifier le mot de passe</a>
            <a href="accueil.php">Retour a l'accueil</a>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
