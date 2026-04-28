<?php
// Вся логика ДО подключения header.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

// Удаление автомобиля
if (isset($_GET['delete_id'])) {
    $id   = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("SELECT image FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    $img  = $stmt->fetchColumn();

    if ($img && file_exists($_SERVER['DOCUMENT_ROOT'] . '/uploads/cars/' . $img)) {
        unlink($_SERVER['DOCUMENT_ROOT'] . '/uploads/cars/' . $img);
    }

    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: cars.php");
    exit;
}

// Добавление автомобиля
$success = '';
$error   = '';

if (isset($_POST['add_car'])) {
    $brand       = trim($_POST['brand']);
    $model       = trim($_POST['model']);
    $year        = (int)$_POST['year'];
    $price       = (int)$_POST['price'];
    $mileage     = (int)$_POST['mileage'];
    $description = trim($_POST['description']);

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/cars/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $image     = $_FILES['image'];
    $imageName = time() . '_' . basename($image['name']);

    if (move_uploaded_file($image['tmp_name'], $uploadDir . $imageName)) {
        $stmt = $pdo->prepare("INSERT INTO cars (brand, model, year, price, mileage, description, image)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$brand, $model, $year, $price, $mileage, $description, $imageName]);
        $success = 'Автомобиль успешно добавлен!';
    } else {
        $error = 'Ошибка при загрузке фото.';
    }
}

// Список автомобилей
$stmt = $pdo->query("SELECT * FROM cars ORDER BY id DESC");
$cars = $stmt->fetchAll();

// Только теперь выводим HTML
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<main class="container mt-4">

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- ===== ФОРМА ДОБАВЛЕНИЯ ===== -->
    <div class="card shadow-sm mb-5">
        <div class="card-header">
            <h5 class="mb-0">Добавить автомобиль</h5>
        </div>
        <div class="card-body">
            <form action="cars.php" method="POST" enctype="multipart/form-data">
                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Марка</label>
                        <input type="text" name="brand" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Модель</label>
                        <input type="text" name="model" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Год</label>
                        <input type="number" name="year" class="form-control"
                               min="1990" max="2025" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Цена (₽)</label>
                        <input type="number" name="price" class="form-control" min="0" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Пробег (км)</label>
                        <input type="number" name="mileage" class="form-control" min="0" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Фото автомобиля</label>
                        <input type="file" name="image" class="form-control"
                               accept="image/*" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Описание</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                </div>

                <button type="submit" name="add_car" class="btn btn-success mt-3">
                    💾 Сохранить авто
                </button>
            </form>
        </div>
    </div>

    <!-- ===== ТАБЛИЦА АВТОМОБИЛЕЙ ===== -->
    <h5 class="mb-3">Список автомобилей</h5>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Марка</th>
                    <th>Модель</th>
                    <th>Год</th>
                    <th>Цена</th>
                    <th>Пробег</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$cars): ?>
                    <tr><td colspan="6" class="text-center text-muted">Автомобилей пока нет</td></tr>
                <?php else: ?>
                    <?php foreach ($cars as $car): ?>
                        <tr>
                            <td><?= htmlspecialchars($car['brand']) ?></td>
                            <td><?= htmlspecialchars($car['model']) ?></td>
                            <td><?= $car['year'] ?></td>
                            <td><?= number_format($car['price'], 0, '', ' ') ?> ₽</td>
                            <td><?= number_format($car['mileage'], 0, '', ' ') ?> км</td>
                            <td>
                                <a href="edit_car.php?id=<?= $car['id'] ?>"
                                   class="btn btn-warning btn-sm">✏️</a>
                                <a href="?delete_id=<?= $car['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Удалить <?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>?')">
                                   🗑️
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>