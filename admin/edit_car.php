<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: cars.php'); exit; }

// 1. Загружаем текущие данные машины
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch();

// 2. Обработка сохранения
if (isset($_POST['update_car'])) {
    $sql = "UPDATE cars SET brand=?, model=?, year=?, price=?, mileage=?, description=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['brand'], $_POST['model'], $_POST['year'], 
        $_POST['price'], $_POST['mileage'], $_POST['description'], $id
    ]);
    
    // Если выбрали новое фото — обрабатываем его (логика как в добавлении)
    if ($_FILES['image']['name']) {
        // ... код загрузки файла ...
        // UPDATE cars SET image = ? WHERE id = ?
    }
    
    header("Location: cars.php?success=updated");
    exit;
}
?>

<div class="container mt-4 w-50">
    <h2>Редактировать: <?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="brand" value="<?= htmlspecialchars($car['brand']) ?>" class="form-control mb-2">
        <input type="text" name="model" value="<?= htmlspecialchars($car['model']) ?>" class="form-control mb-2">
        <input type="number" name="year" value="<?= $car['year'] ?>" class="form-control mb-2">
        <input type="number" name="price" value="<?= $car['price'] ?>" class="form-control mb-2">
        <button name="update_car" class="btn btn-primary">Сохранить изменения</button>
        <a href="cars.php" class="btn btn-secondary">Отмена</a>
    </form>
</div>