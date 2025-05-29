<?php
    include "../app/model/DiaDiemDLDAO.php";
    $ddDAO = new DiaDiemDAO();
    $iddiadiem = $_GET["id"];
    $result = $ddDAO->get_tung_DiaDiem($iddiadiem);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $tendd = $row["TenDiaDiem"];
    }
    mysqli_free_result($result);
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/reset.css">
  <link rel="stylesheet" href="./css/footer-style.css">
  <link rel="stylesheet" href="./css/header-style.css">
  <link rel="stylesheet" href="./css/DiaDiemDL.css">
  <link rel="stylesheet" href="./css/rightmenu-style.css">
  <script src="https://kit.fontawesome.com/8f5e4d2946.js" crossorigin="anonymous"></script>
  <script src="./scripts/zoom-img.js"> </script>
  <title>
    <?php 
    	  if (isset($tendd)) {
            echo $tendd;
        } 
        else {
            echo "Không tìm thấy";
    } 
    ?>

  </title>

  <link rel="icon" href="./images/logo.webp" type="image/x-icon">
</head>
<?php include "../app/views/partials/header.php"; ?>

<body>

  <div class="container-ctDiaDiem">
    <div class="container-left"> </div>
    <div class="main-container">
     
	 <?php
      require_once "../app/model/DiaDiemDLDAO.php";
      $ddDAO = new DiaDiemDAO();
      $result = $ddDAO->get_tung_DiaDiem($iddiadiem);

      // kiểm tra trong database có tồn tại bài báo với id đưa vào hay k
      if (mysqli_num_rows($result) > 0) {
          
          // vì có 1 dòng nên không cần lặp bằng while	  
		      $rawData = mysqli_fetch_array($result);
          
          //*************lấy tiêu đề từ database, ảnh , địa điểm từ database**************
          // lấy tiêu đề
          echo "<h1> " . $rawData["TenDiaDiem"] . "</h1>";

          // Đọc tệp và kiểm tra từng dòng
          ($f = fopen("../app/dulich_data/" . $iddiadiem . ".txt", "r")) or die("<h1>Không tìm thấy bài báo</h1>");
          
		    
		     //hiện thị ảnh
		     echo '<img class="art-content" src="./images/imgDuLich/' . $rawData["DiaDiemID"] . "/" . $rawData["HinhAnh"] . '" alt="' .$rawData["HinhAnh"] . '">';     
         
         // chữ giới thiệu
         echo "<u> <h2> Giới thiệu: </h2> </u>";
         // hiện chữ địa điểm và địa điểm múc từ databasse
         echo "<h3>" 
                  . "<img src='https://img.icons8.com/?size=100&id=Udrc3LA8OPbn&format=png&color=000000'>" . "<b> Địa chỉ: " . $rawData["DiaChi"] . "</b>" . 
               "</h3>";
					  
         //*************************đọc nội dung từ file************************** 
          while (!feof($f)) {
			  
                $row = fgets($f);
  			        //vÌ cuối dòng không cÓ "\n" nên php sẽ tính mỗi dòng là 1 đoạn luôn             

                // Xóa các khoảng trắng  2 hai bên của dòng
  			        //vd: trim(" hello ") , kq là "hello"
                $row = trim($row);  
                
                // Kiểm tra nếu là dấu chỉ định ảnh
                if ($row == "[img]") {      
                }

                // Duyệt qua tất cả các dòng còn lại trong file
                else {                  
          				 //vÌ cuối dòng khÔng cÓ "\n" ở cuối mỗi dòng =>  nên php sẽ tính mỗi dòng là 1 đoạn luôn
          				 echo "<p>" . $row . "</p>";
                }
          }

          fclose($f);
      }
      else {
        echo "<h1> <b> Không có địa điểm nào với id này" .$iddiadiem. "</b> </h1>";
      }
      ?>
	  
    </div>
    <div class="container-right">
      <?php include "../app/views/right/homepage.php"; ?>
    </div>

  </div>

  <div class="img-overlay">
    <img src="" alt="Hình ảnh phóng to">
  </div>

</body>


<?php include "../app/views/partials/footer.php";
?>
