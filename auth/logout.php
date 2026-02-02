<?php
require_once __DIR__ . '/../config.php';

session_unset();
session_destroy();

// ส่ง flag เพื่อให้ฝั่ง JS เคลียร์ cart_guest ด้วย
header("Location: " . BASE_URL . "/index.php?logout=1");
exit;
