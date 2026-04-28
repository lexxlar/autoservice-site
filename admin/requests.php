<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_check.php'; // Только для админа

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php'; 

// Обработка смены статуса
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int)$_GET['id'];
    $status = $_GET['status'];
    $stmt = $pdo->prepare("UPDATE requests SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header("Location: requests.php");
    exit;
}

// Получение заявок пользователей с ФИО, почтой и номером телефона
$sql = "SELECT 
            r.id, 
            r.request_date, 
            r.status, 
            r.created_at,
            u.first_name, 
            u.last_name, 
            u.email AS user_email,
            u.phone,
            c.brand, 
            c.model 
        FROM requests r
        JOIN users u ON r.user_id = u.id
        JOIN cars c ON r.car_id = c.id
        ORDER BY r.created_at DESC";

$stmt = $pdo->query($sql);
$requests = $stmt->fetchAll();

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; 
?>

<main class="container mt-4">
    <h2>Управление заявками на тест-драйв</h2>
    
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Дата визита</th>
                    <th>Клиент</th>
                    <th>Автомобиль</th>
                    <th>Статус</th>
                    <th>Дата подачи</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $req): ?>
                    <tr>
                        <td><?= $req['id'] ?></td>
                        <td><strong><?= date('d.m.Y', strtotime($req['request_date'])) ?></strong></td>
                        <td>
                            <strong><?= htmlspecialchars($req['first_name'] . ' ' . $req['last_name']) ?></strong><br>
                            
                            <small>
                                <a href="tel:<?= $req['phone'] ?>" class="text-decoration-none">📞 <?= htmlspecialchars($req['phone'] ?: 'Нет номера') ?></a>
                            </small><br>
                            
                            <small>
                                <a href="mailto:<?= $req['user_email'] ?>?subject=Тест-драйв <?= htmlspecialchars($req['brand']) ?>" class="text-muted">
                                    ✉️ <?= htmlspecialchars($req['user_email']) ?>
                                </a>
                            </small>
                        </td>
                        <td><?= htmlspecialchars($req['brand'] . ' ' . $req['model']) ?></td>
                        <td>
                            <?php if ($req['status'] == 'new'): ?>
                                <span class="badge bg-warning text-dark">Новая</span>
                            <?php elseif ($req['status'] == 'approved'): ?>
                                <span class="badge bg-success">Одобрена</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Отклонена</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d.m.Y H:i', strtotime($req['created_at'])) ?></td>
                        <td>
                            <a href="?id=<?= $req['id'] ?>&status=approved" class="btn btn-sm btn-outline-success">✅</a>
                            <a href="?id=<?= $req['id'] ?>&status=rejected" class="btn btn-sm btn-outline-danger">❌</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$requests): ?>
                    <tr><td colspan="7" class="text-center">Заявок пока нет</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>