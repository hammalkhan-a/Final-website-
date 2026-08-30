<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/header.php';

$classes = getClasses(getDb());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentName = trim($_POST['student_name'] ?? '');
    $fatherName = trim($_POST['father_name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $classId = (int)($_POST['class_id'] ?? 0);
    $admissionDate = $_POST['admission_date'] ?? date('Y-m-d');

    if ($studentName !== '' && $classId > 0) {
        $pdo = getDb();
        $stmt = $pdo->prepare('INSERT INTO students (student_name, father_name, mobile, email, class_id, admission_date) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$studentName, $fatherName, $mobile, $email, $classId, $admissionDate]);

        $studentId = $pdo->lastInsertId();
        $className = getClassName($pdo, $classId);

        $feeStmt = $pdo->prepare('INSERT INTO fees (student_id, student_name, class_name, fee_amount, paid_amount, fee_status) VALUES (?, ?, ?, ?, ?, ?)');
        $feeStmt->execute([$studentId, $studentName, $className, 0, 0, 'Pending']);

        $resultStmt = $pdo->prepare('INSERT INTO results (student_id, student_name, class_name, total_marks, obtained_marks, grade) VALUES (?, ?, ?, ?, ?, ?)');
        $resultStmt->execute([$studentId, $studentName, $className, 0, 0, 'N/A']);

        echo '<div class="alert alert-success">Admission submitted successfully.</div>';
    } else {
        echo '<div class="alert alert-danger">Student name and class are required.</div>';
    }
}
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h4 mb-4">Admission Form</h2>
                <form method="post">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Student Name</label>
                            <input type="text" name="student_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Father's Name</label>
                            <input type="text" name="father_name" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" name="mobile" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Class</label>
                            <select name="class_id" class="form-select" required>
                                <option value="">Select Class</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= (int)$class['id'] ?>"><?= htmlspecialchars($class['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Admission Date</label>
                            <input type="date" name="admission_date" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Submit Admission</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
