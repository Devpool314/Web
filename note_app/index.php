<?php
session_start();
include 'db.php';

// Hàm gửi email kích hoạt
function sendActivationEmail($email, $token) {
    $subject = "Kích hoạt tài khoản Note App";
    $activation_link = "http://localhost/note-app/activate.php?email=$email&token=$token";
    $message = "Chào bạn,\n\nBạn đã đăng ký tài khoản thành công trên Note App.\n\nĐể xác minh email, hãy nhấn vào liên kết sau:\n$activation_link\n\nNếu bạn không đăng ký tài khoản, vui lòng bỏ qua email này.";
    $headers = "From: no-reply@note-app.local";

    // Gửi email
    mail($email, $subject, $message, $headers);
}

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if (!empty($email) && !empty($pass)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $user = $res->fetch_assoc();
            if (password_verify($pass, $user['password'])) {
                $_SESSION['user'] = [
                    'email' => $user['email'],
                    'name' => $user['display_name']
                ];
                header("Location: home.php");
                exit;
            } else {
                $error = "⚠️ Mật khẩu không đúng.";
            }
        } else {
            $error = "⚠️ Tài khoản không tồn tại.";
        }
    } else {
        $error = "⚠️ Vui lòng nhập đầy đủ email và mật khẩu.";
    }
}

// Xử lý đăng ký
if (isset($_POST['register']) && isset($_POST['reg_email']) && isset($_POST['reg_password']) && isset($_POST['reg_password2'])) {
    $email = $_POST['reg_email'];
    $name = $_POST['reg_name'] ?? '';
    $pass1 = $_POST['reg_password'];
    $pass2 = $_POST['reg_password2'];

    if ($pass1 !== $pass2) {
        $reg_error = "Mật khẩu không khớp.";
    } else {
        $hash = password_hash($pass1, PASSWORD_BCRYPT);
        $token = bin2hex(random_bytes(16));

        // Kiểm tra email đã tồn tại
        $check = $conn->prepare("SELECT * FROM users WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $check_res = $check->get_result();

        if ($check_res->num_rows > 0) {
            $reg_error = "Email đã được đăng ký.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (email, display_name, password, verify_token, is_verified) VALUES (?, ?, ?, ?, 0)");
            $stmt->bind_param("ssss", $email, $name, $hash, $token);
            $stmt->execute();

            $_SESSION['user'] = [
                'email' => $email,
                'name' => $name
            ];

            // Gửi link kích hoạt (tạm in ra vì chưa cấu hình SMTP)
            $activation_link = "http://localhost/note-app/activate.php?email=" . urlencode($email) . "&token=" . urlencode($token);
            echo "<script>alert('Đăng ký thành công. Link kích hoạt đã được gửi đến email của bạn.');</script>";
            // Chuyển hướng sau 0s
            echo "<script>setTimeout(function() { window.location.href = 'home.php'; });</script>";
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Note App - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .auth-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .toggle-link {
            color: #0d6efd;
            cursor: pointer;
        }
        .toggle-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="auth-container">
        <!-- Đăng nhập -->
        <div id="login-box">
            <h3 class="text-center mb-3">Đăng nhập</h3>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Đăng nhập</button>
            </form>
            <p class="mt-3 text-center toggle-link" onclick="toggleRegister()">Chưa có tài khoản? <span>Đăng ký ngay</span></p>
        </div>

        <!-- Đăng ký -->
        <div id="register-box" style="display: none;">
            <h3 class="text-center mb-3">Đăng ký</h3>
            <?php if (isset($reg_error)) echo "<div class='alert alert-danger'>$reg_error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <input type="email" name="reg_email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="reg_name" class="form-control" placeholder="Tên hiển thị" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="reg_password" class="form-control" placeholder="Mật khẩu" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="reg_password2" class="form-control" placeholder="Xác nhận mật khẩu" required>
                </div>
                <button type="submit" name="register" class="btn btn-success w-100">Đăng ký</button>
            </form>
            <p class="mt-3 text-center toggle-link" onclick="toggleLogin()">Đã có tài khoản? <span>Đăng nhập</span></p>
        </div>
    </div>
</div>

<script>
    function toggleRegister() {
        document.getElementById('login-box').style.display = 'none';
        document.getElementById('register-box').style.display = 'block';
    }

    function toggleLogin() {
        document.getElementById('register-box').style.display = 'none';
        document.getElementById('login-box').style.display = 'block';
    }
</script>

</body>
</html>

