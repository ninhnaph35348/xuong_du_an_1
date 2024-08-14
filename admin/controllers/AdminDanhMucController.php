<?php

class AdminDanhMucController


{
    public $modelDanhMuc;

    public function __construct()
    {
        $this->modelDanhMuc = new AdminDanhMuc();
    }

    public function danhSachDanhMuc()
    {
        $listDanhMuc = $this->modelDanhMuc->getAllDanhMuc();

        require_once './views/danhmuc/listDanhMuc.php';
    }
    //////////////////
    //THÊM DANH MỤC//
    ////////////////
    public function formAddDanhMuc()
    {
        require_once './views/danhmuc/addDanhMuc.php';

        //Hàm Này Dùng để hiện thị form nhập    
    }

    public function postDanhMuc()
    {


        //Hàm Này Dùng Xử lý thêm dữ liệu

        // Kiểm tra xem dữ liệu có phải được submit lên không

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy ra dữ liệu 
            $ten_danh_muc = $_POST['ten_danh_muc'];
            $mo_ta = $_POST['mo_ta'];

            // Tạo 1 mảng trống để chứa dữ liệu
            $error = [];
            if (empty($ten_danh_muc)) {
                $error['ten_danh_muc'] = 'Tên danh mục không được để trống';
            }
            // Nếu không có lỗi thì tiến hành thêm danh mục
            if (empty($error)) {
                // Nếu không có lỗi thì tiến hành thêm danh mục
                // var_dump('okkk');
                $this->modelDanhMuc->insertDanhMuc($ten_danh_muc, $mo_ta);
                header("Location: "  . BASE_URL_ADMIN . "?act=danh-muc");
                exit();
            } else {
                //Trả về form
                require_once './views/danhmuc/addDanhMuc.php';
            }
        }
    }

    //////////////////
    //SỬA DANH MỤC//
    ////////////////

    public function formEditDanhMuc()
    {

        // Lấy ra thông tin của danh mục cần sửa
        $id = $_GET['id_danh_muc'];
        $danhMuc = $this->modelDanhMuc->getDetailDanhMuc($id);
        if ($danhMuc) {
            require_once './views/danhmuc/editDanhMuc.php';
        } else {
            header("Location: "  . BASE_URL_ADMIN . "?act=danh-muc");
            exit();
        }


        //Hàm Này Dùng để hiện thị form nhập    
    }

    public function postEditDanhMuc()
    {


        //Hàm Này Dùng Xử lý thêm dữ liệu

        // Kiểm tra xem dữ liệu có phải được submit lên không

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy ra dữ liệu 
            $id = $_POST['id'];
            $ten_danh_muc = $_POST['ten_danh_muc'];
            $mo_ta = $_POST['mo_ta'];

            // Tạo 1 mảng trống để chứa dữ liệu
            $error = [];
            if (empty($ten_danh_muc)) {
                $error['ten_danh_muc'] = 'Tên danh mục không được để trống';
            }
            // Nếu không có lỗi thì tiến hành thêm danh mục
            if (empty($error)) {
                // Nếu không có lỗi thì tiến hành thêm danh mục
                // var_dump('okkk');
                $this->modelDanhMuc->updatetDanhMuc($id, $ten_danh_muc, $mo_ta);
                header("Location: "  . BASE_URL_ADMIN . "?act=danh-muc");
                exit();
            } else {
                //Trả về form vầ lỗi
                $danhMuc = ['id' => $id, 'ten_danh_muc' => $ten_danh_muc, 'mo_ta' => $mo_ta];
                require_once './views/danhmuc/editDanhMuc.php';
            }
        }
    }

    public function deleteDanhMuc()
    {
        $id = $_GET['id_danh_muc'];
        $danhMuc = $this->modelDanhMuc->getDetailDanhMuc($id);

        if ($danhMuc) {
            $this->modelDanhMuc->destroytDanhMuc($id);
        }
        header("Location: " . BASE_URL_ADMIN . '?act=danh-muc');
        exit();
    }
}
