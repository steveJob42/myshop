<?php
// ไฟล์นี้สมมติว่าใช้ไฟล์ functions.php และ header.php เช่นเดียวกับ index.php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/partials/header.php';

// ข้อมูลขั้นตอนการสั่งซื้อ 6 ขั้นตอน (ปรับให้ตรงกับรูปภาพ)
$steps = [
    [
        'number' => 1,
        'title'  => 'ทำเครื่องหมายในหัวข้อที่สนใจ',
        'icon'   => '💙', // ในรูปเป็นไอคอนรูปหัวใจ
        'desc'   => 'เลือกสินค้าที่ต้องการซื้อลงในตะกร้าสินค้า หรือทำเครื่องหมายในหัวข้อที่สนใจ',
    ],
    [
        'number' => 2,
        'title'  => 'เลือกสินค้าลงในตะกร้า',
        'icon'   => '🛍️', // ในรูปเป็นไอคอนรูปตะกร้าสินค้า
        'desc'   => 'ตรวจสอบสินค้าในตะกร้าและดำเนินการสั่งซื้อต่อไป',
    ],
    [
        'number' => 3,
        'title'  => 'เพิ่มข้อมูลการจัดส่ง',
        'icon'   => '✏️', // ในรูปเป็นไอคอนรูปดินสอ
        'desc'   => 'กรอกชื่อ ที่อยู่ เบอร์โทรศัพท์ และรายละเอียดการจัดส่งอื่นๆ',
    ],
    [
        'number' => 4,
        'title'  => 'การชำระเงิน',
        'icon'   => '💳', // สมมติว่าเป็นไอคอนบัตรเครดิต/เงิน
        'desc'   => 'เลือกช่องทางการชำระเงิน และทำการชำระค่าสินค้า',
    ],
    [
        'number' => 5,
        'title'  => 'บรรจุภัณฑ์',
        'icon'   => '📦', // สมมติว่าเป็นไอคอนกล่องพัสดุ
        'desc'   => 'ร้านค้าจะดำเนินการตรวจสอบคำสั่งซื้อและเตรียมบรรจุสินค้าเพื่อจัดส่ง',
    ],
    [
        'number' => 6,
        'title'  => 'นำส่งถึงมือลูกค้า',
        'icon'   => '🏡', // สมมติว่าเป็นไอคอนบ้าน/จัดส่ง
        'desc'   => 'บริษัทขนส่งจะนำส่งสินค้าไปตามที่อยู่ที่ลูกค้าได้ระบุไว้',
    ],
];

?>

<div class="container my-5">
    
    <div class="card p-4 p-md-5 mb-4 text-center" style=" background-color: #f8f9fa; border: none; " >
        <h1 class="display-6 fw-bold">ขั้นตอนการสั่งซื้อและจัดส่งผลิตภัณฑ์สำหรับผิว</h1>
    </div>

    <section class="shipping-steps-grid mb-5">
        <div class="row g-4 justify-content-center">
            <?php foreach($steps as $step): ?>
                <div class="col-6 col-md-4">
                    <div class="card h-100 text-center shadow-sm p-3 border-0">
                        <div class="card-body d-flex flex-column align-items-center">
                            
                            <div class="step-icon-box mb-3">
                                <span class="step-number"><?= $step['number'] ?></span>
                                <span class="step-emoji"><?= $step['icon'] ?></span>
                            </div>

                            <h5 class="card-title fw-bold mb-2">
                                <?= $step['title'] ?>
                            </h5>
                            
                            <p class="card-text text-muted">
                                <small><?= $step['desc'] ?></small>
                            </p>
                            
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <div class="card p-3 text-center" style="background-color: #e9f7ff; border-left: 5px solid #1b5fa3;">
        <p class="mb-0 fw-bold">การรับประกันสินค้า</p>
        <p class="mb-0 text-muted"><small>ผลิตภัณฑ์ของเราได้รับการคุ้มครองและรับประกันว่าสินค้าจะถึงมือลูกค้าในสภาพที่สมบูรณ์</small></p>
    </div>

</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

<style>
/* กำหนดสไตล์สำหรับ Step Icon Box */
.step-icon-box {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #f1f9ff; /* สีฟ้าอ่อน */
    border: 1px solid #cceeff; /* ขอบสีฟ้าอ่อน */
    margin-bottom: 15px;
    line-height: 60px;
    font-size: 1.5rem;
    color: #1b5fa3; /* สีหลักของแบรนด์ (จาก index.php) */
}

/* ตำแหน่งของตัวเลข */
.step-number {
    position: absolute;
    top: -5px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #1b5fa3; /* สีหลัก */
    color: white;
    font-size: 0.8rem;
    font-weight: bold;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    line-height: 20px;
    z-index: 10;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* จัดตำแหน่ง Emoji ให้อยู่ตรงกลาง */
.step-emoji {
    font-size: 1.5rem;
    line-height: 60px;
}

/* ปรับสี Card Title */
.shipping-steps-grid .card-title {
    color: #1b5fa3;
}
</style>