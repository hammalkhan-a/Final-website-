<?php
require_once __DIR__ . '/auth.php';
requireTeacher();
require_once __DIR__ . '/header.php';

$pdo = getDb();
$classes = getClasses($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classId = (int)($_POST['class_id'] ?? 0);
    $attendanceDate = $_POST['attendance_date'] ?? date('Y-m-d');
    $statusMap = $_POST['status'] ?? [];

    if ($classId > 0) {
        $className = getClassName($pdo, $classId);
        $stmt = $pdo->prepare('SELECT id, student_name FROM students WHERE class_id = ? ORDER BY student_name ASC');
        $stmt->execute([$classId]);
        $students = $stmt->fetchAll();

        $deleteStmt = $pdo->prepare('DELETE FROM attendance WHERE class_name = ? AND attendance_date = ?');
        $deleteStmt->execute([$className, $attendanceDate]);

        foreach ($students as $student) {
            $status = $statusMap[$student['id']] ?? 'Absent';
            $insertStmt = $pdo->prepare('INSERT INTO attendance (student_id, student_name, class_name, attendance_date, status) VALUES (?, ?, ?, ?, ?)');
            $insertStmt->execute([$student['id'], $student['student_name'], $className, $attendanceDate, $status]);
        }

        echo '<div class="alert alert-success">Attendance saved successfully.</div>';
    }
}

$selectedClassId = (int)($_GET['class_id'] ?? 0);
$selectedDate = $_GET['attendance_date'] ?? date('Y-m-d');

$students = [];
if ($selectedClassId > 0) {
    $stmt = $pdo->prepare('SELECT id, student_name FROM students WHERE class_id = ? ORDER BY student_name ASC');
    $stmt->execute([$selectedClassId]);
    $students = $stmt->fetchAll();
}

$attendanceQuery = 'SELECT id, student_id, student_name, class_name, attendance_date, status FROM attendance WHERE 1=1';
$attendanceParams = [];
if ($selectedClassId > 0) {
    $attendanceQuery .= ' AND class_name = ?';
    $attendanceParams[] = getClassName($pdo, $selectedClassId);
}
if ($selectedDate !== '') {
    $attendanceQuery .= ' AND attendance_date = ?';
    $attendanceParams[] = $selectedDate;
}
$attendanceQuery .= ' ORDER BY attendance_date DESC, id DESC';
$attendanceStmt = $pdo->prepare($attendanceQuery);
$attendanceStmt->execute($attendanceParams);
$attendanceRecords = $attendanceStmt->fetchAll();
?>
<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="h4 mb-3">Attendance</h2>
        <form method="get" class="row g-3 mb-4">
            <div class="col-md-5">
                <label class="form-label">Class</label>
                <select name="class_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= (int)$class['id'] ?>" <?= $selectedClassId === (int)$class['id'] ? 'selected' : '' ?>><?= htmlspecialchars($class['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">Attendance Date</label>
                <input type="date" name="attendance_date" class="form-control" value="<?= htmlspecialchars($selectedDate) ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Load Students</button>
            </div>
        </form>
        <?php if ($students): ?>
            <form method="post">
                <input type="hidden" name="class_id" value="<?= $selectedClassId ?>">
                <input type="hidden" name="attendance_date" value="<?= htmlspecialchars($selectedDate) ?>">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead><tr><th>Student</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['student_name']) ?></td>
                                    <td>
                                        <select name="status[<?= (int)$student['id'] ?>]" class="form-select">
                                            <option value="Present">Present</option>
                                            <option value="Absent" selected>Absent</option>
                                            <option value="Leave">Leave</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-success">Save Attendance</button>
            </form>
        <?php endif; ?>

        <div class="mt-5">
            <h3 class="h5">Attendance Records</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr><th>ID</th><th>Student</th><th>Class</th><th>Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php if ($attendanceRecords): ?>
                            <?php foreach ($attendanceRecords as $record): ?>
                                <tr>
                                    <td><?= (int)$record['student_id'] ?></td>
                                    <td><?= htmlspecialchars($record['student_name']) ?></td>
                                    <td><?= htmlspecialchars($record['class_name']) ?></td>
                                    <td><?= htmlspecialchars($record['attendance_date']) ?></td>
                                    <td><?= htmlspecialchars($record['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-muted">No attendance records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
