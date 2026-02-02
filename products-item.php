<?php
// ------ ส่วน PHP: ดึงข้อมูลสินค้าจากฐานข้อมูลตาม id ------

// ถ้ามี session ต้องการใช้ ก็เปิดได้ (เช่น แสดงชื่อ user ด้านบน)
// session_start();

// เรียกไฟล์เชื่อมต่อฐานข้อมูลของโปรเจกต์คุณ
// *** ปรับให้ตรงกับของจริง เช่น './config.php' หรือ './db.php' ***
require_once './config.php';  // สมมติว่ามีตัวแปร $conn แบบ mysqli
require_once './db.php';
// ถ้าไม่ได้ส่ง id มา ให้เด้งกลับหน้า index
if (!isset($_GET['id']) || !ctype_digit(text: $_GET['id'])) {
    header(header: "Location: index.php");
    exit;
}

$productId = (int)$_GET['id'];

// เตรียมคำสั่ง SQL แบบ prepared statement กัน SQL Injection
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $pdo->prepare(query: $sql);
$stmt->bindParam(param: 1, var: $productId, type: PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(mode: PDO::FETCH_ASSOC);

// ถ้าไม่พบสินค้า ให้เด้งกลับไปหน้า index (หรือจะโชว์ข้อความก็ได้)
if (!$product) {
    header(header: "Location: index.php");
    exit;
}

require_once __DIR__ . '/partials/header.php';
require_once './linkandscript.php';
?>



<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars(string: $product['name']); ?></title>
    <!-- <link rel="stylesheet" href="./assets/css/style.css"> -->
    

</head>

<body>


    <div class="container mt-5 pt-5">
        <h2 class="mb-5 pb-5">
            <a class="text-decoration-none text-body" href="./index.php">Home</a> /
            <a class="text-decoration-none text-body" href="./products.php">Products</a>
            /
            <?php echo htmlspecialchars(string: $product['name']); ?>
        </h2>

        <div class="row mt-5">
            <div class="col-md-5">
                <!-- รูปสินค้า -->
                <img src="<?php echo htmlspecialchars(string: $product['image_url'] ?? 'https://via.placeholder.com/500x400'); ?>"
                    alt="<?php echo htmlspecialchars(string: $product['name']); ?>"
                    class="img-fluid rounded shadow-sm">
            </div>

            <div class="col-lg-5 col-md-12 col-12">
                <h3 class="my-1"><?php echo htmlspecialchars(string: $product['name']); ?></h3>
                <?php echo $product['star']; ?>
                <div style="display: flex; align-items: center; gap: 16px;">
                    <h2 class="my-3 " style="color: #ff0055;">฿<?php echo number_format(num: $product['price']); ?></h2>
                    <span class="price-old my-3 "><?= number_format(num: $product['discount_price'], decimals: 0) ?></span>
                    <?php echo $product['discountlogo']; ?>
                </div>

                <div class="d-flex align-items-center gap-2 mt-3">
                    <input type="number"
                        id="quantity"
                        value="1"
                        min="1"
                        class="form-control"
                        style="max-width: 90px;">
                    <button
                        class="btn btn-primary"
                        onclick="handleAddToCart()"
                        >
                        เพิ่มลงตะกร้า
                    </button>
                    <a href="products.php" class="btn btn-outline-secondary">
                        กลับไปหน้าสินค้าทั้งหมด
                    </a>
                </div>

                <h4 class="mt-4">รายละเอียด</h4>
                <hr class="mb-2">
                <span><?php echo nl2br(string: htmlspecialchars(string: $product['description'] ?? '')); ?>
                </span>
                <h4 class="mt-4">ส่วนประกอบ</h4>
                <hr class="mb-2">
                <span><?php echo $product['component']; ?>
                    <div class="mb-5"></div>
                </span>
            </div>


        </div>
    </div>

    <script>
        function handleAddToCart() {
            const qtyInput = document.getElementById('quantity');
            let qty = parseInt(qtyInput.value, 10);
            if (isNaN(qty) || qty < 1) qty = 1;

            const product = {
                id: <?php echo (int)$product['id']; ?>,
                name: "<?php echo addslashes($product['name']); ?>",
                price: <?php echo (float)$product['price']; ?>,
                image_url:"<?php echo htmlspecialchars($product['image_url'] ?? 'https://via.placeholder.com/500x400'); ?>",
                quantity: qty
            };

            addToCart(product); // ฟังก์ชัน global จาก script.js
        }
    </script>



    <?php require_once __DIR__ . '/partials/footer.php'; ?>
</body>

</html>