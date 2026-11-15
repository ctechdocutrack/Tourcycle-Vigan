<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    $loginPage = basename(dirname($_SERVER['SCRIPT_NAME'])) === 'controller'
        ? '../index.php'
        : 'index.php';

    header("Location: $loginPage");
    exit();
}
?>