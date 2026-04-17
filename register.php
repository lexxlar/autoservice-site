<?php

require_once 'includes/db.php';                     // Подключаем БД к форме

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Получение данных из формы 
    $login = $_POST['login'];
    $password = $_POST['password'];
    $password_conf = $_POST['password_conf'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $telephone = $_POST['telephone'];

    if (empty($login)) {                            // Проверка на то что логин введен
        $error = "Логин обязателен"; 
    } else if (empty($password)) {                  // Проверка, что пароль введен
        $error = "Пароль обязателен";
    } else if ($password !== $password_conf) {      // Проверка на то, что пароли совпадают
        $error = "Пароли не совпадают";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
        $stmt->execute([$login]);
        $user_state = $stmt->fetch();

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $email_state = $stmt->fetch();

        if ($user_state) {
            $error = "Данный логин занят";
        } else if ($email_state) {
            $error = "Данная почта уже используется";
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);

            $sql = $pdo->prepare("INSERT INTO users (login, password, email, first_name, last_name, phone) VALUES (?, ?, ?, ?, ?, ?)");
            $sql->execute([$login, $password, $email, $first_name, $last_name, $telephone]);
            header('Location: login.php');
            exit;
        }
        
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Регистрация</title>
</head>
<body>
    <!-- КОНТЕЙНЕР С ФОРМОЙ РЕГИСТРАЦИИ -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <!-- ВЫВОД ОШИБКИ ПРИ НЕПРАВИЛЬНОМ НАПИСАНИИ -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div> 
                <?php endif; ?>

                <form action="" method="POST">
                    <br>
                    <h2>Регистрация</h2>
                    <div class="mb-3">
                        <label for="login" class="form-label">Логин</label>
                        <input type="text" class="form-control" name="login" id="login" placeholder="Логин" required value="<?= htmlspecialchars($login ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_conf" class="form-label">Подтверждение</label>
                        <input type="password" class="form-control" name="password_conf" id="password_conf" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="my.mail123@mail.ru" required value="<?= htmlspecialchars($email ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">Имя</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Иван" required value="<?= htmlspecialchars($first_name ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Фамилия</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Иванов" required value="<?= htmlspecialchars($last_name ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Номер телефона</label>
                        <input type="tel" class="form-control" name="telephone" id="telephone" placeholder="+7 (999) 999-99-99" value="<?= htmlspecialchars($telephone ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                    <div class="mt-3">
                        <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
                        <a href="index.php" class="text-muted">← На главную</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>