<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; 

// Если не залогинен — выкидываем
if (!isset($_SESSION['user_id'])) { header('Location: /login.php'); exit; }

$user_id = $_SESSION['user_id'];
$message = "";

// 1. Обработка обновления данных
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fn = $_POST['first_name'];
    $ln = $_POST['last_name'];
    $ph = $_POST['phone'];
    $em = $_POST['email'];

    $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ?, email = ? WHERE id = ?");
    if ($stmt->execute([$fn, $ln, $ph, $em, $user_id])) {
        $message = "<div class='alert alert-success'>Данные успешно обновлены!</div>";
    }
}

// 2. Получаем актуальные данные пользователя
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Мой профиль</h4>
                </div>
                <div class="card-body">
                    <?= $message ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Имя</label>
                            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Фамилия</label>
                            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Телефон</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="+7 (900) 000-00-00">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Сохранить изменения</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>