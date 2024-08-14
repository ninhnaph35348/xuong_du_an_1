<?php

class AdminSanPhamController


{
    public $modelSanPham;
    public $modelDanhMuc;

    public function __construct()
    {
        $this->modelSanPham = new AdminSanPham();
        $this->modelDanhMuc = new AdminDanhMuc();
    }

    public function danhSachSanPham()
    {
        $listSanPham = $this->modelSanPham->getAllSanPham();

        require_once './views/sanpham/listSanPham.php';
    }
    //////////////////  
    //THÊM SAN PHAM//
    ////////////////
    public function formAddSanPham()
    {
        $listDanhMuc = $this->modelDanhMuc->getAllDanhMuc();

        require_once './views/sanpham/addSanPham.php';

        // Xóa session sau khi load lại trang
        deleteSessionError();
        //Hàm Này Dùng để hiện thị form nhập    
    }

    public function postAddSanPham()
    {


        //Hàm Này Dùng Xử lý thêm dữ liệu

        // Kiểm tra xem dữ liệu có phải được submit lên không

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy ra dữ liệu 
            $ten_san_pham = $_POST['ten_san_pham'] ?? '';
            $gia_san_pham = $_POST['gia_san_pham'] ?? '';
            $gia_khuyen_mai = $_POST['gia_khuyen_mai'] ?? '';
            $so_luong = $_POST['so_luong'] ?? '';
            $ngay_nhap = $_POST['ngay_nhap'] ?? '';
            $danh_muc_id = $_POST['danh_muc_id'] ?? '';
            $trang_thai = $_POST['trang_thai'] ?? '';
            $mo_ta = $_POST['mo_ta'] ?? '';


            $hinh_anh = $_FILES['hinh_anh'] ?? null;

            //Lưu hình ảnh vào 
            $file_thumb = uploadFile($hinh_anh, './uploads/');

            //Mảng hình ảnh 
            $imege_array = $_FILES['img_array'];




            // Tạo 1 mảng trống để chứa dữ liệu
            $error = [];
            if (empty($ten_san_pham)) {
                $error['ten_san_pham'] = 'Tên sản phẩm không được để trống';
            }
            if (empty($gia_san_pham)) {
                $error['gia_san_pham'] = 'Giá sản phẩm không được để trống';
            }
            if (empty($gia_khuyen_mai)) {
                $error['gia_khuyen_mai'] = 'Giá khuyến mãi không được để trống';
            }
            if (empty($so_luong)) {
                $error['so_luong'] = 'Số lượng không được để trống';
            }
            if (empty($ngay_nhap)) {
                $error['ngay_nhap'] = 'Ngày nhập không được để trống';
            }
            if (empty($danh_muc_id)) {
                $error['danh_muc_id'] = 'Danh mục sản phẩm phải chọn';
            }
            if (empty($trang_thai)) {
                $error['trang_thai'] = 'Trạng thái sản phẩm phải chọn';
            }
            if ($hinh_anh['error'] !== 0) {
                $error['hinh_anh'] = 'Phải chọn ảnh sản phẩm';
            }

            $_SESSION['error'] = $error;
            // Nếu không có lỗi thì tiến hành thêm sản phẩm
            if (empty($error)) {
                // Nếu không có lỗi thì tiến hành thêm sản phẩm
                // var_dump('okkk');
                $san_pham_id = $this->modelSanPham->insertSanPham(
                    $ten_san_pham,
                    $gia_san_pham,
                    $gia_khuyen_mai,
                    $so_luong,
                    $ngay_nhap,
                    $danh_muc_id,
                    $trang_thai,
                    $mo_ta,
                    $file_thumb
                );
                // Xử lý thêm album ảnh sản phẩm img_array

                if (!empty($imege_array['name'])) {
                    foreach ($imege_array['name'] as $key => $value) {
                        $file = [
                            'name' => $imege_array['name'][$key],
                            'type' => $imege_array['type'][$key],
                            'tmp_name' => $imege_array['tmp_name'][$key],
                            'error' => $imege_array['error'][$key],
                            'size' => $imege_array['size'][$key],
                        ];

                        $link_hinh_anh = uploadFile($file, './uploads/');
                        $this->modelSanPham->insertAlbumAnhSanPham($san_pham_id, $link_hinh_anh);
                    }
                }

                header("Location: "  . BASE_URL_ADMIN . "?act=san-pham");
                exit();
            } else {
                //Trả về form
                // Đặt chỉ thị xóa session sau khi hiển thị form 

                $_SESSION['flash'] = true;
                header("Location: "  . BASE_URL_ADMIN . "?act=form-them-san-pham");
            }
        }
    }

    //////////////////
    //SỬA sản phẩm//
    ////////////////

    public function formEditSanPham()
    {

        // Lấy ra thông tin của danh mục cần sửa
        $id = $_GET['id_san_pham'];
        $sanPham = $this->modelSanPham->getDetailSanPham($id);
        $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);
        $listDanhMuc = $this->modelDanhMuc->getAllDanhMuc();
        if ($sanPham) {
            require_once './views/sanpham/editSanPham.php';
            deleteSessionError();
        } else {
            header("Location: "  . BASE_URL_ADMIN . "?act=san-pham");
            exit();
        }


        //Hàm Này Dùng để hiện thị form nhập    
    }
    public function postEditSanPham()
    {


        //Hàm Này Dùng Xử lý thêm dữ liệu

        // Kiểm tra xem dữ liệu có phải được submit lên không

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy ra dữ liệu 
            // Lấy ra dữ liêu của sản phẩm
            $san_pham_id = $_POST['san_pham_id'] ?? '';

            // Truy vấn 

            $sanPhamOld = $this->modelSanPham->getDetailSanPham($san_pham_id);
            $old_file = $sanPhamOld['hinh_anh']; // Lấy ảnh cũ để phục vụ cho sửa ảnh


            $ten_san_pham = $_POST['ten_san_pham'] ?? '';
            $gia_san_pham = $_POST['gia_san_pham'] ?? '';
            $gia_khuyen_mai = $_POST['gia_khuyen_mai'] ?? '';
            $so_luong = $_POST['so_luong'] ?? '';
            $ngay_nhap = $_POST['ngay_nhap'] ?? '';
            $danh_muc_id = $_POST['danh_muc_id'] ?? '';
            $trang_thai = $_POST['trang_thai'] ?? '';
            $mo_ta = $_POST['mo_ta'] ?? '';

            $hinh_anh = $_FILES['hinh_anh'] ?? null;


            // Tạo 1 mảng trống để chứa dữ liệu
            $error = [];
            if (empty($ten_san_pham)) {
                $error['ten_san_pham'] = 'Tên sản phẩm không được để trống';
            }
            if (empty($gia_san_pham)) {
                $error['gia_san_pham'] = 'Giá sản phẩm không được để trống';
            }
            if (empty($gia_khuyen_mai)) {
                $error['gia_khuyen_mai'] = 'Giá khuyến mãi không được để trống';
            }
            if (empty($so_luong)) {
                $error['so_luong'] = 'Số lượng không được để trống';
            }
            if (empty($ngay_nhap)) {
                $error['ngay_nhap'] = 'Ngày nhập không được để trống';
            }
            if (empty($danh_muc_id)) {
                $error['danh_muc_id'] = 'Danh mục sản phẩm phải chọn';
            }
            if (empty($trang_thai)) {
                $error['trang_thai'] = 'Trạng thái sản phẩm phải chọn';
            }
            if ($hinh_anh['error'] !== 0) {
                $error['hinh_anh'] = 'Phải chọn ảnh sản phẩm';
            }

            $_SESSION['error'] = $error;


            // logic sửa anh 
            if (isset($hinh_anh) && $hinh_anh['error'] == UPLOAD_ERR_OK) {

                // upload ảnh mới lên 

                $new_file = uploadFile($hinh_anh, './uploads/');

                if (!empty($old_file)) {

                    deleteFile($old_file);
                } else {
                    $new_file = $old_file;
                }
            }
            // Nếu không có lỗi thì tiến hành thêm sản phẩm
            if (empty($error)) {
                // Nếu không có lỗi thì tiến hành thêm sản phẩm
                // var_dump('okkk');
                $san_pham_id = $this->modelSanPham->updateSanPham(
                    $san_pham_id,
                    $ten_san_pham,
                    $gia_san_pham,
                    $gia_khuyen_mai,
                    $so_luong,
                    $ngay_nhap,
                    $danh_muc_id,
                    $trang_thai,
                    $mo_ta,
                    $new_file
                );
                header("Location: "  . BASE_URL_ADMIN . "?act=san-pham");
                exit();
            } else {
                //Trả về form
                // Đặt chỉ thị xóa session sau khi hiển thị form 

                $_SESSION['flash'] = true;
                header("Location: "  . BASE_URL_ADMIN . "?act=form-sua-san-pham&id_san_pham=" . $san_pham_id);
                exit();
            }
        }
    }

    // Sửa album ảnh
    // Sửa ảnh cũ
    // + Thêm ảnh mới
    // + Không thêm ảnh mới
    // Không sửa ảnh cũ
    // + Thêm ảnh mới
    // + Không thêm ảnh mới
    // Xóa ảnh cũ
    // + Thêm ảnh mới
    // + Không thêm ảnh mới

    public function postEditAnhSanPham()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $san_pham_id = $_POST['san_pham_id'] ?? '';

            // Lấy danh sách ảnh hiện tại của sản phẩm 

            $listAnhSanPhamCurrent = $this->modelSanPham->getListAnhSanPham($san_pham_id);

            // Xử lý các ảnh được gửi từ form 

            $img_array = $_FILES['img_array'];

            $img_delete = isset($_POST['img_delete']) ? explode(',', $_POST['img_delete']) : [];
            $current_img_ids = $_POST['current_img_ids'] ?? [];

            // Khai báo ảnh để lưu ảnh thêm mới hoặc thay thế ảnh cũ

            $upload_file = [];

            // Upload ảnh mới hoặc thay thế ảnh cũ

            foreach ($img_array['name'] as $key => $value) {
                if ($img_array['error'][$key] == UPLOAD_ERR_OK) {

                    $new_file = uploadFileAlbum($img_array, './uploads/', $key);
                    if ($new_file) {
                        $upload_file[] = [
                            'id' => $current_img_ids[$key] ?? null,
                            'file' => $new_file

                        ];
                    }
                }
            }

            // Lưu ảnh vào db và xóa ảnh cũ nếu có 
            foreach ($upload_file as $file_info) {
                if ($file_info['id']) {
                    $old_file = $this->modelSanPham->getDetailAnhSanPham($file_info['id'])['link_hinh_anh'];

                    // cập nhật ảnh cũ 
                    $this->modelSanPham->updateAnhSanPham($file_info['id'], $file_info['file']);

                    // xóa ảnh cũ

                    deleteFile($old_file);
                } else {
                    // Thêm ảnh mới
                    $this->modelSanPham->insertAlbumAnhSanPham($san_pham_id, $file_info['file']);
                }
            }

            // Xử lý xóa ảnh 
            foreach ($listAnhSanPhamCurrent as $anhSP) {
                $anh_id = $anhSP['id'];
                if (in_array($anh_id, $img_delete)) {
                    // Xóa ảnh trong db
                    $this->modelSanPham->destroyAnhSanPham($anh_id);

                    // Xóa file 

                    deleteFile($anhSP['link_hinh_anh']);
                }
            }

            header("Location: "  . BASE_URL_ADMIN . "?act=form-sua-san-pham&id_san_pham=" . $san_pham_id);
            exit();
        }
    }
    public function deleteSanPham()
    {
        $id = $_GET['id_san_pham'];
        $sanPham = $this->modelSanPham->getDetailSanPham($id);

        $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);

        if ($sanPham) {
            deleteFile($sanPham['hinh_anh']);
            $this->modelSanPham->destroySanPham($id);
        }

        if ($listAnhSanPham) {
            foreach ($listAnhSanPham as $key => $anhSP) {
                deleteFile($anhSP['link_hinh_anh']);
                $this->modelSanPham->destroyAnhSanPham($anhSP['id']);
            }
        }
        header("Location: " . BASE_URL_ADMIN . '?act=san-pham');
        exit();
    }

    public function detailSanPham()
    {
        $id = $_GET['id_san_pham'];

        $sanPham = $this->modelSanPham->getDetailSanPham($id);

        $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);

        $listBinhLuan = $this->modelSanPham->getBinhLuanFromSanPham($id);

        if ($sanPham) {
            require_once './views/sanpham/detailSanPham.php';
        } else {
            header("Location: "  . BASE_URL_ADMIN . "?act=san-pham");
            exit();
        }


        //Hàm Này Dùng để hiện thị form nhập    
    }

    public function updateTrangThaiBinhLuan()
    {
        $id_binh_luan = $_POST['id_binh_luan'];
        $name_view = $_POST['name_view'];
        $id_khach_hang = $_POST['id_khach_hang'];
        $binhLuan = $this->modelSanPham->getDetailBinhLuan($id_binh_luan);

        if ($binhLuan) {
            $trang_thai_update = '';
            if ($binhLuan['trang_thai'] == 1) {
                $trang_thai_update = 2;
            } else {
                $trang_thai_update = 1;
            }
            $status = $this->modelSanPham->updateTrangThaiBinhLuan($id_binh_luan, $trang_thai_update);

            if ($status) {
                if ($name_view == 'detail_khach') {
                    header("Location: "  . BASE_URL_ADMIN . "?act=chi-tiet-khach-hang&id_khach_hang=" . $binhLuan['tai_khoan_id']);
                } else {
                    header("Location: "  . BASE_URL_ADMIN . "?act=chi-tiet-san-pham&id_san_pham=" . $binhLuan['san_pham_id']);
                }
            }
        }
    }
}
