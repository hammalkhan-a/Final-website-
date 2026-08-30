<?php
require_once __DIR__ . '/auth.php';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Academy Portal</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="admission.php">Admission</a></li>
                <li class="nav-item"><a class="nav-link" href="fee_status.php">Fee Status</a></li>
                <li class="nav-item"><a class="nav-link" href="results.php">Results</a></li>
                <?php if (isTeacher()): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="students.php">Students</a></li>
                    <li class="nav-item"><a class="nav-link" href="attendance.php">Attendance</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_fees.php">Manage Fees</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_results.php">Manage Results</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (isTeacher()): ?>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Teacher Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
