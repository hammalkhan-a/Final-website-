<?php
require_once __DIR__ . '/auth.php';
requireTeacher();
require_once __DIR__ . '/header.php';

$pdo = getDb();
$classes = getClasses($pdo);
$selectedClassId = (int)($_GET['class_id'] ?? 0);
$students = [];

if ($selectedClassId > 0) {
    $stmt = $pdo->prepare('SELECT s.id, s.student_name, r.total_marks, r.obtained_marks, r.grade FROM students s LEFT JOIN results r ON r.student_id = s.id WHERE s.class_id = ? ORDER BY s.student_name ASC');
    $stmt->execute([$selectedClassId]);
    $students = $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = (int)($_POST['student_id'] ?? 0);
    $totalMarks = (int)($_POST['total_marks'] ?? 0);
    $obtainedMarks = (int)($_POST['obtained_marks'] ?? 0);
    $grade = trim($_POST['grade'] ?? '');
    if ($studentId > 0) {
        $pdo->prepare('UPDATE results SET total_marks = ?, obtained_marks = ?, grade = ? WHERE student_id = ?')->execute([$totalMarks, $obtainedMarks, $grade, $studentId]);
        echo '<div class="alert alert-success">Result updated.</div>';
    }
}
?>
<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="h4 mb-3">Result Management</h2>
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
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Student</th><th>Total Marks</th><th>Obtained Marks</th><th>Grade</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <form method="post">
                                    <input type="hidden" name="student_id" value="<?= (int)$student['id'] ?>">
                                    <td><?= htmlspecialchars($student['student_name']) ?></td>
                                    <td><input type="text" name="total_marks" class="form-control" value="<?= (int)($student['total_marks'] ?? 0) ?>"></td>
                                    <td><input type="text" name="obtained_marks" class="form-control" value="<?= (int)($student['obtained_marks'] ?? 0) ?>"></td>
                                    <td><input type="text" name="grade" class="form-control" value="<?= htmlspecialchars($student['grade'] ?? '') ?>"></td>
                                    <td><button type="submit" class="btn btn-sm btn-primary">Save</button></td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
