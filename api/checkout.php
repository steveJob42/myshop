<?php

require_once __DIR__ . '/../functions.php';
RequireLogin();

header('Content-Type: application/json; charset=utf-8');

$user = $_SESSION['user'];
$userId = (int)($user['id'] ?? 0);
$fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
$email = trim($user['email'] ?? '');

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Invalid JSON']);
    exit;
}

$phone = trim($data['phone'] ?? '');
$address = trim($data['address'] ?? '');
$paymentMethod = strtoupper(trim($data['payment_method'] ?? 'PAID')); // COD / BANK / CARD / ...
if ($paymentMethod === '') $paymentMethod = 'PAID';
$items = $data['items'] ?? [];

$orderStatus = ($paymentMethod === 'COD') ? 'COD' : 'paid';


if ($phone === '' || $address === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Phone and Address are required']);
    exit;
}

if (!is_array($items) || empty($items)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Cart is empty']);
    exit;
}

// normalize items => product_id => qty
$normalized = [];
foreach ($items as $it) {
    $pid = (int)($it['id'] ?? 0);
    $qty = (int)($it['qty'] ?? 0);
    if ($pid <= 0 || $qty <= 0) continue;
    $normalized[$pid] = ($normalized[$pid] ?? 0) + $qty;
}

if (empty($normalized)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Cart is empty']);
    exit;
}

try {
    global $pdo;
    $pdo->beginTransaction();

    $orderLines = [];
    $total = 0.0;

    foreach ($normalized as $pid => $qty) {
        // lock row to prevent race condition
        $stmt = $pdo->prepare("
      SELECT id, name, stock, price, discount_price
      FROM products
      WHERE id = ?
      FOR UPDATE
    ");
        $stmt->execute([$pid]);
        $p = $stmt->fetch();

        if (!$p) {
            $pdo->rollBack();
            http_response_code(404);
            echo json_encode(['ok' => false, 'message' => "Product not found: {$pid}"]);
            exit;
        }

        $stock = (int)$p['stock'];
        if ($stock < $qty) {
            $pdo->rollBack();
            http_response_code(409);
            echo json_encode([
                'ok' => false,
                'code' => 'OUT_OF_STOCK',
                'product_id' => (int)$p['id'],
                'product_name' => $p['name'],
                'available' => $stock,
                'requested' => $qty,
                'message' => "สินค้าไม่พอ: {$p['name']} เหลือ {$stock}"
            ]);
            exit;
        }

        $unitPrice = ($p['discount_price'] !== null) ? (float)$p['discount_price'] : (float)$p['price'];
        $lineTotal = $unitPrice * $qty;
        $total += $lineTotal;

        $orderLines[] = [
            'product_id' => (int)$p['id'],
            'product_name' => $p['name'],
            'unit_price' => $unitPrice,
            'qty' => $qty,
            'line_total' => $lineTotal,
        ];
    }

    // create order
    $stmtOrder = $pdo->prepare("
    INSERT INTO orders (user_id, full_name, email, phone, address, total_amount, status, payment_method)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
  ");
    $stmtOrder->execute([$userId, $fullName, $email, $phone, $address, $total, $orderStatus, $paymentMethod]);

    $orderId = (int)$pdo->lastInsertId();

    $stmtUpdate = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
    $stmtItem = $pdo->prepare("
    INSERT INTO order_items (order_id, product_id, product_name, unit_price, qty, line_total)
    VALUES (?, ?, ?, ?, ?, ?)
  ");

    foreach ($orderLines as $l) {
        $qty = (int)$l['qty'];
        $pid = (int)$l['product_id'];

        // ตัดสต็อก
        $stmtUpdate->execute([$qty, $pid]);

        // บันทึกรายการสินค้า
        $stmtItem->execute([
            $orderId,
            $pid,
            $l['product_name'],
            (float)$l['unit_price'],
            $qty,
            (float)$l['line_total']
        ]);
    }


    $pdo->commit();

    echo json_encode(['ok' => true, 'order_id' => $orderId, 'total' => round($total, 2)]);
    exit;
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Checkout failed', 'detail' => $e->getMessage()]);
    exit;
}
