<?php
session_start();
include_once('service/mysqlcon.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$userid = $_POST['userid'] ?? '';
$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (!$userid || !$token || !$password) {
    $_SESSION['fp_msg'] = 'Missing data.';
    header('Location: forgot_password.php');
    exit();
}

if ($password !== $confirm) {
    $_SESSION['fp_msg'] = 'Passwords do not match.';
    header('Location: reset_password.php?u=' . urlencode($userid) . '&t=' . urlencode($token));
    exit();
}

// Find unused token for this user
$token_hash = hash('sha256', $token);
$stmt = $mysqli->prepare("SELECT id, expires_at, used FROM password_resets WHERE userid = ? AND token_hash = ? LIMIT 1");
$stmt->bind_param('ss', $userid, $token_hash);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

if (!$row) {
    $_SESSION['fp_msg'] = 'Invalid or expired reset token.';
    header('Location: forgot_password.php');
    exit();
}

if ($row['used']) {
    $_SESSION['fp_msg'] = 'This reset link has already been used.';
    header('Location: forgot_password.php');
    exit();
}

if (strtotime($row['expires_at']) < time()) {
    $_SESSION['fp_msg'] = 'Reset token expired.';
    header('Location: forgot_password.php');
    exit();
}

// Update the user's password. NOTE: project stores passwords in plaintext in many places.
// We'll follow existing project behavior: update `users.password` directly. If you want hashing,
// replace the assignment below with password_hash($password, PASSWORD_DEFAULT) and update other places.

$stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE userid = ?");
$stmt->bind_param('ss', $password, $userid);
$ok = $stmt->execute();

if (!$ok) {
    $_SESSION['fp_msg'] = 'Failed to update password.';
    header('Location: forgot_password.php');
    exit();
}

// Mark this token used
$stmt = $mysqli->prepare("UPDATE password_resets SET used = 1 WHERE id = ?");
$stmt->bind_param('i', $row['id']);
$stmt->execute();

$_SESSION['fp_msg'] = 'Password updated. You may now login.';
header('Location: index.php');
exit();
