<?php
require_once __DIR__ . '/functions.php';
RequireLogin();
require_once __DIR__ . '/partials/header.php';
$user = $_SESSION['user'];


?>

<div class="container mt-5 pt-4">
  <div class="row">
    <!-- ส่วนรายการสินค้าในตะกร้า -->
    <div class="col-lg-8">
      <div class="card shadow-sm mb-4">
        <div class="card-header text-white" style="background-color: #083058;">
          <h5 class="mb-0">ตะกร้าสินค้า</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th>สินค้า</th>
                  <th class="text-center" style="width: 120px;">ราคา</th>
                  <th class="text-center" style="width: 120px;">จำนวน</th>
                  <th class="text-center" style="width: 140px;">รวม</th>
                  <th class="text-center" style="width: 80px;">ลบ</th>
                </tr>
              </thead>
              <tbody id="cartBody">
                <!-- JS จะเติมข้อมูลตรงนี้ -->
              </tbody>
            </table>
          </div>

          <div id="emptyCart" class="p-4 text-center text-muted d-none">
            ยังไม่มีสินค้าในตะกร้า
          </div>
        </div>
      </div>
    </div>

    <!-- ส่วนสรุปรายการ / ชำระเงิน -->
    <div class="col-lg-4">
      <div class="card shadow-sm mb-4">
        <div class="card-header text-white" style="background-color:#083058;">
          <h5 class="mb-0">ข้อมูลจัดส่ง</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">ชื่อ-นามสกุล</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars(string: $user['first_name'] . ' ' . $user['last_name']); ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">เบอร์โทร <span class="text-danger">*</span></label>
            <input id="shipPhone" type="text" class="form-control" placeholder="เช่น 0812345678" required>
          </div>
          <div class="mb-3">
            <label class="form-label">ที่อยู่จัดส่ง <span class="text-danger">*</span></label>
            <textarea id="shipAddress" class="form-control" rows="3" placeholder="บ้านเลขที่, ถนน, แขวง/ตำบล, เขต/อำเภอ, จังหวัด, รหัสไปรษณีย์" required></textarea>
          </div>
          <div class="form-text text-muted">
            กรุณากรอกข้อมูลให้ครบถ้วนก่อนดำเนินการชำระเงิน
          </div>
          <div class="mb-2">
            <label class="form-label">วิธีการชำระเงิน</label>
            <select id="paymentMethod" class="form-select">
              <option value="BANK">โอนผ่านธนาคาร</option>
              <option value="COD" selected>เก็บเงินปลายทาง (COD)</option>
              <option value="CARD">บัตรเครดิต / เดบิต</option>
            </select>
          </div>
          <small class="text-muted">
            * สามารถตรวจสอบวิธีสั่งซื้อได้ที่ <a href="<?= BASE_URL ?>/shipping.php" style="text-decoration: none;">ขั้นตอนการสั่งซื้อ</a>
          </small>
        </div>
      </div>


      <div class="card shadow-sm mb-4">
        <div class="card-header text-white" style="background-color:#083058;">
          <h5 class="mb-0">สรุปรายการ</h5>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <span>จำนวนรายการทั้งหมด</span>
            <span id="summaryItems">0 ชิ้น</span>
          </div>
          <div class="d-flex justify-content-between mb-3">
            <span>ยอดรวม</span>
            <span id="summaryTotal" class="fw-bold text-danger">฿0.00</span>
          </div>

          <button class="btn btn-success w-100 mb-3" id="btnCheckout" disabled>
            ดำเนินการชำระเงิน
          </button>

        </div>
      </div>


    </div>
  </div>
</div>



<script>
  // ใช้ฟังก์ชัน getCart / saveCart / updateCartCount จาก script.js

  function formatCurrency(num) {
    return '฿' + Number(num).toLocaleString('th-TH', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
  }

  function renderCart() {
    const cart = getCart();
    const tbody = document.getElementById('cartBody');
    const emptyCart = document.getElementById('emptyCart');
    const summaryItems = document.getElementById('summaryItems');
    const summaryTotal = document.getElementById('summaryTotal');
    const btnCheckout = document.getElementById('btnCheckout');

    tbody.innerHTML = '';

    if (!cart.length) {
      emptyCart.classList.remove('d-none');
      summaryItems.textContent = '0 ชิ้น';
      summaryTotal.textContent = '฿0.00';
      if (btnCheckout) btnCheckout.disabled = true;
      updateCartCount();
      return;
    }

    emptyCart.classList.add('d-none');

    let totalQty = 0;
    let totalAmount = 0;

    cart.forEach((item, index) => {
      const qty = parseInt(item.quantity || 1, 10);
      const price = parseFloat(item.price || 0);
      const lineTotal = qty * price;

      totalQty += qty;
      totalAmount += lineTotal;

      const tr = document.createElement('tr');

      tr.innerHTML = `
      <td>
        <div class="d-flex align-items-center">
          <img src="${item.image_url || 'https://via.placeholder.com/60x60'}"
               alt=""
               class="me-2 rounded"
               style="width:60px;height:60px;object-fit:cover;">
          <div>
            <div class="fw-semibold">${item.name}</div>
          </div>
        </div>
      </td>
      <td class="text-center">
        ${formatCurrency(price)}
      </td>
      <td class="text-center">
        <input type="number"
               min="1"
               value="${qty}"
               class="form-control form-control-sm text-center"
               style="max-width:80px;"
               data-index="${index}">
      </td>
      <td class="text-center fw-semibold">
        ${formatCurrency(lineTotal)}
      </td>
      <td class="text-center">
        <button class="btn btn-sm btn-outline-danger" data-remove="${index}">
          <i class="bi bi-trash"></i>
        </button>
      </td>
    `;

      tbody.appendChild(tr);
    });

    summaryItems.textContent = totalQty + ' ชิ้น';
    summaryTotal.textContent = formatCurrency(totalAmount);
    if (btnCheckout) btnCheckout.disabled = false;

    // event: เปลี่ยนจำนวน
    tbody.querySelectorAll('input[type="number"]').forEach(input => {
      input.addEventListener('change', (e) => {
        const idx = parseInt(e.target.getAttribute('data-index'), 10);
        let newQty = parseInt(e.target.value, 10);
        if (isNaN(newQty) || newQty < 1) newQty = 1;
        const cart = getCart();
        cart[idx].quantity = newQty;
        saveCart(cart);
        renderCart();
        updateCartCount();
      });
    });

    // event: ลบรายการ
    tbody.querySelectorAll('button[data-remove]').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const idx = parseInt(e.currentTarget.getAttribute('data-remove'), 10);
        const cart = getCart();
        cart.splice(idx, 1);
        saveCart(cart);
        renderCart();
        updateCartCount();
      });
    });
  }



  async function handleCheckout() {
    const cart = getCart();
    const phone = (document.getElementById('shipPhone')?.value || '').trim();
    const address = (document.getElementById('shipAddress')?.value || '').trim();

    if (!cart.length) {
      Swal.fire({
        icon: 'warning',
        title: 'ยังไม่มีสินค้าในตะกร้า',
        text: 'กรุณาเลือกสินค้าอย่างน้อย 1 รายการก่อนดำเนินการชำระเงิน',
        buttonsStyling: false,
        customClass: {
          popup: 'rounded-4 shadow-lg',
          confirmButton: 'btn btn-primary px-4',
          title: 'fw-bold'
        },
        background: '#f8fafc',
        color: '#0f172a',
        confirmButtonText: 'ตกลง'
      });
      return;
    }

    // ✅ Required fields
    if (!phone || !address) {
      Swal.fire({
        icon: 'warning',
        title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
        html: `
        <div class="text-start">
          <p class="mb-2">ต้องกรอกข้อมูลก่อนชำระเงิน:</p>
          <ul class="mb-0">
            <li>เบอร์โทร</li>
            <li>ที่อยู่จัดส่ง</li>
          </ul>
        </div>
      `,
        buttonsStyling: false,
        customClass: {
          popup: 'rounded-4 shadow-lg',
          confirmButton: 'btn btn-primary px-4',
          title: 'fw-bold'
        },
        background: '#f8fafc',
        color: '#0f172a',
        confirmButtonText: 'ตกลง'
      });
      return;
    }

    // validate phone (ปรับได้)
    const phoneOk = /^[0-9+\-\s]{8,20}$/.test(phone);
    if (!phoneOk) {
      Swal.fire({
        icon: 'warning',
        title: 'รูปแบบเบอร์โทรไม่ถูกต้อง',
        text: 'กรุณากรอกเบอร์โทรเป็นตัวเลข เช่น 0812345678',
        buttonsStyling: false,
        customClass: {
          popup: 'rounded-4 shadow-lg',
          confirmButton: 'btn btn-primary px-4',
          title: 'fw-bold'
        },
        background: '#f8fafc',
        color: '#0f172a',
        confirmButtonText: 'ตกลง'
      });
      return;
    }

    const payment_method = (document.getElementById('paymentMethod')?.value || 'PAID').trim();
    const payload = {
      phone,
      address,
      payment_method,
      items: cart.map(p => ({
        id: parseInt(p.id, 10),
        qty: parseInt(p.quantity || 1, 10)
      }))
    };

    const btn = document.getElementById('btnCheckout');
    if (btn) {
      btn.disabled = true;
      btn.textContent = 'กำลังดำเนินการ...';
    }

    try {
      const res = await fetch("<?= BASE_URL ?>/api/checkout.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
      });
      const data = await res.json();

      if (!res.ok || !data.ok) {
        if (data.code === 'OUT_OF_STOCK') {
          Swal.fire({
            icon: 'error',
            title: 'สินค้าในสต็อกไม่พอ',
            html: `
            <div class="text-start">
              <p class="mb-1"><strong>${data.product_name}</strong></p>
              <p class="mb-0">ต้องการ: ${data.requested} | เหลือ: ${data.available}</p>
            </div>
          `,
            buttonsStyling: false,
            customClass: {
              popup: 'rounded-4 shadow-lg',
              confirmButton: 'btn btn-primary px-4',
              title: 'fw-bold'
            },
            background: '#f8fafc',
            color: '#0f172a',
            confirmButtonText: 'ตกลง'
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'ชำระเงินไม่สำเร็จ',
            text: data.message || 'Checkout failed'
          });
        }
        return;
      }

      // ✅ success (server ตัดสต็อก + สร้าง order แล้ว)
      saveCart([]);
      renderCart();
      updateCartCount();

      Swal.fire({
        icon: 'success',
        title: 'สั่งซื้อสำเร็จ',
        html: `
        <div class="text-start">
          <p class="mb-1">เลขคำสั่งซื้อ: <strong>#${data.order_id}</strong></p>
          <p class="mb-1">ยอดรวม: <strong class="text-danger">฿${Number(data.total).toFixed(2)}</strong></p>
          <p class="mb-0 small text-muted">ระบบได้บันทึกคำสั่งซื้อและตัดสต็อกเรียบร้อยแล้ว</p>
        </div>
      `,
        buttonsStyling: false,
        customClass: {
          popup: 'rounded-4 shadow-lg',
          confirmButton: 'btn btn-primary px-4',
          title: 'fw-bold'
        },
        background: '#f8fafc',
        color: '#0f172a',
        confirmButtonText: 'ปิด'
      });

    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'เกิดข้อผิดพลาด',
        text: String(err)
      });
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.textContent = 'ดำเนินการชำระเงิน';
      }
    }
  }



  document.addEventListener('DOMContentLoaded', function() {
    renderCart();
    updateCartCount();

    const btnCheckout = document.getElementById('btnCheckout');
    if (btnCheckout) {
      btnCheckout.addEventListener('click', function(e) {
        e.preventDefault();
        handleCheckout();
      });
    }
  });
</script>



<?php require_once __DIR__ . '/partials/footer.php'; ?>