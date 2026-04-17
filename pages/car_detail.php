<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; 

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: /pages/catalog.php'); exit; }

// 1. Получаем инфу о машине
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch();

if (!$car) { echo "Машина не найдена"; exit; }

// 2. Обработка записи на тест-драйв
if (isset($_POST['book_test_drive']) && isset($_SESSION['user_id'])) {
    $date = $_POST['request_date'];
    $user_id = $_SESSION['user_id'];
    
    $ins = $pdo->prepare("INSERT INTO requests (user_id, car_id, request_date) VALUES (?, ?, ?)");
    $ins->execute([$user_id, $id, $date]);
    
    $success = "Вы успешно записаны на тест-драйв!";
}
?>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="/uploads/cars/<?= $car['image'] ?: 'default.jpg' ?>" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-6">
            <h1><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h1>
            <p class="lead text-primary">Цена: <?= number_format($car['price'], 0, '', ' ') ?> ₽</p>
            <hr>
            <p><strong>Год выпуска:</strong> <?= $car['year'] ?></p>
            <p><strong>Пробег:</strong> <?= number_format($car['mileage'], 0, '', ' ') ?> км</p>
            <p><strong>Описание:</strong><br><?= nl2br(htmlspecialchars($car['description'])) ?></p>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="card p-3 mt-4 bg-light">
                    <h5>Записаться на тест-драйв</h5>
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php else: ?>
                        <form method="POST">
                            <input type="date" name="request_date" class="form-control mb-2" required min="<?= date('Y-m-d') ?>">
                            <button name="book_test_drive" class="btn btn-success w-100">Отправить заявку</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning mt-4">
                    Чтобы записаться на тест-драйв, <a href="/login.php">войдите в систему</a>.
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>