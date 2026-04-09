<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php'; 

$user_id = $_SESSION["user_id"] ?? null;                 // Принимаем id пользователя
$user_role = $_SESSION['role'] ?? null;                  // Принимаем его роль

if ($user_id === null) {
    header('Location: /login.php');
    exit;
}

if ($user_role != 'admin') {
    header('Location: /index.php');
    exit;
}

?>