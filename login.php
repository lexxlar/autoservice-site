<?php
session_start();

require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $user = $pdo->prepare('SELECT * FROM users WHERE login = ?');
    $user->execute([$login]);
    $user = $user->fetch();
    if (!$user) {
        $error = "Неверный логин или пароль!";
    } else if(password_verify($password, $user["password"]) === false) {
        $error = "Неверный логин или пароль!";
    } else {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit;
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Вход</title>
</head>
<body>
    <!-- КОНТЕЙНЕР СО ВХОДОМ -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- ВЫВОД ОШИБКИ ПРИ НЕПРАВИЛЬНОМ НАПИСАНИИ -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div> 
                <?php endif; ?>
                <form action="" method="post">
                    <h2>Вход</h2>
                    <div class="mb-3">
                        <label for="login" class="form-label">Логин</label>
                        <input type="text" class="form-control" name="login" id="login" placeholder="Логин">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <button type="submit" class="btn btn-primary">Войти</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>