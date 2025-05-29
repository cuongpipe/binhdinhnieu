<!DOCTYPE html>

<?php
    include('../app/model/dulichDAO.php');

    $dlDAO =  new dulichDAO();
    $result = $dlDAO->get_tung_loaihinh($_GET['idloaihinh']);

    if(mysqli_num_rows($result)> 0)
    {
        $row = mysqli_fetch_array($result);
        $tenlh = $row['TenLoaiHinh'];
    }
    mysqli_free_result($result);
?>



<html lang="vi">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="./css/rightmenu-style.css">
      <link rel="stylesheet" href="./css/reset.css">
      <link rel="stylesheet" href="./css/footer-style.css">
      <link rel="stylesheet" href="./css/dulich.css">
      <link rel="stylesheet" href="./css/header-style.css">
      <script src="https://kit.fontawesome.com/8f5e4d2946.js" crossorigin="anonymous"></script>
      <link rel="icon" href="./images/logo.webp" type="image/x-icon">
      <script src="./scripts/homePage.js"></script>
      <title>
            <?php 
                if(isset($tenlh)) echo $tenlh; 
                else echo "Không tìm thấy" 
           ?> 
      </title>
</head>



<?php
    include('../app/views/partials/header.php');
?>

<body>

      <div class="container-loaihinh-dulich">
            <div class="container-left"></div>
            
            <div class="container-mid">
                 <!-- h1 cho tên loại hình -->
				 <h1>
                   <?php 
                       if(isset($tenlh)) echo $tenlh; 
                       else echo "404 Không tìm thấy" 
                   ?>
                  </h1>
				  
                  <div class="container_cacdiadiem_dulich">
                   
				  
				   <?php
				   // hiện từng địa điểm
                    include('../app/model/DiaDiemDLDAO.php');
					$ddDAO = new DiaDiemDAO();
					
			
					//tính và hiện số địa điểm trên 1 trang 
                    $diadiem_tren_1_trang = 5;
                    
                    //kiểm tra trang hiện tại là trang 
                    if (isset($_GET['page'])) {                       
                        $trang_hientai = $_GET['page'];
                    } 
                    else {
                        $trang_hientai = 1;
                    }
                                        
                    $sql = "SELECT * FROM diadiem_dulich WHERE LoaiHinhID = " . $_GET['idloaihinh']. "
                            LIMIT " . $diadiem_tren_1_trang." OFFSET " . ($trang_hientai - 1) * $diadiem_tren_1_trang."";
							
					// LIMIT: số lượng dòng (địa điểm) hiển thị mỗi trang		
				    // OFFSET: xác định bắt đầu lấy từ dòng số bao nhiêu.
                    // nếu dòng đầu thì không bỏ qua dòng nào 
                   
				    $result_dd = $ddDAO->get_tatca_DiaDiem($sql);
                    
                    $sql_tatca_diadiem = "SELECT  * FROM diadiem_dulich WHERE LoaiHinhID = " . $_GET['idloaihinh'];

                    $tong_soluong_diadiem = mysqli_num_rows($ddDAO->get_tatca_DiaDiem($sql_tatca_diadiem)) ;
	

                    $tong_soluong_trang = ceil($tong_soluong_diadiem / $diadiem_tren_1_trang);
                    //ceil()dùng để làm tròn lên, vd: 10 địa điểm / 4 = 2.5 ⇒ cần 3 trang để hiện
     
                   // lấy từng dòng dữ liệu từ kết quả truy vấn $result_dd, 1 dòng dữ liệu này bằng 1 dòng trong database
                   while($row = mysqli_fetch_array($result_dd))
                    {
                        $filename = '../app/dulich_data/'.$row['DiaDiemID'] .'.txt';
                        // $row thì là biến bạn gán từ mysqli_fetch_array(), nó chứa mảng dữ liệu trả về từ cơ sở dữ liệu.
						
                        if (empty($row['HinhAnh'])) {
                             $row['HinhAnh'] = 'default.jpg';
                             $row['TheLoaiID'] = 'default';
                        }

                       echo'
                        <div class="place-card">
                            <a href="./DiaDiemDL.php?id='.$row['DiaDiemID'].'">
                                
                                 <img src="./images/imgDuLich/' . $row['DiaDiemID']. '/' . $row['HinhAnh'] . '" alt="' . $row['HinhAnh'] . '">
                                   
                                  <h2>' . $row['TenDiaDiem'] . '</h2>

                                  <p>' . $row['DiaChi'] . '</p>
                            </a>
                        </div>
                        ';

                    
                    }
                  //giải phóng bộ nhớ
                   mysqli_free_result($result_dd);  

                ?>


                </div>

              <?php
			  
                //nút điều hướng trang
				
                page_navigation($tong_soluong_trang, $trang_hientai);
				
                function page_navigation($tong_soluong_trang, $trang_hientai)
                {
                    echo '<div class="page-navigation">';
                    //trang trước // nếu trang hiện tại lớn hơn 1 thì trở về trang trước bằng trang hiện tại -1 
                    if ($trang_hientai > 1) {
                        echo '<a href="./dulich.php?idloaihinh=' . $_GET["idloaihinh"] . "&page=" . ($trang_hientai - 1) . '"> 
                                < 
                             </a>';
                    }

                    //hiện thị trang liền kề
                    for ($i = 1; $i <= $tong_soluong_trang; $i++) {
                        
                        //nếu mà có trang trước hoặc trang sau thì tiếp tục
                        if ($i < $trang_hientai - 1 || $i > $trang_hientai + 1) {
                            continue;
                        }

                        //vi du: trang_hientai =2 
                        //trang hiện tại trừ là  1 - 1 bé hơn 1 => tiếp tục ? là sao 



                        if ($i == $trang_hientai) {
                            echo "<span>" . $i . "</span>";
                        } 
                        else {
                            echo '<a href="./dulich.php?idloaihinh=' . $_GET["idloaihinh"] . "&page=" . $i . '">'  . $i .  "</a>";
                        }
                    }

                    //trang tiếp theo
                    if ($trang_hientai < $tong_soluong_trang) {
                        echo '<a href="./dulich.php?idloaihinh=' . $_GET["idloaihinh"] . "&page=" . ($trang_hientai + 1) . '">
                                >
                              </a>';
                    }
                    echo "</div>";
                }

             ?>
            </div>
			
            <div class="container-right">
                  <?php
                include("../app/views/right/homepage.php");
            ?>
            </div>
      </div>
</body>
<?php
        include('../app/views/partials/footer.php');
    ?>

</html>