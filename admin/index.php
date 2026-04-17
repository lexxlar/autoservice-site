<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_check.php'; 
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php'; 

// Получаем статистику для дашборда
$cars_count = $pdo->query("SELECT COUNT(*) FROM cars")->fetchColumn();
$new_requests = $pdo->query("SELECT COUNT(*) FROM requests WHERE status = 'new'")->fetchColumn();
$total_requests = $pdo->query("SELECT COUNT(*) FROM requests")->fetchColumn();
?>

<div class="container mt-5">
    <h2 class="mb-4">Панель управления автосалоном</h2>
    
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Автомобили</h5>
                    <p class="display-4"><?= $cars_count ?></p>
                    <a href="cars.php" class="text-white border-bottom pb-1 text-decoration-none">Управлять парком →</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <h5 class="card-title">Новые заявки</h5>
                    <p class="display-4"><?= $new_requests ?></p>
                    <a href="requests.php" class="text-dark border-bottom border-dark pb-1 text-decoration-none">Просмотреть →</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Всего заявок</h5>
                    <p class="display-4"><?= $total_requests ?></p>
                    <a href="requests.php" class="text-white border-bottom pb-1 text-decoration-none">История →</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light"><h5>Быстрые действия</h5></div>
        <div class="card-body">
            <div class="d-flex gap-3">
                <a href="cars.php" class="btn btn-outline-primary">➕ Добавить новый автомобиль</a>
                <a href="/" class="btn btn-outline-secondary">🌐 Перейти на сайт</a>
            </div>
        </div>
    </div>
</div>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>