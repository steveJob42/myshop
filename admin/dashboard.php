<?php
require_once __DIR__ . '/../functions.php';
RequireAdmin();

// นับจำนวน users / products
$totalUsers = $pdo->query(query: "SELECT COUNT(*) AS c FROM users where is_admin = 0")->fetch()['c'];
$totalProducts = $pdo->query(query: "SELECT COUNT(*) AS c FROM products")->fetch()['c'];
$lowStock = $pdo->query(query: "SELECT COUNT(*) AS c FROM products WHERE stock <= 10")->fetch()['c'];

require_once __DIR__ . '/../partials/header.php';
?>

<h3 class="mb-3">Admin Dashboard</h3>

<div class="row g-3">
  <div class="col-md-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted">จำนวนผู้ใช้งานทั้งหมด</div>
        <div class="display-6 fw-bold"><?= $totalUsers ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted">จำนวนสินค้าทั้งหมด</div>
        <div class="display-6 fw-bold"><?= $totalProducts ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted">สินค้าใกล้หมด (สินค้า ≤ 10)</div>
        <div class="display-6 fw-bold text-danger"><?= $lowStock ?></div>
      </div>
    </div>
  </div>
</div>

<hr class="my-4">

<h5>รายการสินค้า</h5>
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th><th>ชื่อ</th><th>ราคา</th><th>Stock</th>
    </tr>
  </thead>
  <tbody>
  <?php $i = 1; ?>
  <?php
    $stmt = $pdo->query(query: "SELECT * FROM products ORDER BY created_at DESC ");
    foreach($stmt as $p):
  ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= htmlspecialchars(string: $p['name']) ?></td>
      <td>฿<?= number_format(num: $p['price'],decimals: 2) ?></td>
      <td><?= $p['stock'] ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
