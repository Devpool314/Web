<?php
session_start();

// Nếu chưa đăng nhập, chuyển về index.php
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ Sơ Người Dùng</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="light-mode">

<div class="main">
    <h2>👋 Xin chào, <?php echo $_SESSION['user']['name']; ?>!</h2>
    <p>Đây là thông tin cá nhân của bạn:</p>

    <ul>
        <li><strong>Tên đăng nhập:</strong> <?php echo $_SESSION['user']['name']; ?></li>
        <li><strong>Email:</strong> <?php echo $_SESSION['user']['email']; ?></li>
    </ul>

    <a class="logout-link" href="home.php">← Quay lại trang chủ</a>
</div>

</body>
</html>
