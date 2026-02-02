<?php
require_once __DIR__ . '/../functions.php';
RequireAdmin();
require_once __DIR__ . '/../partials/header.php';

// ดึง order + item แบบ 2 query (เร็ว/อ่านง่าย)
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll();

$orderIds = array_map(fn($o) => (int)$o['id'], $orders);
$itemsByOrder = [];

if (!empty($orderIds)) {
    $in = implode(',', array_fill(0, count($orderIds), '?'));
    $stmt2 = $pdo->prepare("SELECT * FROM order_items WHERE order_id IN ($in) ORDER BY id ASC");
    $stmt2->execute($orderIds);
    $items = $stmt2->fetchAll();

    foreach ($items as $it) {
        $oid = (int)$it['order_id'];
        if (!isset($itemsByOrder[$oid])) $itemsByOrder[$oid] = [];
        $itemsByOrder[$oid][] = $it;
    }
}
?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h3 class="mb-0">Order List</h3>
        <div class="text-muted">รายการคำสั่งซื้อที่ชำระเงินสำเร็จ</div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <?php if (empty($orders)): ?>
            <div class="text-center text-muted py-4">ยังไม่มีรายการสั่งซื้อ</div>
        <?php else: ?>

            <div class="accordion" id="orderAccordion">
                <?php foreach ($orders as $idx => $o):
                    $oid = (int)$o['id'];
                    $items = $itemsByOrder[$oid] ?? [];
                ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= $oid ?>">
                            <button class="accordion-button <?= $idx === 0 ? '' : 'collapsed' ?>" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse<?= $oid ?>"
                                aria-expanded="<?= $idx === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $oid ?>">
                                <div class="w-100 d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    <div class="fw-bold">#<?= $oid ?> — <?= htmlspecialchars($o['full_name']) ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($o['email']) ?></div>
                                    <div class="fw-semibold text-danger">฿<?= number_format((float)$o['total_amount'], 2) ?></div>

                                    <?php
                                    $status = strtoupper(trim($o['status'] ?? ''));
                                    if ($status === 'COD') {
                                        // ✅ COD: เหลือง + ไอคอนเตือน + ข้อความ COD
                                        echo '<span class="badge bg-warning"><span class="me-1"></span>COD</span>';
                                    } else {
                                        // ✅ อื่น ๆ: แบบเดิม (paid สีเขียว)
                                        echo '<span class="badge bg-success">PAID</span>';
                                    }
                                    ?>

                                    <div class="text-muted small"><?= htmlspecialchars($o['created_at']) ?></div>
                                </div>
                            </button>
                        </h2>

                        <div id="collapse<?= $oid ?>" class="accordion-collapse collapse <?= $idx === 0 ? 'show' : '' ?>"
                            aria-labelledby="heading<?= $oid ?>" data-bs-parent="#orderAccordion">
                            <div class="accordion-body">

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="fw-semibold mb-1">ข้อมูลจัดส่ง</div>
                                        <div><span class="text-muted">โทร:</span> <?= htmlspecialchars($o['phone']) ?></div>
                                        <div class="mt-1"><span class="text-muted">ที่อยู่:</span><br><?= nl2br(htmlspecialchars($o['address'])) ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fw-semibold mb-1">สรุป</div>
                                        <div><span class="text-muted">ยอดรวม:</span> <span class="text-danger fw-bold">฿<?= number_format((float)$o['total_amount'], 2) ?></span></div>
                                        <div><span class="text-muted">จำนวนรายการ:</span> <?= count($items) ?> รายการ</div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>สินค้า</th>
                                                <th style="width:120px;">ราคา</th>
                                                <th style="width:80px;">จำนวน</th>
                                                <th style="width:140px;">รวม</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($items as $it): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($it['product_name']) ?></td>
                                                    <td>฿<?= number_format((float)$it['unit_price'], 2) ?></td>
                                                    <td><?= (int)$it['qty'] ?></td>
                                                    <td class="fw-semibold">฿<?= number_format((float)$it['line_total'], 2) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($items)): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-3">ไม่พบรายการสินค้า</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>