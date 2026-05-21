<?php

session_start();

require_once 'config/connexion.php';

/* ── POST uniquement ─────────────────────────── */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: connexion.php');
    exit;

}

/* ── Recuperation donnees ────────────────────── */

$email = trim($_POST['email'] ?? '');

$mdp = trim($_POST['mot_de_passe'] ?? '');

$souvenir = isset($_POST['souvenir']);

/* ── Verification champs ─────────────────────── */

if (empty($email) || empty($mdp)) {

    $_SESSION['erreur_connexion'] =
        "Veuillez remplir tous les champs.";

    header('Location: connexion.php');

    exit;
}

/* ── Recherche utilisateur ───────────────────── */

$stmt = $pdo->prepare(
    "SELECT id,
            prenom,
            email,
            mot_de_passe,
            tentatives_connexion,
            compte_bloque_jusqua
     FROM utilisateurs
     WHERE email = :email
     LIMIT 1"
);

$stmt->execute([
    ':email' => $email
]);

$utilisateur = $stmt->fetch();

/* ── Verification blocage ───────────────────── */

if (
    $utilisateur &&
    !empty($utilisateur['compte_bloque_jusqua']) &&
    strtotime($utilisateur['compte_bloque_jusqua']) > time()
) {

    $_SESSION['erreur_connexion'] =
        "Compte bloque temporairement. Reessayez plus tard.";

    header('Location: connexion.php');

    exit;
}

/* ── Verification mot de passe ───────────────── */

$hash_test = $utilisateur
    ? $utilisateur['mot_de_passe']
    : '';

$mdp_correct = password_verify($mdp, $hash_test);

/* ── Mauvaise connexion ─────────────────────── */

if (!$utilisateur || !$mdp_correct) {

    if ($utilisateur) {

        $tentatives =
            $utilisateur['tentatives_connexion'] + 1;

        $restantes = 5 - $tentatives;

        if ($tentatives >= 5) {

            $blocage = date(
                'Y-m-d H:i:s',
                strtotime('+15 minutes')
            );

            $stmt_block = $pdo->prepare(
                "UPDATE utilisateurs
                 SET tentatives_connexion = 0,
                     compte_bloque_jusqua = :blocage
                 WHERE id = :id"
            );

            $stmt_block->execute([

                ':blocage' => $blocage,

                ':id' => $utilisateur['id']

            ]);

            $_SESSION['erreur_connexion'] =
                "Compte bloque pendant 15 minutes.";

        } else {

            $stmt_try = $pdo->prepare(
                "UPDATE utilisateurs
                 SET tentatives_connexion = :tentatives
                 WHERE id = :id"
            );

            $stmt_try->execute([

                ':tentatives' => $tentatives,

                ':id' => $utilisateur['id']

            ]);

            $_SESSION['erreur_connexion'] =
                "Email ou mot de passe incorrect. "
                . "Il vous reste "
                . $restantes
                . " tentative(s).";
        }

    } else {

        $_SESSION['erreur_connexion'] =
            "Email ou mot de passe incorrect.";
    }

    $_SESSION['old_email_connexion'] = $email;

    header('Location: connexion.php');

    exit;
}

/* ── Connexion reussie ───────────────────────── */

session_regenerate_id(true);

/* RESET tentatives */

$stmt_reset = $pdo->prepare(
    "UPDATE utilisateurs
     SET tentatives_connexion = 0,
         compte_bloque_jusqua = NULL
     WHERE id = :id"
);

$stmt_reset->execute([

    ':id' => $utilisateur['id']

]);

/* SESSION */

$_SESSION['utilisateur_id'] =
    $utilisateur['id'];

$_SESSION['utilisateur_prenom'] =
    $utilisateur['prenom'];

$_SESSION['utilisateur_email'] =
    $utilisateur['email'];

/* ── COOKIE SOUVENIR ─────────────────────────── */

if ($souvenir) {

    setcookie(

        'souvenir_utilisateur',

        $utilisateur['id'],

        time() + (30 * 24 * 60 * 60),

        "/"

    );
}

/* REDIRECTION */

header('Location: accueil.php');

exit;
