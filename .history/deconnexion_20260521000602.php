<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$_SESSION = [];
session_destroy();

setcookie('souvenir_utilisateur', '', [
    'expires' => time() - 3600,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => false,
]);

redirect('connexion.php');
