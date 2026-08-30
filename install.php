<?php
require_once __DIR__ . '/database.php';

if (!$pdo instanceof PDO) {
    echo '<h1>Installation failed</h1><p>Database connection is unavailable. Start MySQL and try again.</p>';
    exit;
}

try {
    $pdo->exec('CREATE DATABASE IF NOT EXISTS brioht_db');
    $pdo->exec('USE brioht_db');

    $pdo->exec('CREATE TABLE IF NOT EXISTS classes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE
    )');

    $defaultClasses = [
        'Basic','Foundation One','Foundation Two','Beginner One','Beginner Two','Level One','Level Two','Level Three','Level Four','Level Five','Advance'
    ];

    $stmt = $pdo->prepare('INSERT IGNORE INTO classes (name) VALUES (?)');
    foreach ($defaultClasses as $className) {
        $stmt->execute([$className]);
    }

    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM("teacher","admin") NOT NULL DEFAULT "teacher"
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_name VARCHAR(100) NOT NULL,
        father_name VARCHAR(100) DEFAULT NULL,
        mobile VARCHAR(20) DEFAULT NULL,
        email VARCHAR(100) DEFAULT NULL,
        class_id INT NOT NULL,
        admission_date DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (class_id) REFERENCES classes(id)
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS attendance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        student_name VARCHAR(100) NOT NULL,
        class_name VARCHAR(100) NOT NULL,
        attendance_date DATE NOT NULL,
        status VARCHAR(20) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id)
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS fees (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        student_name VARCHAR(100) NOT NULL,
        class_name VARCHAR(100) NOT NULL,
        fee_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        paid_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        fee_status VARCHAR(20) NOT NULL DEFAULT "Pending",
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id)
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS results (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        student_name VARCHAR(100) NOT NULL,
        class_name VARCHAR(100) NOT NULL,
        total_marks INT NOT NULL DEFAULT 0,
        obtained_marks INT NOT NULL DEFAULT 0,
        grade VARCHAR(10) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id)
    )');

    $passwordHash = password_hash('teacher123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE email = VALUES(email)');
    $stmt->execute(['Teacher', 'teacher@example.com', $passwordHash, 'teacher']);

    echo '<h1>Installation complete</h1><p>The database and default records were created successfully.</p>';
} catch (Exception $e) {
    echo '<h1>Installation failed</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>';
}
