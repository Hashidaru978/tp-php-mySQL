<?php

/**
 * Connexion PDO a MySQL
 */

/* ── Parametres connexion ───────────────────── */

$hote = 'localhost';

$base = 'auth_tp';

$user = 'root';

$pass = '';

$charset = 'utf8mb4';

/* ── DSN ────────────────────────────────────── */

$dsn = "mysql:host=$hote;dbname=$base;charset=$charset";

/* ── Options PDO ───────────────────────────── */

$options = [

    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

    PDO::ATTR_EMULATE_PREPARES => false,

];

/* ── Connexion ─────────────────────────────── */

try {

    $pdo = new PDO(

        $dsn,

        $user,

        $pass,

        $options

    );

} catch (PDOException $e) {

    die("Erreur connexion base de donnees.");

}