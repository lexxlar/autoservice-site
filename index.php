<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php'; 

// Выбираем 3 последние добавленные машины
$stmt = $pdo->query("SELECT * FROM cars ORDER BY id DESC LIMIT 3");
$latest_cars = $stmt->fetchAll();
?>

<div class="p-5 mb-4 bg-dark text-white rounded-3 shadow" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1600&q=80'); background-size: cover; background-position: center;">
    <div class="container-fluid py-5 text-center">
        <h1 class="display-5 fw-bold">Найдите автомобиль своей мечты</h1>
        <p class="fs-4">Лучшие предложения проверенных автомобилей с гарантией и тест-драйвом.</p>
        <a href="/pages/catalog.php" class="btn btn-primary btn-lg mt-3">Перейти в каталог</a>
    </div>
</div>

<div class="container">
    <div class="row text-center mb-5">
        <div class="col-md-4">
            <h3>🛡️ Гарантия</h3>
            <p class="text-muted">Все автомобили прошли юридическую и техническую проверку.</p>
        </div>
        <div class="col-md-4">
            <h3>🏎️ Тест-драйв</h3>
            <p class="text-muted">Запишитесь онлайн и проверьте авто в деле прямо сегодня.</p>
        </div>
        <div class="col-md-4">
            <h3>💰 Выгода</h3>
            <p class="text-muted">Лучшие цены в городе и гибкие условия рассрочки.</p>
        </div>
    </div>

    <h2 class="text-center mb-4">Наши новинки</h2>
    <div class="row">
        <?php foreach ($latest_cars as $car): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="/uploads/cars/<?= $car['image'] ?: 'default.jpg' ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h5>
                        <p class="card-text text-primary fw-bold"><?= number_format($car['price'], 0, '', ' ') ?> ₽</p>
                        <a href="/pages/car_detail.php?id=<?= $car['id'] ?>" class="btn btn-outline-primary w-100">Смотреть детали</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>