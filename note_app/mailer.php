<?php

require 'C:\xampp\htdocs\note_app\phpmailer\PHPMailer.php';
require 'C:\xampp\htdocs\note_app\phpmailer\SMTP.php';
require 'C:\xampp\htdocs\note_app\phpmailer\Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendActivationEmail($toEmail, $token) {
    $mail = new PHPMailer(true);

    try {
        // Server cấu hình
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // SMTP của Gmail
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_email@gmail.com'; // Email bạn
        $mail->Password   = 'your_app_password'; // App password (không dùng mật khẩu tài khoản)
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Người gửi và người nhận
        $mail->setFrom('your_email@gmail.com', 'Note App');
        $mail->addAddress($toEmail);

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = 'Kích hoạt tài khoản Note App';
        $activation_link = "http://localhost/note-app/activate.php?email=" . urlencode($toEmail) . "&token=" . urlencode($token);
        $mail->Body    = "Chào bạn,<br><br>Hãy nhấn vào liên kết sau để kích hoạt tài khoản:<br><a href='$activation_link'>$activation_link</a><br><br>Xin cảm ơn!";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email error: {$mail->ErrorInfo}");
        return false;
    }
}
