<?php
session_start();
$_SESSION = []; // Очистка массива данных сессии
session_destroy(); // Удаление файла сессии
header('Location: index.php');
exit;

?>