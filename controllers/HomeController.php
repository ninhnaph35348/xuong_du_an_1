<?php

class HomeController
{
    public $modelSanPham;
    public $modelTaiKhoan;

    public function __construct()
    {
        $this->modelSanPham = new SanPham();
        $this->modelTaiKhoan = new TaiKhoan();
    }
    public function home()
    {
        $listSanPham = $this->modelSanPham->getAllSanPham();

        require_once "./views/home.php";
    }

    public function chiTietSanPham()
    {
        $id = $_GET['id_san_pham'];

        $sanPham = $this->modelSanPham->getDetailSanPham($id);

        $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);

        $listBinhLuan = $this->modelSanPham->getBinhLuanFromSanPham($id);

        $listSanPhamCungDanhMuc = $this->modelSanPham->getListSanPhamDanhMuc($sanPham['danh_muc_id']);

        if ($sanPham) {
            require_once 'views/detailSanPham.php';
        } else {
            header("Location: "  . BASE_URL);
            exit();
        }
    }

    public function formLogin()
    {
        require_once './views/auth/formLogin.php';

        deleteSessionError();
    }

    public function postlogin() 
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // lấy email và pass gửi lên từ form

            $email = $_POST['email'];
            $password = $_POST['password'];

            // var_dump($email);
            // die();

            // Xử Lý kiểm tra thông tin đăng nhập

            $user = $this->modelTaiKhoan->checkLogin($email, $password);


            if ($user == $email) { // Trường hợp đăng nhập thành công
                // Lưu thông tin vào session 
                $_SESSION['user_client'] = $user;

                header("Location:" . BASE_URL);
                exit();
            } else {
                // Lỗi thì lưu vào session 
                $_SESSION['error'] = $user;
                // var_dump($_SESSION['error']);die();
                $_SESSION['flash'] = true;

                header("Location:" . BASE_URL . '?act=login');
                exit();
            }
        }
    }
}
