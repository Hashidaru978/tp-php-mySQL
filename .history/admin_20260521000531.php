<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_auth();

$user = current_user($pdo);
if (!$user) {
    session_destroy();
    redirect('connexion.php');
}
require_admin($user);

$stmt = $pdo->query('SELECT prenom, nom, email, role, date_inscription FROM utilisateurs ORDER BY id DESC');
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration | <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php require __DIR__ . '/includes/header.php'; ?>
<main class="page-main">
    <section class="card card-wide">
        <h1>Administration utilisateurs</h1>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Prenom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Inscription</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $entry): ?>
                        <tr>
                            <td><?= e((string) $entry['prenom']) ?></td>
                            <td><?= e((string) $entry['nom']) ?></td>
                            <td><?= e((string) $entry['email']) ?></td>
                            <td><?= e((string) $entry['role']) ?></td>
                            <td><?= e(date('d/m/Y', strtotime((string) $entry['date_inscription']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
