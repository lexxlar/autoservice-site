<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php'; 
$user_id   = $_SESSION["user_id"] ?? null;
$user_role = $_SESSION['role']    ?? null;

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
    <title>Автосалон Монарх</title>
    <link rel="icon" type="image/png" href="/uploads/logo-m.png">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <script src="/js/bootstrap.bundle.min.js"></script>
    <style>
        /* ── Sticky footer fix ── */
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main, .main-content {
            flex: 1 0 auto;  /* растягивает контент, прижимая footer вниз */
        }
        footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand fw-bold" href="/index.php">Монарх</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarMain" aria-controls="navbarMain"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="/pages/catalog.php">Каталог</a>
                        </li>
                    </ul>

                    <?php if ($user_role === null): ?>
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="/login.php">Вход</a></li>
                            <li class="nav-item"><a class="nav-link" href="/register.php">Регистрация</a></li>
                        </ul>
                    <?php else: ?>
                        <ul class="navbar-nav">
                            <?php if ($user_role === 'admin'): ?>
                                <li class="nav-item"><a class="nav-link text-danger" href="/admin/index.php">Панель</a></li>
                                <li class="nav-item"><a class="nav-link text-danger" href="/admin/requests.php">Заявки</a></li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link fw-bold" href="/user_panel.php"><?= htmlspecialchars($user_name) ?></a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="/logout.php">Выход</a></li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>