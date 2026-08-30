<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = '127.0.0.1';
$dbName = 'brioht_db';
$dbUser = 'root';
$dbPass = '';
$pdo = null;

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    try {
        $pdo = new PDO("mysql:host={$host};dbname=mysql;charset=utf8mb4", $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS {$dbName}");
        $pdo->exec("USE {$dbName}");
    } catch (PDOException $fallbackException) {
        error_log($fallbackException->getMessage());
        if (php_sapi_name() !== 'cli') {
            die('Database connection failed. Please verify your MySQL server and run install.php.');
        }
    }
}

if ($pdo instanceof PDO) {
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS classes (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL UNIQUE)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL, role ENUM('teacher','admin') NOT NULL DEFAULT 'teacher')");
        $pdo->exec("CREATE TABLE IF NOT EXISTS students (id INT AUTO_INCREMENT PRIMARY KEY, student_name VARCHAR(100) NOT NULL, father_name VARCHAR(100) DEFAULT NULL, mobile VARCHAR(20) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, class_id INT NOT NULL, admission_date DATE NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS attendance (id INT AUTO_INCREMENT PRIMARY KEY, student_id INT NOT NULL, student_name VARCHAR(100) NOT NULL, class_name VARCHAR(100) NOT NULL, attendance_date DATE NOT NULL, status VARCHAR(20) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS fees (id INT AUTO_INCREMENT PRIMARY KEY, student_id INT NOT NULL, student_name VARCHAR(100) NOT NULL, class_name VARCHAR(100) NOT NULL, fee_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00, paid_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00, fee_status VARCHAR(20) NOT NULL DEFAULT 'Pending', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS results (id INT AUTO_INCREMENT PRIMARY KEY, student_id INT NOT NULL, student_name VARCHAR(100) NOT NULL, class_name VARCHAR(100) NOT NULL, total_marks INT NOT NULL DEFAULT 0, obtained_marks INT NOT NULL DEFAULT 0, grade VARCHAR(10) DEFAULT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");

        $pdo->prepare("INSERT IGNORE INTO classes (name) VALUES ('Basic'),('Foundation One'),('Foundation Two'),('Beginner One'),('Beginner Two'),('Level One'),('Level Two'),('Level Three'),('Level Four'),('Level Five'),('Advance')")->execute();
        $passwordHash = password_hash('teacher123', PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE email = VALUES(email)")->execute(['Teacher', 'teacher@example.com', $passwordHash, 'teacher']);
    } catch (PDOException $setupException) {
        error_log($setupException->getMessage());
    }
}

function getDb(): PDO
{
    global $pdo;
    return $pdo;
}

function getClasses(PDO $pdo): array
{
    $stmt = $pdo->prepare('SELECT id, name FROM classes ORDER BY name ASC');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getClassName(PDO $pdo, $classId): string
{
    if (empty($classId)) {
        return '';
    }

    $stmt = $pdo->prepare('SELECT name FROM classes WHERE id = ?');
    $stmt->execute([$classId]);
    $row = $stmt->fetch();
    return $row['name'] ?? '';
}
