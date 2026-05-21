<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('inscription.php');
}

$prenom = trim((string) ($_POST['prenom'] ?? ''));
$nom = trim((string) ($_POST['nom'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$password = trim((string) ($_POST['mot_de_passe'] ?? ''));
$passwordConfirm = trim((string) ($_POST['confirmer_mdp'] ?? ''));

old_set('prenom', $prenom);
old_set('nom', $nom);
old_set('email', $email);

if ($prenom === '' || $nom === '' || $email === '' || $password === '' || $passwordConfirm === '') {
    flash_set('error', 'Tous les champs sont obligatoires.');
    redirect('inscription.php');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash_set('error', 'Adresse email invalide.');
    redirect('inscription.php');
}

if (mb_strlen($prenom) < 2 || mb_strlen($prenom) > 100 || mb_strlen($nom) < 2 || mb_strlen($nom) > 100) {
    flash_set('error', 'Le prenom et le nom doivent contenir entre 2 et 100 caracteres.');
    redirect('inscription.php');
}

if (strlen($password) < 8) {
    flash_set('error', 'Le mot de passe doit contenir au moins 8 caracteres.');
    redirect('inscription.php');
}

if ($password !== $passwordConfirm) {
    flash_set('error', 'Les mots de passe ne correspondent pas.');
    redirect('inscription.php');
}

$stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE email = :email LIMIT 1');
$stmt->execute([':email' => $email]);
if ($stmt->fetch()) {
    flash_set('error', 'Cette adresse email est deja utilisee.');
    redirect('inscription.php');
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO utilisateurs (prenom, nom, email, mot_de_passe) VALUES (:prenom, :nom, :email, :password)');
$created = $stmt->execute([
    ':prenom' => $prenom,
    ':nom' => $nom,
    ':email' => $email,
    ':password' => $hashedPassword,
]);

if (!$created) {
    flash_set('error', 'Une erreur est survenue. Merci de reessayer.');
    redirect('inscription.php');
}

unset($_SESSION['old']);
flash_set('success', 'Compte cree avec succes. Vous pouvez maintenant vous connecter.');
redirect('connexion.php');
