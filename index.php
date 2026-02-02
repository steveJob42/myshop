<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/partials/header.php';

// ดึงสินค้า
$all = $pdo->query(query: "SELECT * FROM products ORDER BY created_at DESC")->fetchAll();

$flashSales = array_filter(array: $all, callback: fn($p): bool => (int)$p['flashsale'] === 1);
$normals    = array_filter(array: $all, callback: fn($p): bool => (int)$p['flashsale'] === 0);
?>

<!-- Banner -->
<!-- <div class="p-4 p-md-5 mb-4 text-white" style=" background-color: #1b5fa3; " >
  <div class="col-md-8 px-0">
    <h1 class="display-5 fw-bold">Welcome to CeraVe</h1>
    <p class="lead">เว็บขายของตัวอย่าง คล้าย Shopee แบบย่อมๆ</p>
  </div>
</div> -->

<!-- carousel -->
<div id="carouselExampleIndicators" class="carousel slide bg-dark mb-3 " data-bs-ride="carousel">
        <div class="carousel-indicators ">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner rounded-2">
            <div class="carousel-item active" data-bs-interval="6000">
                <img src="./assets/img/cerave_bn.avif" class="d-block w-100 object-fit-fill " height="350px">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="./assets/img/Picture1.png" class="d-block w-100 object-fit-fill " height="350px">
            </div>
            <div class="carousel-item">
                <img src="./assets/img/Picture2.png" class="d-block w-100 object-fit-fill " height="350px">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
</div>


<!-- ===== FLASH SALE SECTION ===== -->
<section class="flash-sale-wrap mb-4">
  <div class="flash-sale-title">
    <span>⚡</span>
    <span>FLASH SALE</span>
  </div>

  <div class="flash-sale-row">
    <?php foreach($flashSales as $p): ?>
      <a href="products-item.php?id=<?php echo $p['id']; ?>" class="text-decoration-none">
        <div class="flash-card shadow-sm">
          <?php if (!empty($p['discount_percent'])): ?>
            <span class="discount-badge">-<?= (int)$p['discount_percent'] ?>%</span>
          <?php endif; ?>

          <div class="img-box">
            <img src="<?= htmlspecialchars(string: $p['image_url']) ?>"
                 alt="<?= htmlspecialchars(string: $p['name']) ?>">
          </div>

          <div class="flash-name" style="color: #214982;">
            <?= htmlspecialchars(string: $p['name']) ?>
          </div>

          <div class="flash-price-now">
            ฿<?= number_format(num: $p['price'],decimals: 0) ?>
          </div>

          <?php if (!empty($p['discount_price'])): ?>
            <div class="flash-price-old">
              ฿<?= number_format(num: $p['discount_price'],decimals: 0) ?>
            </div>
          <?php endif; ?>

        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- Search bar (UI เฉยๆ ยังไม่ทำ backend search) -->
<div class="row mb-3">
  <div class="col-md-6">
    <input class="form-control" placeholder="ค้นหาสินค้า...">
  </div>
</div>


<!-- ===== NORMAL PRODUCT GRID ===== -->
<section>
  <div class="row g-3">
    <?php foreach($normals as $p): ?>
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
              ฿<?= number_format(num: $p['price'],decimals: 0) ?>
            </div>

            <?php if (!empty($p['discount_price'])): ?>
              <div class="price-old">
                ฿<?= number_format(num: $p['discount_price'],decimals: 0) ?>
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



<?php require_once __DIR__ . '/partials/footer.php'; ?>
