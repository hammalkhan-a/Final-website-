<?php
require_once __DIR__ . '/auth.php';
requireTeacher();
require_once __DIR__ . '/header.php';

$pdo = getDb();
$totalStudents = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$admissions = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$todayAttendance = (int)$pdo->query("SELECT COUNT(*) FROM attendance WHERE attendance_date = CURDATE()")->fetchColumn();
$pendingFees = (int)$pdo->query("SELECT COUNT(*) FROM fees WHERE fee_status = 'Pending'")->fetchColumn();
$feesCollectedThisMonth = (float)$pdo->query("SELECT COALESCE(SUM(paid_amount),0) FROM fees WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->fetchColumn();

$classes = getClasses($pdo);
$studentCounts = [];
foreach ($classes as $class) {
    $countStmt = $pdo->prepare('SELECT COUNT(*) FROM students WHERE class_id = ?');
    $countStmt->execute([$class['id']]);
    $studentCounts[$class['name']] = (int)$countStmt->fetchColumn();
}
?>
<div class="row g-4">
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><h2 class="h6 text-muted">Total Students</h2><h3 class="fw-bold"><?= $totalStudents ?></h3></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><h2 class="h6 text-muted">Today Attendance</h2><h3 class="fw-bold"><?= $todayAttendance ?></h3></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><h2 class="h6 text-muted">Pending Fees</h2><h3 class="fw-bold"><?= $pendingFees ?></h3></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><h2 class="h6 text-muted">Fees Collected This Month</h2><h3 class="fw-bold"><?= number_format($feesCollectedThisMonth, 2) ?></h3></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><h2 class="h6 text-muted">Total Admissions</h2><h3 class="fw-bold"><?= $admissions ?></h3></div></div></div>
</div>
<div class="card shadow-sm mt-4">
    <div class="card-body">
        <h2 class="h5 mb-3">Students by Class</h2>
        <ul class="list-group">
            <?php foreach ($studentCounts as $name => $count): ?>
                <li class="list-group-item d-flex justify-content-between"><span><?= htmlspecialchars($name) ?></span><strong><?= $count ?></strong></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
