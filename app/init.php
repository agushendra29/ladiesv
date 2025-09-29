<?php

require_once('config/config.php');


// including the classes
require_once 'database/connection.php';
require_once 'classes/Object.php';
require_once 'classes/User.php';

//include the function
require_once 'functions.php';
date_default_timezone_set('Asia/Jakarta');



// makeing global objects
global $pdo;
session_start();
$obj = new Objects($pdo);
$Ouser = new User($pdo);


$idle_limit = 30 * 60; // 30 menit (dalam detik)

if (isset($_SESSION['user_id'])) {
    // Jika sudah ada last_activity dan idle lebih dari 30 menit
    if (isset($_SESSION['last_activity']) &&
        (time() - $_SESSION['last_activity'] > $idle_limit)) {

        session_unset();
        session_destroy();

        // Redirect ke login dengan pesan timeout
        header('Location: login.php?timeout=1');
        exit;
    }
    // Update waktu aktivitas terakhir
    $_SESSION['last_activity'] = time();
}








?>
