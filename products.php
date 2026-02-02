<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/partials/header.php';

// ดึงสินค้า
$all = $pdo->query(query: "SELECT * FROM products ORDER BY created_at DESC")->fetchAll();

$flashSales = array_filter(array: $all, callback: fn($p): bool => (int)$p['flashsale'] === 1);
$normals    = array_filter(array: $all, callback: fn($p): bool => (int)$p['flashsale'] === 0);
?>


    <div class="container mt-5 pt-5">
       
            <h2 class="mb-3 pb-4"><a class="text-decoration-none text-body" href="./index.php">Home</a> / <a class="text-decoration-none text-body" href="./products.php">Products</a></h2>
        

        <section>
            <div class="row g-3">
                <?php foreach ($flashSales as $p): ?>
                    <div class="col-6 col-md-3">
                        <div class="card product-card shadow-sm h-100 position-relative ">
                            <?php if (!empty($p['discount_percent'])): ?>
                                <span class="discount-badge">-<?= (int)$p['discount_percent'] ?>%</span>
                            <?php endif; ?>

                            <div class="p-3">
                                <img src="<?= htmlspecialchars(string: $p['image_url']) ?>"
                                    class="img-fluid w-100" alt="<?= htmlspecialchars(string: $p['name']) ?>">
                            </div>

                            <div class="card-body text-center pt-0 d-flex flex-column">
                                <div class="product-title">
                                    <?= htmlspecialchars(string: $p['name']) ?>
                                </div>

                                <div class="price-now mt-1">
                                    ฿<?= number_format(num: $p['price'], decimals: 0) ?>
                                </div>

                                <?php if (!empty($p['discount_price'])): ?>
                                    <div class="price-old">
                                        ฿<?= number_format(num: $p['discount_price'], decimals: 0) ?>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-auto pt-2 ">
                                    <a href="products-item.php?id=<?php echo $p['id']; ?>"
                                        class="btn btn-detail w-100 py-2">
                                        รายละเอียด
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php foreach ($normals as $p): ?>
                    <div class="col-6 col-md-3">
                        <div class="card product-card shadow-sm h-100 position-relative">
                            <?php if (!empty($p['discount_percent'])): ?>
                                <span class="discount-badge">-<?= (int)$p['discount_percent'] ?>%</span>
                            <?php endif; ?>

                            <div class="p-3">
                                <img src="<?= htmlspecialchars(string: $p['image_url']) ?>"
                                    class="img-fluid w-100" alt="<?= htmlspecialchars(string: $p['name']) ?>">
                            </div>

                            <div class="card-body text-center pt-0 d-flex flex-column">
                                <div class="product-title">
                                    <?= htmlspecialchars(string: $p['name']) ?>
                                </div>

                                <div class="price-now mt-1">
                                    ฿<?= number_format(num: $p['price'], decimals: 0) ?>
                                </div>

                                <?php if (!empty($p['discount_price'])): ?>
                                    <div class="price-old">
                                        ฿<?= number_format(num: $p['discount_price'], decimals: 0) ?>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-auto pt-2">
                                    <a href="products-item.php?id=<?php echo $p['id']; ?>"
                                        class="btn btn-detail w-100 py-2">
                                        รายละเอียด
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>


    </div>

    <?php include "./partials/footer.php" ?>

