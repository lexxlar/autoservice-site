<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; 

$stmt = $pdo->query("SELECT * FROM cars ORDER BY created_at DESC");
$cars = $stmt->fetchAll();
?>

<main class="container mt-4">
    <h1 class="mb-4">Каталог автомобилей</h1>

    <!-- Поиск -->
    <div class="mb-4">
        <input type="text" id="search" class="form-control form-control-lg"
               placeholder="🔍 Поиск по марке или модели...">
    </div>

    <!-- Счётчик -->
    <p class="text-muted mb-3">Найдено: <strong id="resultCount">0</strong></p>

    <!-- Карточки -->
    <div class="row" id="carGrid">
        <?php if (!$cars): ?>
            <div class="alert alert-info">Машин пока нет в наличии.</div>
        <?php else: ?>
            <?php foreach ($cars as $car): ?>
                <div class="col-md-4 mb-4 car-card"
                     data-name="<?= htmlspecialchars(mb_strtolower($car['brand'] . ' ' . $car['model'])) ?>">
                    <div class="card h-100 shadow-sm">
                        <img src="/uploads/cars/<?= htmlspecialchars($car['image'] ?: 'default.jpg') ?>"
                             class="card-img-top"
                             alt="<?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>"
                             style="height: 200px; object-fit: cover;">
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
    

    <div id="noResults" class="d-none">
        <div class="alert alert-info text-center">
            <h5>🚗 Ничего не найдено</h5>
            <p class="mb-0">Попробуйте другой запрос.</p>
        </div>
    </div>
</main>

<script>
const searchInput = document.getElementById('search');
const cards = document.querySelectorAll('.car-card');
const countEl = document.getElementById('resultCount');
const noResults = document.getElementById('noResults');

function updateCount() {
    const visible = document.querySelectorAll('.car-card:not(.d-none)').length;
    countEl.textContent = visible;
    noResults.classList.toggle('d-none', visible > 0);
}

searchInput.addEventListener('input', function () {
    const query = this.value.toLowerCase().trim();
    cards.forEach(card => {
        const name = card.dataset.name;
        card.classList.toggle('d-none', query !== '' && !name.includes(query));
    });
    updateCount();
});

updateCount();
</script>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>