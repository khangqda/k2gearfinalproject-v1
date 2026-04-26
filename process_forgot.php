<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh");
mysqli_set_charset($conn, "utf8mb4");

// Nhúng thư viện PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['reset_email']));

    // Kiểm tra xem email có trong hệ thống không
    $check_email = mysqli_query($conn, "SELECT id, name FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $user = mysqli_fetch_assoc($check_email);
        
        // Tạo token ngẫu nhiên và thời gian hết hạn (ví dụ: 15 phút)
        $token = bin2hex(random_bytes(50));
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $expire_time = date("Y-m-d H:i:s", strtotime('+15 minutes'));

        // Lưu token vào CSDL
        mysqli_query($conn, "UPDATE users SET reset_token='$token', reset_token_expire='$expire_time' WHERE email='$email'");

        // Link khôi phục
        $reset_link = "http://localhost/k2gear/reset_password.php?token=" . $token;

        // BẮT ĐẦU GỬI MAIL
        $mail = new PHPMailer(true);
        try {
            // Cấu hình SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            
            // ĐIỀN EMAIL VÀ MẬT KHẨU ỨNG DỤNG CỦA BẠN VÀO ĐÂY
            $mail->Username   = 'closesoregano366@gmail.com'; 
            $mail->Password   = 'vhcb pikh ewnr ipfn'; 
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // Người gửi & Người nhận
            $mail->setFrom('k2gearsp@gmail.com', 'K2 GEAR Support');
            $mail->addAddress($email, $user['name']);

            // Nội dung Mail
            $mail->isHTML(true);
            $mail->Subject = 'Yêu cầu khôi phục mật khẩu - K2 GEAR';
            $mail->Body    = "
                <h3>Xin chào {$user['name']},</h3>
                <p>Bạn vừa yêu cầu khôi phục mật khẩu tại K2 GEAR.</p>
                <p>Vui lòng click vào đường link dưới đây để đặt lại mật khẩu (Link có hiệu lực trong 15 phút):</p>
                <p><a href='$reset_link' style='padding: 10px 20px; background: #3b66cc; color: #fff; text-decoration: none; border-radius: 5px;'>ĐẶT LẠI MẬT KHẨU</a></p>
                <p>Nếu bạn không yêu cầu, vui lòng bỏ qua email này.</p>
            ";

            $mail->send();
            $_SESSION['success'] = "Đã gửi link khôi phục! Vui lòng kiểm tra Email (cả hộp thư rác).";
        } catch (Exception $e) {
            $_SESSION['error'] = "Không thể gửi mail. Lỗi: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['error'] = "Email này chưa được đăng ký trong hệ thống!";
    }
    
    header("Location: login.php");
    exit();
}
?>