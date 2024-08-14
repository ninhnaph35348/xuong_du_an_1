<?php

class TaiKhoan
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function checkLogin($email, $mat_khau)
    {
        try {
            $sql = 'SELECT * FROM tai_khoans WHERE email = :email';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($mat_khau, $user['mat_khau'])) {

                if ($user['chuc_vu_id'] == 2) {
                    if ($user['trang_thai'] == 1) {
                        return $user['email']; // Trường Hợp đăng nhập thành công
                    } else {
                        return "Tài Khoản Bị Cấm";
                    }
                } else {
                    return "Tài Khoản Không Có Quyền Đăng Nhập";
                }
            } else {
                return "Bạn Nhập Sai Thông Tin Email Hoặc Mật Khẩu";
            }
        } catch (\Exception $e) {
            echo 'Lỗi' . $e->getMessage();
            return false;
        }
    }
}
