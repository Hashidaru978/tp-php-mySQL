<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('connexion.php');
}

$email = trim((string) ($_POST['email'] ?? ''));
$password = trim((string) ($_POST['mot_de_passe'] ?? ''));
$rememberMe = isset($_POST['souvenir']);

if ($email === '' || $password === '') {
    flash_set('error', 'Veuillez remplir tous les champs.');
    old_set('email_connexion', $email);
    redirect('connexion.php');
}

$stmt = $pdo->prepare(
    'SELECT id, prenom, email, mot_de_passe, tentatives_connexion, compte_bloque_jusqua
     FROM utilisateurs
     WHERE email = :email
     LIMIT 1'
);
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();

if ($user && !empty($user['compte_bloque_jusqua']) && strtotime((string) $user['compte_bloque_jusqua']) > time()) {
    flash_set('error', 'Compte temporairement bloque. Reessayez plus tard.');
    old_set('email_connexion', $email);
    redirect('connexion.php');
}

$passwordHash = $user ? (string) $user['mot_de_passe'] : '';
$validCredentials = $user && password_verify($password, $passwordHash);

if (!$validCredentials) {
    if ($user) {
        $attempts = (int) $user['tentatives_connexion'] + 1;

        if ($attempts >= MAX_LOGIN_ATTEMPTS) {
            $blockedUntil = date('Y-m-d H:i:s', strtotime('+' . LOCKOUT_MINUTES . ' minutes'));
            $stmt = $pdo->prepare('UPDATE utilisateurs SET tentatives_connexion = 0, compte_bloque_jusqua = :blocked_until WHERE id = :id');
            $stmt->execute([':blocked_until' => $blockedUntil, ':id' => $user['id']]);
            flash_set('error', 'Compte bloque pendant ' . LOCKOUT_MINUTES . ' minutes.');
        } else {
            $stmt = $pdo->prepare('UPDATE utilisateurs SET tentatives_connexion = :attempts WHERE id = :id');
            $stmt->execute([':attempts' => $attempts, ':id' => $user['id']]);
            $remaining = MAX_LOGIN_ATTEMPTS - $attempts;
            flash_set('error', 'Email ou mot de passe incorrect. Tentatives restantes : ' . $remaining . '.');
        }
    } else {
        flash_set('error', 'Email ou mot de passe incorrect.');
    }

    old_set('email_connexion', $email);
    redirect('connexion.php');
}

session_regenerate_id(true);

$stmt = $pdo->prepare('UPDATE utilisateurs SET tentatives_connexion = 0, compte_bloque_jusqua = NULL WHERE id = :id');
$stmt->execute([':id' => $user['id']]);

$_SESSION['utilisateur_id'] = (int) $user['id'];
$_SESSION['utilisateur_prenom'] = (string) $user['prenom'];
$_SESSION['utilisateur_email'] = (string) $user['email'];

if ($rememberMe) {
    $token = remember_me_create((int) $user['id']);
    setcookie('souvenir_utilisateur', $token, [
        'expires' => time() + (REMEMBER_ME_DAYS * 24 * 60 * 60),
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => false,
    ]);
}

redirect('accueil.php');
