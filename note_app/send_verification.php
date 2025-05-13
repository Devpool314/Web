<?php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Đường dẫn tới autoload Composer

function sendVerificationEmail($email, $token) {
    $mail = new PHPMailer(true);

    try {
        // Cấu hình SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Hoặc SMTP của bạn
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@gmail.com';     // Địa chỉ email gửi
        $mail->Password = 'your_email_password';      // Mật khẩu ứng dụng
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Gửi email
        $mail->setFrom('your_email@gmail.com', 'Your Website');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Xác nhận tài khoản';
        $verifyLink = "http://yourdomain.com/verify.php?email=$email&token=$token";
        $mail->Body = "Nhấp vào liên kết sau để xác nhận tài khoản của bạn: <a href='$verifyLink'>$verifyLink</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: {$mail->ErrorInfo}");
        return false;
    }
}
