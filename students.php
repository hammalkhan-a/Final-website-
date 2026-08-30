<?php
require_once __DIR__ . '/auth.php';
requireTeacher();
require_once __DIR__ . '/header.php';

$pdo = getDb();
$classes = getClasses($pdo);
$search = trim($_GET['search'] ?? '');
$field = $_GET['field'] ?? 'student_name';
$classFilter = (int)($_GET['class_id'] ?? 0);

$query = 'SELECT s.id, s.student_name, s.father_name, s.mobile, s.email, s.class_id, s.admission_date, c.name AS class_name FROM students s LEFT JOIN classes c ON c.id = s.class_id WHERE 1=1';
$params = [];

if ($field === 'student_id' && $search !== '') {
    $query .= ' AND s.id = ?';
    $params[] = (int)$search;
} elseif ($field === 'student_name' && $search !== '') {
    $query .= ' AND s.student_name LIKE ?';
    $params[] = '%' . $search . '%';
} elseif ($field === 'father_name' && $search !== '') {
    $query .= ' AND s.father_name LIKE ?';
    $params[] = '%' . $search . '%';
} elseif ($field === 'mobile' && $search !== '') {
    $query .= ' AND s.mobile LIKE ?';
    $params[] = '%' . $search . '%';
} elseif ($field === 'class' && $search !== '') {
    $query .= ' AND c.name LIKE ?';
    $params[] = '%' . $search . '%';
}

if ($classFilter > 0) {
    $query .= ' AND s.class_id = ?';
    $params[] = $classFilter;
}

$query .= ' ORDER BY s.id DESC';
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$students = $stmt->fetchAll();
?>
<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="h4 mb-3">Student Search</h2>
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search By</label>
                <select name="field" class="form-select">
                    <option value="student_id" <?= $field === 'student_id' ? 'selected' : '' ?>>Student ID</option>
                    <option value="student_name" <?= $field === 'student_name' ? 'selected' : '' ?>>Student Name</option>
                    <option value="father_name" <?= $field === 'father_name' ? 'selected' : '' ?>>Father's Name</option>
                    <option value="mobile" <?= $field === 'mobile' ? 'selected' : '' ?>>Mobile Number</option>
                    <option value="class" <?= $field === 'class' ? 'selected' : '' ?>>Class</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Search Value</label>
                <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Class Filter</label>
                <select name="class_id" class="form-select">
                    <option value="0">All Classes</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= (int)$class['id'] ?>" <?= $classFilter === (int)$class['id'] ? 'selected' : '' ?>><?= htmlspecialchars($class['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>
        <div class="table-responsive mt-4">
            <table class="table table-striped">
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Father</th><th>Mobile</th><th>Class</th><th>Admission Date</th></tr>
                </thead>
                <tbody>
                    <?php if ($students): ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= (int)$student['id'] ?></td>
                                <td><?= htmlspecialchars($student['student_name']) ?></td>
                                <td><?= htmlspecialchars($student['father_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($student['mobile'] ?? '') ?></td>
                                <td><?= htmlspecialchars($student['class_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($student['admission_date'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-muted">No students found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
