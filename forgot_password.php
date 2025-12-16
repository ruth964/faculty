<?php
session_start();
// Simple forgot password request form
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Forgot Password</title>
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
          <h4 class="card-title mb-3 text-success">Forgot Password</h4>
          <p class="text-muted">Enter the email or user id associated with your account. We'll send a reset link if the account exists.</p>
          <?php if (!empty($_SESSION['fp_msg'])): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($_SESSION['fp_msg']); unset($_SESSION['fp_msg']); ?></div>
          <?php endif; ?>
          <form method="post" action="send_reset.php">
            <div class="mb-3">
              <label for="identifier" class="form-label">Email or User ID</label>
              <input id="identifier" name="identifier" class="form-control" required>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <a href="index.php">Back to login</a>
              <button class="btn btn-success" type="submit">Send reset link</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
