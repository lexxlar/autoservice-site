<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_check.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php'; 

if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    
    // 1. (Опционально) Сначала можно удалить саму картинку из папки uploads
    $stmt = $pdo->prepare("SELECT image FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetchColumn();
    if ($img && file_exists($_SERVER['DOCUMENT_ROOT'] . '/uploads/cars/' . $img)) {
        unlink($_SERVER['DOCUMENT_ROOT'] . '/uploads/cars/' . $img);
    }

    // 2. Удаляем запись из базы
    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: cars.php"); // Перезагружаем страницу, чтобы данные обновились
    exit;
}
?>
<br>
<div class="container w-50">
    <form action="cars.php" method="post" enctype="multipart/form-data" class="card p-3 mb-5">
    <h3>Добавить автомобиль</h3>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Марка</label>
            <input type="text" name="brand" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Модель</label>
            <input type="text" name="model" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Год</label>
            <input type="text" name="year" class="form-control" required>
        </div>
        <div class="col-md-12 mb-3">
            <label>Описание</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="col-md-6 mb-3">
            <label>Цена</label>
            <input type="text" name="price" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Пробег</label>
            <input type="text" name="mileage" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Фото автомобиля</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>
    </div>
    <button type="submit" name="add_car" class="btn btn-success">Сохранить авто</button>
</form>
</div>


<?php
// Проверка нажатия кнопки
if (isset($_POST['add_car'])) {
    
    // Сбор данных
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = (int)$_POST['year'];
    $price = (int)$_POST['price'];
    $mileage = (int)$_POST['mileage'];
    $description = $_POST['description'];

    // Работа с файлом
    $image = $_FILES['image'];
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/cars/'; 
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Создание папки, если ее нет
    }

    $imageName = time() . '_' . $image['name'];
    $uploadFile = $uploadDir . $imageName;

    if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
        // запись автомобиля в бд  
        $sql = "INSERT INTO cars (brand, model, year, price, mileage, description, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        // Обработка запроса
        $stmt->execute([$brand, $model, $year, $price, $mileage, $description, $imageName]);

        echo '<div class="alert alert-success">Автомобиль успешно добавлен!</div>';
    } else {
        echo '<div class="alert alert-danger">Ошибка при загрузке фото.</div>';
    }
}
?>

<?php
// Получение списка автомобилей из БД
$stmt = $pdo->query("SELECT * FROM cars ORDER BY id DESC");
$cars = $stmt->fetchAll(); 
?>

<div class="container w-50">
    <table class="table table-striped table-hover mt-4">
        <thead>
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
            <?php foreach ($cars as $car): ?>
                <tr>
                    <td><?= htmlspecialchars($car['brand']) ?></td>
                    <td><?= htmlspecialchars($car['model']) ?></td>
                    <td><?= $car['year'] ?></td>
                    <td><?= number_format($car['price'], 0, '', ' ') ?> ₽</td>
                    <td><?= number_format($car['mileage'], 0, '', ' ') ?> км</td>
                    <td>
                        <a href="?delete_id=<?= $car['id'] ?>" class="btn btn-danger btn-sm">Удалить</a>
                        <a href="edit_car.php?id=<?= $car['id'] ?>" class="btn btn-warning btn-sm">Редактировать</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>