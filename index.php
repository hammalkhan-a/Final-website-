<?php require_once __DIR__ . '/header.php'; ?>
<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h3 mb-3">Academy Class Management System</h1>
                <p class="text-muted">Manage and check admissions, classes, attendance, fees, and results of students bopth for teacher and parents .</p>
                <a href="admission.php" class="btn btn-primary">New Admission</a>
                <a href="login.php" class="btn btn-outline-primary ms-2">Teacher Login</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5">Quick Links</h2>
                <ul class="list-unstyled mb-0">
                    <li><a href="fee_status.php">Check Fee Status</a></li>
                    <li><a href="results.php">Check Results</a></li>
                    <li><a href="attendance.php"> Attendance</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
