<?php
session_start();
include_once('service/mysqlcon.php');

$userid = $_GET['u'] ?? '';
$token = $_GET['t'] ?? '';

if (!$userid || !$token) {
    $_SESSION['fp_msg'] = 'Invalid reset link.';
    header('Location: forgot_password.php');
    exit();
}

// Do not validate token yet — process on submission to avoid leakage
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="source/CSS/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title mb-3 text-success">Choose a new password</h4>
          <form method="post" action="process_reset.php">
            <input type="hidden" name="userid" value="<?php echo htmlspecialchars($userid); ?>">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="mb-3">
              <label class="form-label">New password</label>
              <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="mb-3">
              <label class="form-label">Confirm new password</label>
              <input type="password" name="confirm_password" class="form-control" required minlength="6">
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <a href="index.php">Back to login</a>
              <button class="btn btn-success" type="submit">Reset password</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
