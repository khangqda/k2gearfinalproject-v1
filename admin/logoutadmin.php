<?php
// Bắt buộc phải start session thì mới có cái để mà hủy
session_start();

// Xóa tất cả các biến trong session (admin_id, admin_name...)
session_unset();

// Phá hủy hoàn toàn phiên làm việc
session_destroy();

// Đá người dùng về lại trang đăng nhập
header("Location: loginadmin.php");
exit();
?>
