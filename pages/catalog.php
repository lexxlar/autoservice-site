<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; 

// 1. Получаем данные
$stmt = $pdo->query("SELECT * FROM cars ORDER BY created_at DESC");
$cars = $stmt->fetchAll();
?>

<main class="container mt-4">
    <h1 class="mb-4">Каталог автомобилей</h1>
    
    <div class="row">
        <?php if (!$cars): ?>
            <div class="alert alert-info">Машин пока нет в наличии.</div>
        <?php else: ?>
            <?php foreach ($cars as $car): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="/uploads/cars/<?= $car['image'] ?: 'default.jpg' ?>" 
                            class="card-img-top" alt="car" style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h5>
                            <p class="card-text text-muted">
                                Год: <?= $car['year'] ?> | Пробег: <?= number_format($car['mileage'], 0, '', ' ') ?> км
                            </p>
                            <h4 class="text-primary mt-auto"><?= number_format($car['price'], 0, '', ' ') ?> ₽</h4>
                            <a href="car_detail.php?id=<?= $car['id'] ?>" class="btn btn-primary mt-3">Подробнее</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>