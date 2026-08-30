<?php
require_once __DIR__ . '/database.php';

function isTeacher(): bool
{
    return !empty($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'teacher';
}

function requireTeacher(): void
{
    if (!isTeacher()) {
        header('Location: login.php?message=Please login as a teacher.');
        exit;
    }
}

function loginUser(PDO $pdo, string $email, string $password): array|false
{
    $stmt = $pdo->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        return false;
    }

    if (!password_verify($password, $user['password_hash'])) {
        return false;
    }

    return $user;
}

function logoutUser(): void
{
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
