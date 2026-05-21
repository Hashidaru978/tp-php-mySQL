<?php

session_start();

/* SUPPRIMER SESSION */

$_SESSION = [];

session_destroy();

/* SUPPRIMER COOKIE SOUVENIR */

setcookie(

    'souvenir_utilisateur',

    '',

    time() - 3600,

    "/"

);

/* REDIRECTION */

header('Location: connexion.php');

exit;