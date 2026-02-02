<?php
require_once __DIR__ . '/../functions.php';

$user = CurrentUser();


?>
<!doctype html>
<html lang="th">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CeraVe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <script>
    window.__MYSHOP_BASE_URL = "<?= BASE_URL ?>";   
    window.__MYSHOP_USER_ID = <?= (!empty($_SESSION['user']['id'])) ? (int)$_SESSION['user']['id'] : 'null' ?>;
    window.__MYSHOP_USER_ID = <?= ($user && !empty($user['id'])) ? (int)$user['id'] : 'null' ?>;
  </script>

</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark " style=" background-color: #083058ff; ">
    <div class="container">
      <a class="navbar-brand fw-bold d-flex align-items-center" href="<?= BASE_URL ?>/index.php">
        <img src="<?= BASE_URL ?>/assets/img/logopic.png" width="160px" height="28px" alt="logo">

      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php"><strong>Home</strong></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/about.php">About Us</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/products.php">Products</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/shipping.php">Checkout?</a></li>

          <?php if ($user && $user['is_admin']): ?>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/fr_product.php">Edit Product</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/order_list.php">Order list</a></li>
          <?php endif; ?>
        </ul>

        <div class="d-flex align-items-center gap-3">
          <a href="<?= BASE_URL ?>/payment.php" class="btn btn-outline-light btn-sm position-relative">
            <i class="bi bi-cart3"></i>
            <span
              id="cartCountBadge"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
              style="font-size: .65rem; min-width: 18px;">
              0
            </span>
          </a>


          <?php if ($user): ?>
            <span class="text-white">
              <?= htmlspecialchars(string: $user['first_name']) ?>
            </span>
            <a class="btn btn-outline-light btn-sm" href="<?= BASE_URL ?>/auth/logout.php">Logout</a>

          <?php else: ?>
            <a href="<?= BASE_URL ?>/auth/login.php" class="btn btn-light btn-sm">Login</a>

          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <main class="container my-4">