<?php

class AdminDonHangController


{
    public $modelDonHang;

    public function __construct()
    {
        $this->modelDonHang = new AdminDonHang();
    }

    public function danhSachDonHang()
    {
        $listDonHang = $this->modelDonHang->getAllDonHang();

        require_once './views/donhang/listDonHang.php';
    }


    public function detailDonHang()
    {
        $don_hang_id = $_GET['id_don_hang'];

        // Lấy thông tin đơn hàng ở bản don_hangs
        $donHang = $this->modelDonHang->getDetailDonHang($don_hang_id);

        // Lấy danh sách sản phẩm của đơn hàng ở bảng chi_tiet_don_hangs

        $sanPhamDonHang = $this->modelDonHang->getListSpDonHang($don_hang_id);

        $listTrangThaiDonHang = $this->modelDonHang->getAllTrangThaiDonHang();

        require_once './views/donhang/detailDonHang.php';
    }
    //////////////////
    //SỬA Đơn Hàng//
    ////////////////

    public function formEditDonHang()
    {
        $id = $_GET['id_don_hang'];
        $donHang = $this->modelDonHang->getDetailDonHang($id);
        $listTrangThaiDonHang = $this->modelDonHang->getAllTrangThaiDonHang();
        if ($donHang) {
            require_once './views/donhang/editDonHang.php';
            deleteSessionError();
        } else {
            header("Location: "  . BASE_URL_ADMIN . "?act=don-hang");
            exit();
        }
    }
    public function postEditDonHang()
    {


        //Hàm Này Dùng Xử lý thêm dữ liệu

        // Kiểm tra xem dữ liệu có phải được submit lên không

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy ra dữ liệu 
            // Lấy ra dữ liêu của sản phẩm
            $don_hang_id = $_POST['don_hang_id'] ?? '';


            $ten_nguoi_nhan = $_POST['ten_nguoi_nhan'] ?? '';
            $email_nguoi_nhan = $_POST['email_nguoi_nhan'] ?? '';
            $sdt_nguoi_nhan = $_POST['sdt_nguoi_nhan'] ?? '';
            $dia_chi_nguoi_nhan = $_POST['dia_chi_nguoi_nhan'] ?? '';
            $ghi_chu = $_POST['ghi_chu'] ?? '';
            $trang_thai_id = $_POST['trang_thai_id'] ?? '';



            // Tạo 1 mảng trống để chứa dữ liệu
            $error = [];
            if (empty($ten_nguoi_nhan)) {
                $error['ten_nguoi_nhan'] = 'Tên người nhận không được để trống';
            }
            if (empty($email_nguoi_nhan)) {
                $error['email_nguoi_nhan'] = 'Email không được để trống';
            }
            if (empty($sdt_nguoi_nhan)) {
                $error['sdt_nguoi_nhan'] = 'Số điện thoại không được để trống';
            }
            if (empty($dia_chi_nguoi_nhan)) {
                $error['dia_chi_nguoi_nhan'] = 'Địa chỉ không được để trống';
            }
            if (empty($trang_thai_id)) {
                $error['trang_thai_id'] = 'Trạng thái đơn hàng phải chọn';
            }

            $_SESSION['error'] = $error;

            // var_dump($error);
            // die();

            // Nếu không có lỗi thì tiến hành Sửa
            if (empty($error)) {
                // Nếu không có lỗi thì tiến hành Sửa
                // var_dump('okkk');
                $this->modelDonHang->updateDonHang(
                    $don_hang_id,
                    $ten_nguoi_nhan,
                    $email_nguoi_nhan,
                    $sdt_nguoi_nhan,
                    $dia_chi_nguoi_nhan,
                    $ghi_chu,
                    $trang_thai_id
                );
                header("Location: "  . BASE_URL_ADMIN . "?act=don-hang");
                exit();
            } else {
                //Trả về form
                // Đặt chỉ thị xóa session sau khi hiển thị form 

                $_SESSION['flash'] = true;
                header("Location: "  . BASE_URL_ADMIN . "?act=form-sua-don-hang&id_don_hang=" . $don_hang_id);
                exit();
            }
        }
    }

    // // Sửa album ảnh
    // // Sửa ảnh cũ
    // // + Thêm ảnh mới
    // // + Không thêm ảnh mới
    // // Không sửa ảnh cũ
    // // + Thêm ảnh mới
    // // + Không thêm ảnh mới
    // // Xóa ảnh cũ
    // // + Thêm ảnh mới
    // // + Không thêm ảnh mới

    // public function postEditAnhSanPham()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $san_pham_id = $_POST['san_pham_id'] ?? '';

    //         // Lấy danh sách ảnh hiện tại của sản phẩm 

    //         $listAnhSanPhamCurrent = $this->modelSanPham->getListAnhSanPham($san_pham_id);

    //         // Xử lý các ảnh được gửi từ form 

    //         $img_array = $_FILES['img_array'];

    //         $img_delete = isset($_POST['img_delete']) ? explode(',', $_POST['img_delete']) : [];
    //         $current_img_ids = $_POST['current_img_ids'] ?? [];

    //         // Khai báo ảnh để lưu ảnh thêm mới hoặc thay thế ảnh cũ

    //         $upload_file = [];

    //         // Upload ảnh mới hoặc thay thế ảnh cũ

    //         foreach ($img_array['name'] as $key => $value) {
    //             if ($img_array['error'][$key] == UPLOAD_ERR_OK) {

    //                 $new_file = uploadFileAlbum($img_array, './uploads/', $key);
    //                 if ($new_file) {
    //                     $upload_file[] = [
    //                         'id' => $current_img_ids[$key] ?? null,
    //                         'file' => $new_file

    //                     ];
    //                 }
    //             }
    //         }

    //         // Lưu ảnh vào db và xóa ảnh cũ nếu có 
    //         foreach ($upload_file as $file_info) {
    //             if ($file_info['id']) {
    //                 $old_file = $this->modelSanPham->getDetailAnhSanPham($file_info['id'])['link_hinh_anh'];

    //                 // cập nhật ảnh cũ 
    //                 $this->modelSanPham->updateAnhSanPham($file_info['id'], $file_info['file']);

    //                 // xóa ảnh cũ

    //                 deleteFile($old_file);
    //             } else {
    //                 // Thêm ảnh mới
    //                 $this->modelSanPham->insertAlbumAnhSanPham($san_pham_id, $file_info['file']);
    //             }
    //         }

    //         // Xử lý xóa ảnh 
    //         foreach ($listAnhSanPhamCurrent as $anhSP) {
    //             $anh_id = $anhSP['id'];
    //             if (in_array($anh_id, $img_delete)) {
    //                 // Xóa ảnh trong db
    //                 $this->modelSanPham->destroyAnhSanPham($anh_id);

    //                 // Xóa file 

    //                 deleteFile($anhSP['link_hinh_anh']);
    //             }
    //         }

    //         header("Location: "  . BASE_URL_ADMIN . "?act=form-sua-san-pham&id_san_pham=" . $san_pham_id);
    //         exit();
    //     }
    // }
    // public function deleteSanPham()
    // {
    //     $id = $_GET['id_san_pham'];
    //     $sanPham = $this->modelSanPham->getDetailSanPham($id);

    //     $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);


    //     if ($sanPham) {
    //         deleteFile($sanPham['hinh_anh']);
    //         $this->modelSanPham->destroySanPham($id);
    //     }

    //     if ($listAnhSanPham) {
    //         foreach ($listAnhSanPham as $key => $anhSP) {
    //             deleteFile($anhSP['link_hinh_anh']);
    //             $this->modelSanPham->destroyAnhSanPham($anhSP['id']);
    //         }
    //     }
    //     header("Location: " . BASE_URL_ADMIN . '?act=san-pham');
    //     exit();
    // }

    // public function detailSanPham()
    // {
    //     $id = $_GET['id_san_pham'];

    //     $sanPham = $this->modelSanPham->getDetailSanPham($id);

    //     $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);

    //     if ($sanPham) {
    //         require_once './views/sanpham/detailSanPham.php';
    //     } else {
    //         header("Location: "  . BASE_URL_ADMIN . "?act=san-pham");
    //         exit();
    //     }


    //     //Hàm Này Dùng để hiện thị form nhập    
    // }
}
