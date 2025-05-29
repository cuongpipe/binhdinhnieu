<?php
    require "../../app/controller/loadsession.php";
    if ($_SESSION['role'] < 1) {
        header("Location: ./firewall.php");
        exit();
    }

    require_once("../../app/model/DiaDiemDLDAO.php");
    require_once("../../app/model/dulichDAO.php");

    $dddao = new DiaDiemDAO();
    $dlDAO = new dulichDAO();

    if (!isset($_GET['id'])) {
        die("Thiếu ID địa điểm.");
    }

    $id = $_GET['id'];
    $result = $dddao->get_tung_DiaDiem($id);
    $row_diaDiem = mysqli_fetch_array($result);
    mysqli_free_result($result);

    if (!$row_diaDiem) {
        die("Không tìm thấy địa điểm.");
    }

    // Đọc mô tả hiện có
    $txtPath = "../../app/dulich_data/" . $id . ".txt";
    if (file_exists($txtPath)) {
        $mota = file_get_contents($txtPath);
    } else {
        $mota = "";
    }



    // Xử lý khi nhấn "Lưu"
    if (isset($_POST['luu'])) {
        $ten = $_POST['tendiadiem'];
        $diachi = $_POST['diachi'];
        $loaihinhid = $_POST['loaihinhid'];
        $mota = $_POST['mota'];
        $tenfile = $diaDiem['HinhAnh'];
        
        if ($_FILES['hinhanh']['name']) {
            $tenfile = $_FILES['hinhanh']['name'];
            $uploadDir = "../../public/images/imgDuLich/" . $id;
            // kiểm tra file có tồn tại
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $path = $uploadDir . "/" . basename($tenfile);
            move_uploaded_file($_FILES['hinhanh']['tmp_name'], $path);
        }


        $dddao->capnhat_diadiem($id, $ten, $diachi, $loaihinhid, $tenfile);

        // Ghi lại mô tả
        file_put_contents($txtPath, $mota);

        header("Location: dsdiadiem.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa địa điểm</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/menu-admin.css">
    <link rel="stylesheet" href="../css/suadiadiem.css">
</head>
<body>
<div class="main-container">
    <div class="left-container">
        <?php include "../../app/views/left/menu-admin.php"; ?>
    </div>
    <div class="right-container">
        <div class="form-container">
            <h1>Sửa địa điểm du lịch</h1>

            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="tendiadiem" value="<?php echo $diaDiem['TenDiaDiem']; ?>" required>
                <input type="text" name="diachi" value="<?php echo $diaDiem['DiaChi']; ?>" required>

                <select name="loaihinhid" required>
                    <?php
                        
                        $result = $dlDAO->get_tatca_loaihinh();
                        while ($row = mysqli_fetch_array($result)) {
                             
                            $selected = '';
                            // Nếu LoaiHinhID của dòng hiện tại trong danh sách loại hình du lịch)
                            //selected là khi nó trùng với giá trị LoaiHinhID của địa điểm đang được hiển thị
                            
                            if ($row['LoaiHinhID'] == $row_diaDiem['LoaiHinhID']) {
                                $selected = 'selected';
                            }

                            echo ' <option value=" '. $row['LoaiHinhID'] . '"   '. $selected. ' > '. $row['TenLoaiHinh'] .' </option> ';
                        }

                        // giải phóng bộ nhớ mà MySQLi đã sử dụng để lưu kết quả truy vấn
                        mysqli_free_result($result);
                        // $result là một "bộ lưu trữ" dữ liệu trả về từ truy vấn.
                    ?>
                </select>

                <div class="up_anh">
                    <label>Chọn ảnh mới (nếu muốn):</label>
                    <input type="file" name="hinhanh">
                </div>

                <textarea name="mota" rows="8" required> <?php echo $mota ?> </textarea>

                <button type="submit" name="luu">Lưu thay đổi</button>
            </form>

        </div>
    </div>
</div>
</body>
</html>
