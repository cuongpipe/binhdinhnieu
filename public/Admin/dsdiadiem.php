<?php
    require "../../app/controller/loadsession.php";
    if ($_SESSION['role'] < 1) {
        header("Location: ./firewall.php");
        exit();
    }

    include_once("../../app/model/DiaDiemDLDAO.php");
    $dao = new DiaDiemDAO();
    $query = "SELECT * FROM diadiem_dulich";
    $dsdd = $dao->get_tatca_DiaDiem($query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý địa điểm du lịch</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/menu-admin.css">
    <link rel="stylesheet" href="../css/dsdiadiem.css">
  
</head>
<body>
<div class="main-container">
    <!-- phần thanh menu bên trái nè -->
    <div class="left-container">
        <?php include "../../app/views/left/menu-admin.php"; ?>
    </div>

    <!-- nội dung chính ở đây này  *****************************-->
    <div class="right-container">
        <div class="container-header">
            <h1> Địa điểm du lịch </h1>
            <a href="themdiadiem.php"> 
            	<button class="btn-add"> + Thêm địa điểm </button>
            </a>
        </div>

		<?php
			while ($item = mysqli_fetch_array($dsdd)) {
				echo '
				<div class="place-card">
					
					<div class="place-img">
						<img src="../images/imgDuLich/' . $item['DiaDiemID'] . '/' . $item['HinhAnh'] . '" 
							 alt="' . $item['TenDiaDiem'] . '">
					</div>

					<div class="place-info">
						<h3>' . $item['TenDiaDiem'] . '</h3>
						<p><b>Địa chỉ:</b> ' . $item['DiaChi'] . '</p>
						
						<div class="btn-group">
							<a href="suadiadiem.php?id=' . $item['DiaDiemID'] . '">
								<button class="btn btn-edit">Sửa</button>
							</a>
							
							<a href="xoadiadiem.php?id=' . $item['DiaDiemID'] . '" class="btn-delete-link">
								<button class="btn btn-delete">Xóa</button>
							</a>
							
							<a href="../DiaDiemDL.php?id=' . $item['DiaDiemID'] . '">
								<button class="btn btn-view">Xem</button>
							</a>
						</div>
						
					</div>
				</div>
				';
			}
		mysqli_free_result($dsdd);
		?>


	
    </div>
</div>

<script src="../scripts/dsdiadiem.js"> </script>
</body>
</html>
