<?php
session_start();
// Подключение БД
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php'; 
$user_id = $_SESSION["user_id"] ?? null;                 // Принимаем id пользователя
$user_role = $_SESSION['role'] ?? null;                  // Принимаем его роль

// Получаем настоящее имя пользователя
if ($user_id != null) {
    $stmt = $pdo->prepare("SELECT first_name FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch();
    $user_name = $user_data['first_name'] ?? 'Пользователь';
}
// var_dump($_SESSION);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/index.php">Монарх</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/pages/catalog.php">Каталог</a>
                </li>
                </ul>
                <!-- ПРОВЕРКА РОЛИ ПОЛЬЗОВАТЕЛЯ И ВЫВОД СООТВЕТСВУЮЩЕГО МЕНЮ-->
                <?php
                if ($user_role === null) {
                    // Гость
                    echo '<ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="/login.php">Вход</a></li>
                            <li class="nav-item"><a class="nav-link" href="/register.php">Регистрация</a></li>
                        </ul>';
                } else {
                    // Авторизованный (и buyer, и admin)
                    echo '<ul class="navbar-nav">';
                    
                    // Ссылка на админку, если роль подходящая
                    if ($user_role === 'admin') {
                        echo '<li class="nav-item"><a class="nav-link text-danger" href="/admin/index.php">Панель</a></li>';
                        echo '<li class="nav-item"><a class="nav-link text-danger" href="/admin/requests.php">Заявки</a></li>';
                    }

                    // Имя и Выход
                    echo '<li class="nav-item"><a class="nav-link fw-bold" href="/user_panel.php">' . htmlspecialchars($user_name) . '</a></li>
                        <li class="nav-item"><a class="nav-link" href="/logout.php">Выход</a></li>
                        </ul>';
                }
                ?>
                
            </div>
        </div>
    </nav>
    </header>
    
