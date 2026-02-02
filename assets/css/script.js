function isLoggedIn() {
  return !!window.__MYSHOP_USER_ID; // true ถ้า login แล้ว (มี user id)
}



async function requireLoginOrRedirect() {
  if (window.__MYSHOP_USER_ID) return true;

  const base = (window.__MYSHOP_BASE_URL || '').replace(/\/$/, ''); // "/myshop"
  const returnUrl = window.location.pathname + window.location.search; // "/myshop/products-item.php?id=87"

  const loginUrl = `${base}/auth/login.php?return=${encodeURIComponent(returnUrl)}`;

  const goLogin = await showLoginRequiredPopup();
  if (goLogin) window.location.href = loginUrl;

  return false;
}



// -----------------------------------
// alert login required
// -----------------------------------
function showLoginRequiredPopup() {
  // ถ้าไม่มี SweetAlert2 ก็ fallback เป็น alert
  if (typeof Swal === 'undefined') {
    alert('กรุณาเข้าสู่ระบบก่อนดำเนินการ');
    return Promise.resolve(false);
  }

  return Swal.fire({
    icon: 'warning',
    title: 'กรุณาเข้าสู่ระบบ',
    html: `
      <div class="text-start">
        <p class="mb-1">คุณต้องเข้าสู่ระบบก่อนจึงจะสามารถ</p>
        <ul class="mb-2">
          <li>เพิ่มสินค้าเข้าตะกร้า</li>
          <li>ดำเนินการชำระเงิน</li>
        </ul>
        <p class="mb-0 small text-muted">กด “ไปหน้าเข้าสู่ระบบ” เพื่อดำเนินการต่อ</p>
      </div>
    `,
    showCancelButton: true,
    confirmButtonText: 'ไปหน้าเข้าสู่ระบบ',
    cancelButtonText: 'ยกเลิก',
    confirmButtonColor: '#083058ff',
    cancelButtonColor: '#8d200dfa',
    reverseButtons: true
  }).then((result) => result.isConfirmed);
}




function getCartStorageKey() {
  const uid = window.__MYSHOP_USER_ID; // มาจาก header.php
  return uid ? `cart_u_${uid}` : 'cart_guest';
}

// รองรับข้อมูลเก่าที่เคยเก็บไว้ key = 'cart' (migration)
function migrateLegacyCartIfNeeded() {
  const legacyKey = 'cart';
  const legacy = localStorage.getItem(legacyKey);
  if (!legacy) return;

  // ✅ ถ้ายังไม่ login -> ไม่ต้องย้าย legacy ไป guest ให้ "ลบทิ้ง" เลย
  if (!window.__MYSHOP_USER_ID) {
    localStorage.removeItem(legacyKey);
    localStorage.removeItem('cart_guest');
    return;
  }

  const newKey = getCartStorageKey();
  if (!localStorage.getItem(newKey)) {
    localStorage.setItem(newKey, legacy);
  }
  localStorage.removeItem(legacyKey);
}




// --------------------------------------
// ส่วนตะกร้าสินค้า (ใช้ได้ทุกหน้า)
// --------------------------------------

function getCart() {
  try {
    migrateLegacyCartIfNeeded();
    const key = getCartStorageKey();
    return JSON.parse(localStorage.getItem(key)) || [];
  } catch (e) {
    return [];
  }
}


// function saveCart(cart) {
//   localStorage.setItem('cart', JSON.stringify(cart));
// }

function saveCart(cart) {
  const key = getCartStorageKey();
  localStorage.setItem(key, JSON.stringify(cart));
}


// นับจำนวนรวม (ใช้ quantity ถ้ามี, ถ้าไม่มีให้เท่ากับ 1)
function getCartTotalQuantity() {
  const cart = getCart();
  return cart.reduce((sum, item) => {
    const qty = parseInt(item.quantity || 1, 10);
    return sum + (isNaN(qty) ? 1 : qty);
  }, 0);
}

// อัปเดตตัวเลขบน badge ที่ไอคอนตะกร้า
function updateCartCount() {
  const badge = document.getElementById('cartCountBadge');
  if (!badge) return;

  const totalQty = getCartTotalQuantity();
  if (totalQty > 0) {
    badge.textContent = totalQty;
    badge.style.display = 'inline-block';
  } else {
    badge.textContent = 0;
    badge.style.display = 'none'; // ถ้าอยากให้โชว์ 0 ตลอด ให้ลบบรรทัดนี้ออก
  }
}


async function addToCart(product) {

  // ✅ บังคับ login ก่อนเพิ่มลง cart
  if (!(await requireLoginOrRedirect())) return;

  const cart = getCart();

  const index = cart.findIndex(p => p.id === product.id);
  if (index !== -1) {
    const oldQty = parseInt(cart[index].quantity || 1, 10);
    const addQty = parseInt(product.quantity || 1, 10);
    cart[index].quantity = (isNaN(oldQty) ? 1 : oldQty) + (isNaN(addQty) ? 1 : addQty);
  } else {
    cart.push(product);
  }

  saveCart(cart);
  updateCartCount();
}




// SweetAlert theme สำหรับเว็บนี้
let skinAlert = null;

if (typeof Swal !== 'undefined') {
  skinAlert = Swal.mixin({
    buttonsStyling: false,
    customClass: {
      popup: 'rounded-4 shadow-lg',
      confirmButton: 'btn btn-primary px-4',
      cancelButton: 'btn btn-outline-secondary ms-2 px-4',
      title: 'fw-bold',
    },
    background: '#f8fafc',
    color: '#0f172a'
  });
}


function showError(msg) {
  if (!skinAlert) {
    alert(msg);
    return;
  }
  skinAlert.fire({
    icon: 'error',
    title: 'เกิดข้อผิดพลาด',
    html: `<div style="font-size:14px;">${msg}</div>`,
    confirmButtonText: 'รับทราบ',
  });
}



function clearGuestCartIfLogoutFlag() {
  const url = new URL(window.location.href);
  if (url.searchParams.get('logout') === '1') {
    localStorage.removeItem('cart_guest');
    localStorage.removeItem('cart'); // เผื่อมี legacy ค้าง

    // เอา logout ออกจาก URL กันลบซ้ำ
    url.searchParams.delete('logout');
    window.history.replaceState({}, document.title, url.pathname + url.search);
  }
}

document.addEventListener('DOMContentLoaded', function () {

  // 1) ถ้าออกจากระบบด้วย ?logout=1 ให้เคลียร์ guest cart
  clearGuestCartIfLogoutFlag();

  // 2) ✅ สำคัญ: ถ้ายังไม่ login ให้ล้าง cart_guest เสมอ
  // เพราะระบบคุณต้อง login ก่อน addToCart อยู่แล้ว (guest ไม่ควรมีตะกร้าค้าง)
  if (!window.__MYSHOP_USER_ID) {
    localStorage.removeItem('cart_guest');
    localStorage.removeItem('cart'); // เผื่อมี legacy ค้าง
  }

  // 3) sync badge ทันทีที่โหลดหน้า
  updateCartCount();

  // 4) login/register panel (มีเฉพาะหน้า auth)
  const container   = document.getElementById('container');
  const registerBtn = document.getElementById('register');
  const loginBtn    = document.getElementById('login');
  if (container && registerBtn && loginBtn) {
    registerBtn.addEventListener('click', () => container.classList.add('active'));
    loginBtn.addEventListener('click', () => container.classList.remove('active'));
  }

  // 5) password toggle (ถ้ามีปุ่ม .toggle-pass)
  const toggleButtons = document.querySelectorAll('.toggle-pass');
  toggleButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const selector = btn.getAttribute('data-target');
      if (!selector) return;

      const input = document.querySelector(selector);
      if (!input) return;

      const isHidden = input.getAttribute('type') === 'password';
      input.setAttribute('type', isHidden ? 'text' : 'password');
      btn.classList.toggle('is-shown', isHidden);
      btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
    });
  });
});

// ถ้าจะใช้ SweetAlert ให้เรียกผ่านฟังก์ชันนี้
function showError(msg) {
  if (typeof Swal === 'undefined') return;
  Swal.fire({
    icon: 'error',
    title: 'เกิดข้อผิดพลาด',
    html: `<div style="font-size:14px;">${msg}</div>`,
    confirmButtonText: 'รับทราบ',
    confirmButtonColor: '#111',
    background: '#1f2428',
    color: '#fff',
  });
}


