<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../partials/header.php';

// functions.php มี session_start() + $pdo + BASE_URL แล้ว

$errors = [];
$success = "";

/* ==========================================
   อ่านคอลัมน์จริงของตาราง users
   ========================================== */
function getUserColumns(PDO $pdo): array
{
  $stmt = $pdo->query(query: "
        SELECT COLUMN_NAME 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'users'
    ");
  return array_map(callback: fn($r): string => strtolower(string: $r['COLUMN_NAME']), array: $stmt->fetchAll());
}

$cols = getUserColumns(pdo: $pdo);

function pickCol(array $cols, array $candidates): ?string
{
  foreach ($candidates as $c) {
    $cLower = strtolower(string: $c);
    if (in_array(needle: $cLower, haystack: $cols)) return $cLower;
  }
  return null;
}

/* ===== auto map column names ===== */
$colId    = pickCol(cols: $cols, candidates: ['id', 'user_id']);
$colFirst = pickCol(cols: $cols, candidates: ['first_name', 'firstname', 'firthname', 'name']);
$colLast  = pickCol(cols: $cols, candidates: ['last_name', 'lastname', 'surname']);
$colEmail = pickCol(cols: $cols, candidates: ['emailaddress', 'email', 'username', 'user_name', 'login']);
$colPass  = pickCol(cols: $cols, candidates: ['password_hash', 'password', 'pass_hash', 'passwd']);
$colAdmin = pickCol(cols: $cols, candidates: ['is_admin', 'admin', 'role']);

/* ==========================================
   REGISTER LOGIC (อยู่หน้าเดียว)
   ========================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_type'] ?? '') === 'register') {

  // รับค่าหลายชื่อกันพลาด
  $first = trim(string: $_POST['first_name'] ?? $_POST['firstname'] ?? $_POST['FirstName'] ?? '');
  $last  = trim(string: $_POST['last_name']  ?? $_POST['lastname']  ?? $_POST['LastName']  ?? '');
  $email = trim(string: $_POST['emailaddress'] ?? $_POST['email'] ?? $_POST['username'] ?? '');
  $pass  = $_POST['password'] ?? '';
  $conf  = $_POST['confirm_password'] ?? '';

  if ($first === '' || $last === '' || $email === '' || $pass === '' || $conf === '') {
    $errors[] = "กรุณากรอกข้อมูลให้ครบ";
  } elseif (!filter_var(value: $email, filter: FILTER_VALIDATE_EMAIL) && $colEmail !== 'username') {
    // ถ้า colEmail เป็น username อาจไม่ใช่อีเมลก็ได้ เลยไม่บังคับ
    $errors[] = "รูปแบบอีเมลไม่ถูกต้อง";
  } elseif ($pass !== $conf) {
    $errors[] = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน";
  } elseif (strlen(string: $pass) < 6) {
    $errors[] = "รหัสผ่านต้องอย่างน้อย 6 ตัวอักษร";
  } elseif (!$colEmail || !$colPass) {
    $errors[] = "ไม่พบคอลัมน์ email/username หรือ password ในตาราง users";
  } else {

    // เช็คซ้ำ
    $stmt = $pdo->prepare(query: "SELECT 1 FROM users WHERE {$colEmail} = ? LIMIT 1");
    $stmt->execute(params: [$email]);
    if ($stmt->fetch()) {
      $errors[] = "อีเมล/ชื่อผู้ใช้นี้ถูกใช้งานแล้ว";
    } else {
      // insert ตามคอลัมน์จริง
      $hash = password_hash(password: $pass, algo: PASSWORD_BCRYPT);

      $insertCols = [];
      $insertVals = [];
      $params     = [];

      if ($colFirst) {
        $insertCols[] = $colFirst;
        $insertVals[] = '?';
        $params[] = $first;
      }
      if ($colLast) {
        $insertCols[] = $colLast;
        $insertVals[] = '?';
        $params[] = $last;
      }
      $insertCols[] = $colEmail;
      $insertVals[] = '?';
      $params[] = $email;
      $insertCols[] = $colPass;
      $insertVals[] = '?';
      $params[] = $hash;

      if ($colAdmin) {
        $insertCols[] = $colAdmin;
        $insertVals[] = '?';
        $params[] = 0; // default user
      }

      $sql = "INSERT INTO users(" . implode(separator: ',', array: $insertCols) . ") VALUES(" . implode(separator: ',', array: $insertVals) . ")";
      $pdo->prepare(query: $sql)->execute(params: $params);

      $success = "สมัครสมาชิกสำเร็จ! กรุณา Sign In";
    }
  }
}

/* ==========================================
   LOGIN LOGIC
   ========================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_type'] ?? '') === 'login') {

  $email = trim(string: $_POST['emailaddress'] ?? $_POST['email'] ?? $_POST['username'] ?? '');
  $pass  = $_POST['password'] ?? '';

  if ($email === '' || $pass === '') {
    $errors[] = "กรุณากรอกข้อมูลให้ครบ";
  } elseif (!$colEmail || !$colPass) {
    $errors[] = "ไม่พบคอลัมน์ email/username หรือ password ในตาราง users";
  } else {
    $stmt = $pdo->prepare(query: "SELECT * FROM users WHERE {$colEmail} = ? LIMIT 1");
    $stmt->execute(params: [$email]);
    $user = $stmt->fetch(mode: PDO::FETCH_ASSOC);

    if (!$user) {
      $errors[] = "อีเมล/ชื่อผู้ใช้ หรือรหัสผ่านไม่ถูกต้อง";
    } else {
      $stored = $user[$colPass];

      // รองรับทั้ง hash และ plain (กันตารางเก่า)
      $ok = password_verify(password: $pass, hash: $stored);
      if (!$ok && $pass === $stored) $ok = true;

      if (!$ok) {
        $errors[] = "อีเมล/ชื่อผู้ใช้ หรือรหัสผ่านไม่ถูกต้อง";
      } else {
        $_SESSION['user'] = [
          'id' => $colId ? $user[$colId] : null,
          'first_name' => $colFirst ? $user[$colFirst] : '',
          'last_name'  => $colLast  ? $user[$colLast]  : '',
          'email'      => $user[$colEmail],
          'is_admin'   => $colAdmin ? (int)$user[$colAdmin] : 0
        ];

        // //// header(header: "Location: " . BASE_URL . "/admin/dashboard.php");
        // //// exit;
        // $return = $_GET['return'] ?? '';
        // if ($return) {
        //   header("Location: " . $return);
        // } else {
        //   header("Location: " . BASE_URL . "/admin/dashboard.php");
        // }
        // exit;
        $return = $_GET['return'] ?? '';

        if ($return && str_starts_with($return, BASE_URL)) {
          header("Location: " . $return);
        } else {
          header("Location: " . BASE_URL . "/index.php");
          }
        exit;
      }
    }
  }
}


?>
<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | MyShop</title>

  <link rel="stylesheet" href="<?= BASE_URL ?>/auth/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>



  <main class="auth-page">
    <div class="container" id="container">

      <!-- ================= REGISTER ================= -->
      <div class="form-container sign-up">
        <form method="post">
          <h1>Sign Up</h1>
          <span>สมัครสมาชิกเพื่อเริ่มช้อปปิ้ง</span>

          <input type="hidden" name="form_type" value="register">

          <?php if ($success): ?>
            <p style="color:green; margin:6px 0;"><?= htmlspecialchars(string: $success) ?></p>
          <?php endif; ?>

          <?php if ($errors && ($_POST['form_type'] ?? '') === 'register'): ?>
            <p style="color:red; margin:6px 0;"><?= htmlspecialchars(string: $errors[0]) ?></p>
          <?php endif; ?>

          <!-- field เดิมตาม requirement -->
          <input type="text" name="first_name" placeholder="First Name" required />
          <input type="text" name="last_name" placeholder="Last Name" required />
          <input type="email" name="emailaddress" placeholder="Email Address" required />
          <input type="password" name="password" placeholder="Password" required />
          <input type="password" name="confirm_password" placeholder="Confirm Password" required />

          <button type="submit">Sign Up</button>
        </form>
      </div>

      <!-- ================= LOGIN ================= -->
      <div class="form-container sign-in">
        <form method="post">
          <h1>Sign In</h1>
          <span>ใช้อีเมลและรหัสผ่านของคุณ</span>

          <input type="hidden" name="form_type" value="login">

          <?php if ($errors && ($_POST['form_type'] ?? '') === 'login'): ?>
            <p style="color:red; margin:6px 0;"><?= htmlspecialchars(string: $errors[0]) ?></p>
          <?php endif; ?>

          <input type="email" name="emailaddress" placeholder="Email Address" required />
          <input type="password" name="password" placeholder="Password" required />


          <button type="submit">Sign In</button>
          <a href="<?= BASE_URL ?>/index.php">← Back to Home</a>
        </form>
      </div>

      <!-- ================= TOGGLE PANEL ================= -->
      <div class="toggle-container">
        <div class="toggle">

          <div class="toggle-panel toggle-left">
            <h1>Welcome Back!</h1>
            <p>มีบัญชีแล้ว กด Sign In ได้เลย</p>
            <button class="hidden" id="login">Sign In</button>
          </div>

          <div class="toggle-panel toggle-right">
            <h1>Hello, Friend!</h1>
            <p>ยังไม่มีบัญชี? สมัครได้เลยในหน้านี้</p>
            <button class="hidden" id="register">Sign Up</button>
          </div>

        </div>
      </div>

    </div>
  </main>

  <?php require_once __DIR__ . '/../partials/footer.php'; ?>

  <script src="<?= BASE_URL ?>/assets/css/script.js"></script>

  <?php
  // สร้างฟังก์ชั่นเล็กๆ สำหรับ escape string ไป JS
  function js_escape($str): bool|string
  {
    return json_encode(value: $str, flags: JSON_UNESCAPED_UNICODE);
  }
  ?>

  <?php if (!empty($errors)): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'เกิดข้อผิดพลาด',
        text: <?= js_escape(str: $errors[0]) ?>,
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#1b5fa3', // โทนม่วงเดียวกับหน้า auth
        background: '#fff',
        color: '#111',
      });
    </script>
  <?php endif; ?>

  <?php if (!empty($errors) && (($_POST['form_type'] ?? '') === 'register')): ?>
    <script>
      document.getElementById('container').classList.add('active');
    </script>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'สำเร็จ!',
        text: <?= js_escape(str: $success) ?>,
        confirmButtonText: 'ไป Sign In',
        confirmButtonColor: '#1b5fa3',
      }).then(() => {
        // สมัครเสร็จแล้วสลับกลับไปฝั่ง Sign In อัตโนมัติ
        document.getElementById('container').classList.remove('active');
      });
    </script>
  <?php endif; ?>

</body>

</html>