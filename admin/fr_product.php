<?php
require_once __DIR__ . '/../functions.php';
RequireAdmin();

$flash = $_GET['flash'] ?? null;

// ลบสินค้า
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
  $id = (int)($_POST['id'] ?? 0);

  // หา image_url เพื่อจะลบไฟล์รูป (เฉพาะกรณีเป็นไฟล์ในโปรเจกต์)
  $stmt = $pdo->prepare("SELECT image_url FROM products WHERE id = ?");
  $stmt->execute([$id]);
  $row = $stmt->fetch();

  $del = $pdo->prepare("DELETE FROM products WHERE id = ?");
  $del->execute([$id]);

  // ลบไฟล์รูป (optional) เฉพาะถ้าอยู่ใน /assets/img/products/
  if ($row && !empty($row['image_url'])) {
    $img = $row['image_url'];
    if (str_starts_with($img, './assets/img')) {
      $path = __DIR__ . '/../' . ltrim(substr($img, 2), '/'); // แปลง ./ เป็น path จริง
      if (is_file($path)) @unlink($path);
    }
  }

  header("Location: " . BASE_URL . "/admin/fr_product.php?deleted=1");
  exit;
}



// โหลดรายการสินค้า
$sql = "SELECT * FROM products";
$params = [];
if ($flash === "1" || $flash === "0") {
  $sql .= " WHERE flashsale = ?";
  $params[] = (int)$flash;
}
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

require_once __DIR__ . '/../partials/header.php';
?>
<?php
function productImageSrc(string $imageUrl): string {
    $imageUrl = trim($imageUrl);
    if ($imageUrl === '') return '';

    // ถ้าเก็บแบบ ./assets/...
    if (strpos($imageUrl, './') === 0) {
        return BASE_URL . '/' . ltrim(substr($imageUrl, 2), '/');
    }

    // ถ้าเก็บแบบ /assets/...
    if (strpos($imageUrl, '/assets/') === 0) {
        return BASE_URL . $imageUrl;
    }

    // ถ้าเป็น URL เต็ม https://...
    if (preg_match('/^https?:\/\//i', $imageUrl)) {
        return $imageUrl;
    }

    // ถ้าเป็น assets/... (ไม่มี ./)
    if (strpos($imageUrl, 'assets/') === 0) {
        return BASE_URL . '/' . $imageUrl;
    }

    // fallback
    return $imageUrl;
}
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h3 class="mb-0">จัดการสินค้า</h3>
    <div class="text-muted">เพิ่ม/แก้ไข/ลบสินค้าในตาราง products</div>
  </div>
  <a class="btn btn-primary" href="<?= BASE_URL ?>/admin/product_form.php">
    <i class="bi bi-plus-circle"></i> เพิ่มสินค้า
  </a>
</div>

<?php if (isset($_GET['saved'])): ?>
  <div class="alert alert-success">บันทึกข้อมูลสำเร็จ</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
  <div class="alert alert-warning">ลบสินค้าเรียบร้อย</div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body">

    <div class="d-flex gap-2 mb-3">
      <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_URL ?>/admin/fr_product.php">ทั้งหมด</a>
      <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_URL ?>/admin/fr_product.php?flash=1">Flash Sale</a>
      <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_URL ?>/admin/fr_product.php?flash=0">ปกติ</a>
    </div>

    <div class="table-responsive">
      <table class="table align-middle table-hover">
        <thead class="table-light">
          <tr>
            <th style="width:80px;">รูป</th>
            <th>ชื่อสินค้า</th>
            <th style="width:120px;">ราคา</th>
            <th style="width:90px;">Stock</th>
            <th style="width:100px;">Flash</th>
            <th style="width:170px;">จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $p): ?>
            <tr>
              <td>
                <!-- <?php if (!empty($p['image_url'])): ?>
                  <img src="<?= htmlspecialchars($p['image_url']) ?>" style="width:56px;height:56px;object-fit:cover;border-radius:10px;">
                <?php else: ?>
                  <div class="text-muted">-</div>
                <?php endif; ?> -->

                <?php
                $src = !empty($p['image_url']) ? productImageSrc($p['image_url']) : '';
                ?>
                <?php if ($src): ?>
                  <img src="<?= htmlspecialchars($src) ?>" style="width:56px;height:56px;object-fit:cover;border-radius:10px;">
                <?php else: ?>
                  <div class="text-muted">-</div>
                <?php endif; ?>


              </td>
              <td>
                <div class="fw-semibold"><?= htmlspecialchars($p['name']) ?></div>
                <div class="text-muted small">ID: <?= (int)$p['id'] ?></div>
              </td>
              <td>฿<?= number_format((float)$p['price'], 2) ?></td>
              <td class="<?= ((int)$p['stock'] <= 5 ? 'text-danger fw-bold' : '') ?>">
                <?= (int)$p['stock'] ?>
              </td>
              <td>
                <?= ((int)$p['flashsale'] === 1) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?>
              </td>
              <td>
                <div class="d-flex gap-2">
                  <a class="btn btn-outline-primary btn-sm"
                    href="<?= BASE_URL ?>/admin/product_form.php?id=<?= (int)$p['id'] ?>">
                    <i class="bi bi-pencil-square"></i> แก้ไข
                  </a>

                  <form method="post" onsubmit="return confirm('ยืนยันลบสินค้า: <?= htmlspecialchars($p['name']) ?> ?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                    <button class="btn btn-outline-danger btn-sm" type="submit">
                      <i class="bi bi-trash"></i> ลบ
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>

          <?php if (empty($products)): ?>
            <tr>
              <td colspan="6" class="text-center text-muted py-4">ไม่พบสินค้า</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>