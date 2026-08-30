<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/header.php';

$pdo = getDb();
$search = trim($_GET['search'] ?? '');
$student = null;

if ($search !== '') {
    try {
        $stmt = $pdo->prepare('SELECT s.id, s.student_name, s.father_name, s.class_id, c.name AS class_name, r.total_marks, r.obtained_marks, r.grade FROM students s LEFT JOIN classes c ON c.id = s.class_id LEFT JOIN results r ON r.student_id = s.id WHERE s.student_name LIKE ? ORDER BY s.id DESC');
        $stmt->execute(['%' . $search . '%']);
        $students = $stmt->fetchAll();
    } catch (PDOException $e) {
        $students = [];
        $dbError = 'The database tables are not ready yet. Please run install.php once or refresh after the setup completes.';
    }
} else {
    $students = [];
}

$showNotFound = $search !== '' && empty($students) && empty($dbError ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['student_id'])) {
    $studentId = (int)$_GET['student_id'];
    $stmt = $pdo->prepare('SELECT s.id, s.student_name, s.father_name, s.class_id, c.name AS class_name, r.total_marks, r.obtained_marks, r.grade FROM students s LEFT JOIN classes c ON c.id = s.class_id LEFT JOIN results r ON r.student_id = s.id WHERE s.id = ?');
    $stmt->execute([$studentId]);
    $student = $stmt->fetch();
}
?>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h4 mb-3">Result Search</h2>
                <form method="get">
                    <label class="form-label">Student Name</label>
                    <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary mt-3">Search</button>
                </form>
                <?php if (!empty($dbError ?? '')): ?>
                    <div class="alert alert-danger mt-4"><?= htmlspecialchars($dbError) ?></div>
                <?php elseif ($showNotFound): ?>
                    <div class="alert alert-warning mt-4">Student not found.</div>
                <?php elseif (!empty($students)): ?>
                    <div class="list-group mt-4">
                        <?php foreach ($students as $row): ?>
                            <a class="list-group-item list-group-item-action" href="results.php?search=<?= urlencode($search) ?>&student_id=<?= (int)$row['id'] ?>">
                                <strong><?= htmlspecialchars($row['student_name']) ?></strong><br>
                                <small>ID: <?= (int)$row['id'] ?> | Father: <?= htmlspecialchars($row['father_name'] ?? '') ?> | Class: <?= htmlspecialchars($row['class_name'] ?? '') ?></small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <?php if ($student): ?>
                    <h2 class="h4 mb-3">Complete Result</h2>
                    <p><strong>Student ID:</strong> <?= (int)$student['id'] ?></p>
                    <p><strong>Name:</strong> <?= htmlspecialchars($student['student_name']) ?></p>
                    <p><strong>Father's Name:</strong> <?= htmlspecialchars($student['father_name'] ?? '') ?></p>
                    <p><strong>Class:</strong> <?= htmlspecialchars($student['class_name'] ?? '') ?></p>
                    <p><strong>Total Marks:</strong> <?= (int)$student['total_marks'] ?></p>
                    <p><strong>Obtained Marks:</strong> <?= (int)$student['obtained_marks'] ?></p>
                    <p><strong>Grade:</strong> <?= htmlspecialchars($student['grade'] ?? '') ?></p>
                <?php else: ?>
                    <p class="text-muted">Search for a student to view results.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
