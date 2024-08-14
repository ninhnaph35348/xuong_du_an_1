<!-- Header  -->
<?php include './views/layout/header.php'; ?>
<!-- End Header  -->
<!-- Navbar -->
<?php include './views/layout/navbar.php'; ?>

<!-- /.navbar -->

<!-- Main Sidebar Container -->
<?php include './views/layout/sidebar.php'; ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Quản Danh Sách Thú Cưng</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card card-solid">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="col-12">
                            <img style="width:550px; height:500px;" src="<?= BASE_URL . $sanPham['hinh_anh'] ?>" class="product-image" alt="Product Image">
                        </div>
                        <div class="col-12 product-image-thumbs">
                            <?php foreach ($listAnhSanPham as $key => $anhSP) : ?>
                                <div class="product-image-thumb <?= $anhSP[$key] == 0 ? 'active' : ''; ?>"><img src="<?= BASE_URL . $anhSP['link_hinh_anh'] ?>" alt="Product Image"></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <h3 class="my-3">Tên Sản Phẩm: <?= $sanPham['ten_san_pham'] ?>
                            <hr>
                            <h4 class="mt-3">Giá tiền: <small><?= $sanPham['gia_san_pham'] ?></small></h4>
                            <h4 class="mt-3">Khuyến Mãi: <small><?= $sanPham['gia_khuyen_mai'] ?></small></h4>
                            <h4 class="mt-3">Số Lượng: <small><?= $sanPham['gia_san_pham'] ?></small></h4>
                            <h4 class="mt-3">Lượt Xem: <small><?= $sanPham['luot_xem'] ?></small></h4>
                            <h4 class="mt-3">Ngày Nhập: <small><?= $sanPham['ngay_nhap'] ?></small></h4>
                            <h4 class="mt-3">Danh Mục: <small><?= $sanPham['ten_danh_muc'] ?></small></h4>
                            <h4 class="mt-3">Trang Thái: <small><?= $sanPham['trang_thai'] == 1 ? 'Còn Bán' : 'Dừng bán' ?></small></h4>
                            <h4 class="mt-3">Mô Tả: <small><?= $sanPham['mo_ta'] ?></small></h4>

                    </div>
                </div>
                <div class="container mt-3">

                    <div class="col-12">
                        <hr>
                        <h2>Bình Luận Của Sản Phẩm</h2>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Người Bình Luận</th>
                                    <th>Nội Dung</th>
                                    <th>Ngày Bình Luận</th>
                                    <th>Trạng Thái</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listBinhLuan as $key => $binhLuan) : ?>
                                    <tr>
                                        <td><?= $key + 1  ?></td>
                                        <td>
                                            <a target="_blank" href="<?= BASE_URL_ADMIN . '?act=chi-tiet-khach-hang&id_khach_hang=' . $binhLuan['tai_khoan_id'] ?>">
                                                <?= $binhLuan['ho_ten']  ?>
                                            </a>
                                        </td>
                                        <td><?= $binhLuan['noi_dung']  ?></td>
                                        <td><?= $binhLuan['ngay_dang']  ?></td>
                                        <td><?= $binhLuan['trang_thai'] == 1 ? "Hiện Thị" : "Bị Ẩn" ?></td>
                                        <td>
                                            <form action="<?= BASE_URL_ADMIN . '?act=update-trang-thai-binh-luan' ?>" method="POST">

                                                <input type="hidden" name="id_binh_luan" value="<?= $binhLuan['id'] ?>">
                                                <input type="hidden" name="name_view" value="detail_san_pham">
                                                <button onclick="return confirm('Bạn có muốn ẩn bình luận này không')" class="btn btn-secondary">
                                                    <?= $binhLuan['trang_thai'] == 1 ? 'Ẩn' : 'Bỏ Ẩn' ?>
                                                </button>

                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- Footer  -->
<?php include './views/layout/footer.php'; ?>
<!-- End Footer  -->
<!-- Page specific script -->
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
<!-- Code injected by live-server -->

</body>

</html>
<script>
    $(document).ready(function() {
        $('.product-image-thumb').on('click', function() {
            var $image_element = $(this).find('img')
            $('.product-image').prop('src', $image_element.attr('src'))
            $('.product-image-thumb.active').removeClass('active')
            $(this).addClass('active')
        })
    })
</script>