<?php
session_start();
include_once('service/mysqlcon.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: forgot_password.php');
    exit();
}

$identifier = trim($_POST['identifier'] ?? '');
if ($identifier === '') {
    $_SESSION['fp_msg'] = 'Please provide your email or user id.';
    header('Location: forgot_password.php');
    exit();
}

// Try to find the user by userid or email
$stmt = $mysqli->prepare("SELECT userid, password, usertype FROM users WHERE userid = ? OR userid IN (SELECT id FROM students WHERE email = ?) LIMIT 1");
$stmt->bind_param('ss', $identifier, $identifier);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

if (!$user) {
    // Do not reveal whether the account exists
    $_SESSION['fp_msg'] = 'If an account exists we sent (or will send) an email with reset instructions.';
    header('Location: forgot_password.php');
    exit();
}

$userid = $user['userid'];

// Rate limit: maximum 5 active (unused + not expired) requests per hour
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM password_resets WHERE userid = ? AND used = 0 AND expires_at > NOW() ");
$stmt->bind_param('s', $userid);
$stmt->execute();
$stmt->bind_result($cnt);
$stmt->fetch();
$stmt->close();

if ($cnt >= 5) {
    $_SESSION['fp_msg'] = 'Reset limit reached. Please check your email or try again later.';
    header('Location: forgot_password.php');
    exit();
}

// Generate a secure random token
$token = bin2hex(random_bytes(24)); // 48 chars
$token_hash = hash('sha256', $token);
$expires = date('Y-m-d H:i:s', time() + 60*60); // 1 hour

$stmt = $mysqli->prepare("INSERT INTO password_resets (userid, token_hash, expires_at, request_ip) VALUES (?, ?, ?, ?) ");
$ip = $_SERVER['REMOTE_ADDR'] ?? null;
$stmt->bind_param('ssss', $userid, $token_hash, $expires, $ip);
$ok = $stmt->execute();
$stmt->close();

// Compose reset URL
$host = $_SERVER['HTTP_HOST'];
$resetUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . "://" . $host . dirname($_SERVER['REQUEST_URI']) . "/reset_password.php?u=" . urlencode($userid) . "&t=" . $token;

// Try to send email (best-effort). Projects on XAMPP usually need SMTP setup.
$subject = 'Password reset request';
$message = "You requested a password reset. Click the link below to reset your password (valid for 1 hour):\n\n" . $resetUrl . "\n\nIf you didn't request this, ignore this message.";
$headers = 'From: noreply@' . $host . "\r\n";

$sent = false;
if (filter_var($userid, FILTER_VALIDATE_EMAIL)) {
    $sent = @mail($userid, $subject, $message, $headers);
}

$_SESSION['fp_msg'] = 'If an account exists we sent (or will send) an email with reset instructions.';
header('Location: forgot_password.php');
exit();
