<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php'; 
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
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>