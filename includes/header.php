<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
$user_id = $_SESSION["user_id"] ?? null;
$user_role = $_SESSION['role'] ?? null;
if ($user_id != null) {
    $stmt = $pdo->prepare("SELECT first_name FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch();
    $user_name = $user_data['first_name'] ?? 'Пользователь';
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <!--
    <nav class="navbar navbar-expand-md navbar-light">
        <div class="container-fluid">
            <a href="#" class="navbar-brand">Монарх</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span> 
            
        </div>
    </nav> -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Монарх</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Каталог</a>
                </li>
                <?php
                if ($user_role === null) {
                    echo '
                    <div class="d-flex">
                        <li class="nav-item">
                            <a class="nav-link"  href="/login.php">Вход</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register.php">Зарегистрироваться</a>
                        </li>
                    </div>
                ';
                } else if ($user_role === 'buyer'){
                    echo '
                    <li class="nav-item">
                        <a class="nav-link" href="/user_panel.php">' . htmlspecialchars($user_name) . '</a>
                    </li>
                    <li class="nav-item" style="position: absolute; right: 50px;">
                        <a class="nav-link" href="/logout.php">Выход</a>
                    </li>
                ';
                } else if ($user_role === 'admin'){
                    echo '
                    <div class="d-flex">
                        <li class="nav-item">
                            <a class="nav-link"  href="/user_panel.php">Имя пользователя</a>
                        </li>
                    </div>
                    <li class="nav-item">
                        <a class="nav-link"  href="/admin/index.php">Админ-панель</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout.php">Выход</a>
                    </li>
                ';
                }
                ?>
                </ul>
            </div>
        </div>
    </nav>
