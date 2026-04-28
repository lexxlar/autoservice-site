<?php
// ============================================================
// ВСЯ ЛОГИКА — ДО ЛЮБОГО ВЫВОДА HTML
// header() работает только если ничего не было выведено
// ============================================================
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$id) { header('Location: cars.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch();
if (!$car) { header('Location: cars.php'); exit; }

$error = '';

if (isset($_POST['update_car'])) {

    $brand       = trim($_POST['brand']);
    $model       = trim($_POST['model']);
    $year        = (int)$_POST['year'];
    $price       = (int)$_POST['price'];
    $mileage     = (int)$_POST['mileage'];
    $description = trim($_POST['description']);
    $imageName   = $car['image'];

    if (!empty($_FILES['image']['name'])) {
        $uploadDir    = $_SERVER['DOCUMENT_ROOT'] . '/uploads/cars/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            $error = 'Неверный формат файла. Допустимы: JPG, PNG, WEBP, GIF.';
        } else {
            if ($car['image'] && file_exists($uploadDir . $car['image'])) {
                unlink($uploadDir . $car['image']);
            }
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName)) {
                $error = 'Ошибка при загрузке фото.';
            }
        }
    }

    if (!$error) {
        $stmt = $pdo->prepare("UPDATE cars SET brand=?, model=?, year=?, price=?, mileage=?, description=?, image=? WHERE id=?");
        $stmt->execute([$brand, $model, $year, $price, $mileage, $description, $imageName, $id]);
        header("Location: cars.php?success=updated");
        exit;
    }
}

// Только после обработки POST подключаем header — он выводит HTML
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<main class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="d-flex align-items-center mb-4 gap-3">
                <a href="cars.php" class="btn btn-outline-secondary btn-sm">← Назад</a>
                <h2 class="mb-0">Редактировать: <?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Марка</label>
                        <input type="text" name="brand" class="form-control" required
                               value="<?= htmlspecialchars($car['brand']) ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Модель</label>
                        <input type="text" name="model" class="form-control" required
                               value="<?= htmlspecialchars($car['model']) ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Год выпуска</label>
                        <input type="number" name="year" class="form-control" required
                               min="1990" max="2025" value="<?= $car['year'] ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Цена (₽)</label>
                        <input type="number" name="price" class="form-control" required
                               min="0" value="<?= $car['price'] ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Пробег (км)</label>
                        <input type="number" name="mileage" class="form-control" required
                               min="0" value="<?= $car['mileage'] ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Описание</label>
                        <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($car['description'] ?? '') ?></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Текущее фото</label>
                        <div class="mb-2">
                            <img src="/uploads/cars/<?= htmlspecialchars($car['image'] ?: 'default.jpg') ?>"
                                 alt="Фото" style="height:160px; object-fit:cover; border-radius:8px;" class="border">
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Заменить фото <span class="text-muted small">(необязательно)</span></label>
                        <input type="file" name="image" class="form-control" accept="image/*"
                               onchange="previewImage(this)">
                        <img id="preview" src="#" alt="Превью"
                             style="display:none; height:160px; object-fit:cover; border-radius:8px; margin-top:10px;"
                             class="border">
                    </div>

                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" name="update_car" class="btn btn-primary">💾 Сохранить</button>
                    <a href="cars.php" class="btn btn-outline-secondary">Отмена</a>
                </div>
            </form>

        </div>
    </div>
</main>

<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>