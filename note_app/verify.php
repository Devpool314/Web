<?php
require_once 'db.php';

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND verify_token = ? AND is_verified = 0");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Xác nhận tài khoản
        $update = $conn->prepare("UPDATE users SET is_verified = 1, verify_token = '' WHERE email = ?");
        $update->bind_param("s", $email);
        $update->execute();
        echo "Tài khoản đã được xác minh thành công. <a href='login.php'>Đăng nhập</a>";
    } else {
        echo "Liên kết xác nhận không hợp lệ hoặc đã hết hạn.";
    }
}
?>
