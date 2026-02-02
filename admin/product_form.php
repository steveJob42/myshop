<?php
require_once __DIR__ . '/../functions.php';
RequireAdmin();

function buildDiscountLogo(?int $percent): string
{
    if ($percent === null || $percent <= 0) return '';
    return '<span class="discount-badge-item my-3 ">-' . (int)$percent . '%</span>';
}

function buildStarHtml(float $rating): string
{
    $rating = max(0, min(5, $rating));
    $full = (int)floor($rating);
    $empty = 5 - $full;

    $html = '<div class="p-0">';
    for ($i = 0; $i < $full; $i++) {
        $html .= '<span class="text-warning"><i class="bi bi-star-fill"></i></span>';
    }
    for ($i = 0; $i < $empty; $i++) {
        $html .= '<span class="text-muted"><i class="bi bi-star"></i></span>';
    }
    $html .= '<span class="ms-2 text-dark">' . number_format($rating, 1) . '</span>';
    $html .= '</div>';
    return $html;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;

// default model
$product = [
    'name' => '',
    'price' => '',
    'discount_price' => '',
    'discount_percent' => '',
    'flashsale' => 0,
    'stock' => 0,
    'rating' => 0.0,
    'image_url' => '',
    'description' => '',
    'component' => ''
];

if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $db = $stmt->fetch();
    if (!$db) {
        header("Location: " . BASE_URL . "/admin/fr_product.php");
        exit;
    }
    $product = array_merge($product, $db);
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $discount_price = ($_POST['discount_price'] ?? '') !== '' ? (float)$_POST['discount_price'] : null;
    $discount_percent = ($_POST['discount_percent'] ?? '') !== '' ? (int)$_POST['discount_percent'] : null;
    $flashsale = isset($_POST['flashsale']) ? 1 : 0;
    $stock = (int)($_POST['stock'] ?? 0);
    $rating = (float)($_POST['rating'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $component = trim($_POST['component'] ?? '');
    $image_url = trim($_POST['image_url'] ?? ''); // optional ถ้าไม่ upload

    // validation
    if ($name === '') $errors[] = "กรุณากรอกชื่อสินค้า";
    if ($price <= 0) $errors[] = "ราคาต้องมากกว่า 0";
    if ($stock < 0) $errors[] = "Stock ต้องไม่ติดลบ";
    if ($rating < 0 || $rating > 5) $errors[] = "Rating ต้องอยู่ระหว่าง 0 - 5";
    if ($discount_percent !== null && ($discount_percent < 0 || $discount_percent > 99)) {
        $errors[] = "Discount Percent ต้องอยู่ระหว่าง 0 - 99";
    }

    // upload image (optional)
    $uploadedPath = null;
    if (!empty($_FILES['image_file']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) {
            $errors[] = "ไฟล์รูปต้องเป็น jpg, jpeg, png, webp เท่านั้น";
        } else {
            $dir = __DIR__ . '/../assets/img/products/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $newName = 'p_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $target = $dir . $newName;

            if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $target)) {
                $errors[] = "อัปโหลดรูปไม่สำเร็จ";
            } else {
                $uploadedPath = './assets/img/products/' . $newName; // เก็บเป็น path แบบเดียวกับของเดิม
            }
        }
    }

    if (empty($errors)) {
        $finalImage = $uploadedPath ?: ($image_url !== '' ? $image_url : ($product['image_url'] ?? ''));

        // auto HTML fields
        $discountlogo = buildDiscountLogo($discount_percent);
        $star = buildStarHtml($rating);

        if ($isEdit) {
            $sql = "UPDATE products
                    SET name = ?, price = ?, image_url = ?, description = ?, component = ?,
                        rating = ?, discount_price = ?, discount_percent = ?, flashsale = ?, stock = ?,
                        discountlogo = ?, star = ?
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $name,
                $price,
                $finalImage,
                $description,
                $component,
                $rating,
                $discount_price,
                $discount_percent,
                $flashsale,
                $stock,
                $discountlogo,
                $star,
                $id
            ]);
        } else {
            $sql = "INSERT INTO products
                    (name, price, image_url, description, component, rating, discount_price, discount_percent, flashsale, stock, discountlogo, star)
                    VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $name,
                $price,
                $finalImage,
                $description,
                $component,
                $rating,
                $discount_price,
                $discount_percent,
                $flashsale,
                $stock,
                $discountlogo,
                $star
            ]);
        }

        header("Location: " . BASE_URL . "/admin/fr_product.php?saved=1");
        exit;
    }

    // keep form values
    $product = array_merge($product, [
        'name' => $name,
        'price' => $price,
        'discount_price' => $discount_price,
        'discount_percent' => $discount_percent,
        'flashsale' => $flashsale,
        'stock' => $stock,
        'rating' => $rating,
        'image_url' => $image_url,
        'description' => $description,
        'component' => $component,
    ]);
}

require_once __DIR__ . '/../partials/header.php';

function productImageSrc(string $imageUrl): string
{
    $imageUrl = trim($imageUrl);
    if ($imageUrl === '') return '';

    // ถ้าเป็น URL เต็ม
    if (preg_match('/^https?:\/\//i', $imageUrl)) {
        return $imageUrl;
    }

    // ถ้าเก็บแบบ ./assets/...
    if (strpos($imageUrl, './') === 0) {
        return BASE_URL . '/' . ltrim(substr($imageUrl, 2), '/'); // ตัด "./" ออก
    }

    // ถ้าเก็บแบบ /assets/...
    if (strpos($imageUrl, '/assets/') === 0) {
        return BASE_URL . $imageUrl;
    }

    // ถ้าเก็บแบบ assets/...
    if (strpos($imageUrl, 'assets/') === 0) {
        return BASE_URL . '/' . $imageUrl;
    }

    // fallback
    return BASE_URL . '/' . ltrim($imageUrl, '/');
}

?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h3 class="mb-0"><?= $isEdit ? 'แก้ไขสินค้า' : 'เพิ่มสินค้า' ?></h3>
        <div class="text-muted">ฟอร์มนี้จะบันทึกลงตาราง products</div>
    </div>
    <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/admin/fr_product.php">
        <i class="bi bi-arrow-left"></i> กลับไปหน้ารายการ
    </a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <div class="fw-bold mb-1">พบข้อผิดพลาด</div>
        <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">

        <form method="post" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">ชื่อสินค้า</label>
                    <input class="form-control" name="name" required
                        value="<?= htmlspecialchars($product['name'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">ราคา (Price)</label>
                    <input class="form-control" type="number" step="0.01" name="price" required
                        value="<?= htmlspecialchars((string)($product['price'] ?? '')) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">ราคาก่อนลด (Discount Price) <span class="text-muted">(optional)</span></label>
                    <input class="form-control" type="number" step="0.01" name="discount_price"
                        value="<?= htmlspecialchars((string)($product['discount_price'] ?? '')) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">ส่วนลด % <span class="text-muted">(optional)</span></label>
                    <input class="form-control" type="number" name="discount_percent" min="0" max="99"
                        value="<?= htmlspecialchars((string)($product['discount_percent'] ?? '')) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Stock</label>
                    <input class="form-control" type="number" name="stock" min="0"
                        value="<?= htmlspecialchars((string)($product['stock'] ?? 0)) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Rating (0 - 5)</label>
                    <select class="form-select" name="rating">
                        <?php
                        $ratings = [0, 1, 2, 3, 4, 5, 3.5, 4.5];
                        $current = (float)($product['rating'] ?? 0);
                        foreach ($ratings as $r):
                            $sel = ((float)$r === $current) ? 'selected' : '';
                        ?>
                            <option value="<?= $r ?>" <?= $sel ?>><?= number_format((float)$r, 1) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="flashsale" id="flashsale"
                            <?= ((int)($product['flashsale'] ?? 0) === 1) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="flashsale">Flash Sale</label>
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="form-label">รูปสินค้า</label>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input class="form-control" type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp">
                            <div class="form-text">ถ้าอัปโหลดรูป จะใช้รูปนี้แทน image_url</div>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" name="image_url"
                                placeholder="หรือใส่ URL/Path เช่น ./assets/img/1.jpeg (optional)"
                                value="<?= htmlspecialchars($product['image_url'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- <?php if (!empty($product['image_url'])): ?>
            <div class="mt-2">
              <div class="text-muted small">Preview</div>
              <img src="<?= htmlspecialchars($product['image_url']) ?>" style="max-width:180px;border-radius:12px;object-fit:cover;">
            </div>
          <?php endif; ?> -->
          
                    <?php
                    $previewSrc = !empty($product['image_url']) ? productImageSrc($product['image_url']) : '';
                    ?>
                    <?php if ($previewSrc): ?>
                        <div class="mt-2">
                            <div class="text-muted small">Preview</div>
                            <img src="<?= htmlspecialchars($previewSrc) ?>"
                                style="max-width:180px;border-radius:12px;object-fit:cover;">
                        </div>
                    <?php endif; ?>

                </div>

                <div class="col-md-12">
                    <label class="form-label">รายละเอียด (Description)</label>
                    <textarea class="form-control" rows="4" name="description"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label">ส่วนประกอบ (Component)</label>
                    <textarea class="form-control" rows="4" name="component"><?= htmlspecialchars($product['component'] ?? '') ?></textarea>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-save"></i> บันทึก
                </button>
                <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/admin/fr_product.php">ยกเลิก</a>
            </div>
        </form>

    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>