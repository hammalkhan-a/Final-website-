<?php
require_once __DIR__ . '/auth.php';
requireTeacher();
require_once __DIR__ . '/header.php';

$pdo = getDb();
$classes = getClasses($pdo);
$selectedClassId = (int)($_GET['class_id'] ?? 0);
$students = [];

if ($selectedClassId > 0) {
    $stmt = $pdo->prepare('SELECT s.id, s.student_name, COALESCE(f.fee_amount, 0) AS fee_amount, COALESCE(f.paid_amount, 0) AS paid_amount, COALESCE(f.fee_status, "Pending") AS fee_status FROM students s LEFT JOIN fees f ON f.student_id = s.id WHERE s.class_id = ? ORDER BY s.student_name ASC');
    $stmt->execute([$selectedClassId]);
    $students = $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentIds = $_POST['student_id'] ?? [];
    if (!empty($studentIds)) {
        foreach ($studentIds as $index => $studentId) {
            $studentId = (int)$studentId;
            $feeAmount = (float)($_POST['fee_amount'][$index] ?? 0);
            $paidAmount = (float)($_POST['paid_amount'][$index] ?? 0);
            $feeStatus = trim($_POST['fee_status'][$index] ?? 'Pending');

            if ($studentId > 0) {
                $existing = $pdo->prepare('SELECT id FROM fees WHERE student_id = ?');
                $existing->execute([$studentId]);

                if ($existing->fetch()) {
                    $pdo->prepare('UPDATE fees SET fee_amount = ?, paid_amount = ?, fee_status = ? WHERE student_id = ?')->execute([$feeAmount, $paidAmount, $feeStatus, $studentId]);
                } else {
                    $studentInfo = $pdo->prepare('SELECT student_name, class_id FROM students WHERE id = ?');
                    $studentInfo->execute([$studentId]);
                    $studentRow = $studentInfo->fetch();
                    if ($studentRow) {
                        $className = getClassName($pdo, $studentRow['class_id']);
                        $pdo->prepare('INSERT INTO fees (student_id, student_name, class_name, fee_amount, paid_amount, fee_status) VALUES (?, ?, ?, ?, ?, ?)')->execute([$studentId, $studentRow['student_name'], $className, $feeAmount, $paidAmount, $feeStatus]);
                    }
                }
            }
        }
        echo '<div class="alert alert-success">Fees updated.</div>';
    }
}
?>
<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="h4 mb-3">Fee Management</h2>
        <form method="get" class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Class</label>
                <select name="class_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= (int)$class['id'] ?>" <?= $selectedClassId === (int)$class['id'] ? 'selected' : '' ?>><?= htmlspecialchars($class['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
        <?php if ($students): ?>
            <form method="post">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead><tr><th>Student</th><th>Fee Amount</th><th>Paid Amount</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php foreach ($students as $index => $student): ?>
                                <tr>
                                    <input type="hidden" name="student_id[]" value="<?= (int)$student['id'] ?>">
                                    <td><?= htmlspecialchars($student['student_name']) ?></td>
                                    <td><input type="number" step="0.01" name="fee_amount[]" class="form-control" value="<?= number_format((float)($student['fee_amount'] ?? 0), 2, '.', '') ?>"></td>
                                    <td><input type="number" step="0.01" name="paid_amount[]" class="form-control" value="<?= number_format((float)($student['paid_amount'] ?? 0), 2, '.', '') ?>"></td>
                                    <td>
                                        <select name="fee_status[]" class="form-select">
                                            <option value="Pending" <?= ($student['fee_status'] ?? '') === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Paid" <?= ($student['fee_status'] ?? '') === 'Paid' ? 'selected' : '' ?>>Paid</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary">Save Fees</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
