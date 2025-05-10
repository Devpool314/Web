<?php
include 'db.php';

$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';

if (empty($email) || empty($token)) {
    die("❌ Thiếu thông tin xác thực.");
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND verify_token = ?");
$stmt->bind_param("ss", $email, $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Cập nhật trạng thái xác minh
    $update = $conn->prepare("UPDATE users SET is_verified = 1, verify_token = NULL WHERE email = ?");
    $update->bind_param("s", $email);
    $update->execute();

    echo "✅ Tài khoản đã được kích hoạt thành công. Bạn có thể <a href='index.php'>đăng nhập tại đây</a>.";
} else {
    echo "❌ Link không hợp lệ hoặc đã được sử dụng.";
}
?>
