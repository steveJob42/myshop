
<?php include "./partials/header.php"; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container mt-5 pt-4">
    <div class="hero-about mb-4">
        <div class="hero-about-overlay">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold text-white mb-3">About Us</h1>
                <p class="col-md-8 fs-5 text-light">
                    เราคือบริษัท <strong>SkinHealthCare</strong><br>
                    ผู้นำเข้าและจัดจำหน่ายผลิตภัณฑ์ <strong>CeraVe</strong>
                    อย่างเป็นทางการในประเทศไทย มุ่งมั่นให้ทุกคนมีผิวที่แข็งแรง สุขภาพดี และปลอดภัย
                </p>
            </div>
        </div>
    </div>


    <!-- Company Story -->
    <div class="row mb-5">
        <div class="col-lg-6">
            <h3 class="fw-bold mb-3" style="color: #214982;">Our Story</h3>
            <p class="text-muted">
                SkinHealthCare ก่อตั้งขึ้นด้วยความตั้งใจที่จะคัดสรรผลิตภัณฑ์ดูแลผิวที่มีคุณภาพ
                ได้มาตรฐานระดับสากล และเหมาะกับสภาพผิวของคนไทย เราเชื่อว่า “ผิวที่ดีเริ่มจากพื้นฐานที่แข็งแรง”
                ซึ่งสอดคล้องกับหลักการของแบรนด์ <strong>CeraVe</strong> ที่เน้นเรื่องการฟื้นฟู Skin Barrier
                ด้วยส่วนผสม Ceramides ที่มีความจำเป็นต่อผิว
            </p>
            <p class="text-muted">
                ทีมของเราให้ความสำคัญกับคุณภาพ ความปลอดภัย และข้อมูลที่ถูกต้อง
                เพื่อให้ลูกค้าทุกคนสามารถเลือกผลิตภัณฑ์ที่เหมาะกับสภาพผิวของตัวเองได้อย่างมั่นใจ
            </p>
        </div>

        <div class="col-lg-6 text-center">
            <img src="<?= BASE_URL ?>/assets/img/best-cerave-product-indybest.jpeg"
                class="img-fluid rounded shadow-sm"
                alt="Cerave Products">
        </div>
    </div>

    <!-- Why choose us -->
    <div class="mb-5">
        <h3 class="fw-bold mb-4 " style="color: #214982;">Why Choose SkinHealthCare?</h3>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2"><i class="bi bi-patch-check-fill me-2" style="color: #214982;"></i>ตัวแทนจำหน่ายจริง</h5>
                        <p class="text-muted mb-0">
                            สินค้าทุกชิ้นรับประกันว่าของแท้ 100%
                            จากผู้จัดจำหน่ายผลิตภัณฑ์ <strong>CeraVe</strong> อย่างเป็นทางการในไทย
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2"><i class="bi bi-truck me-2" style="color: #214982;"></i>จัดส่งรวดเร็ว</h5>
                        <p class="text-muted mb-0">
                            บริการจัดส่งทุกวัน ผ่านขนส่งชั้นนำ พร้อมระบบติดตามสถานะสินค้าแบบเรียลไทม์
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2"><i class="bi bi-person-lines-fill me-2" style="color: #214982;"></i>ให้คำปรึกษาการเลือกผลิตภัณฑ์</h5>
                        <p class="text-muted mb-0">
                            ทีมงานผู้มีประสบการณ์พร้อมให้คำแนะนำการเลือกผลิตภัณฑ์ตามสภาพผิวอย่างมืออาชีพ
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mission -->
    <div class="mb-5">
        <div class="p-4 text-white rounded shadow-sm" style="background-color: #214982;">
            <h3 class="fw-bold">Our Mission</h3>
            <p class="mb-0 fs-5">
                มอบผลิตภัณฑ์ดูแลผิวคุณภาพเยี่ยม
                เพื่อให้ทุกคนสามารถมีสุขภาพผิวที่ดีอย่างยั่งยืน
                ในราคาที่เข้าถึงได้ และบริการที่จริงใจ
            </p>
        </div>
    </div>

    <!-- Contact -->
    <div class="mb-5">
        <h3 class="fw-bold mb-3" style="color: #214982;">Contact Us</h3>
        <ul class="list-unstyled fs-5 text-muted">
            <li><i class="bi bi-envelope-fill  me-2" style="color: #214982;"></i> support@skinhealthcare.co.th</li>
            <li><i class="bi bi-telephone-fill  me-2" style="color: #214982;"></i> 02-123-4567</li>
            <li><i class="bi bi-geo-alt-fill  me-2" style="color: #214982;"></i> กรุงเทพฯ ประเทศไทย</li>
        </ul>
    </div>

</div>

<?php include "./partials/footer.php"; ?>
    
</body>
</html>

