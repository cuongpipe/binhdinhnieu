<?php
    // Gọi thư viện đã xây sẵn
    require_once $_SERVER['DOCUMENT_ROOT']. '/BinhDinhNews/app/config/database.php';
    class DiaDiemDAO{
        
        // get connection thông qua lớp Database Connection để tiện lợi hơn
        function getConnection()
        {
            $dbConnect =  new DatabaseConnection();
            return $dbConnect->getConnection();
        }
      
	  
        // lấy dữ liệu tất cả bài báo và trả về
        function get_tatca_DiaDiem($query)
        {
            // get connection
            $conn = $this->getConnection();
            // thực hiện query từ chuỗi query tham số
            $kqua = mysqli_query($conn,$query);
            // đóng kết nối
            mysqli_close($conn);

            return $kqua;
        }
		
        //lấy dữ liệu bài báo và trả về
        function get_tung_DiaDiem($id)
        {
            $conn = $this->getConnection();
            $sql = "SELECT * FROM `diadiem_dulich` WHERE diadiemID = ".$id."";
            $kqua = mysqli_query($conn, $sql);
            // mysqli_close($conn);
            return $kqua;
        }   

		function them_DiaDiem_va_lay_ID($ten, $diachi, $loaihinh, $hinhanh) {
				$conn = $this->getConnection();

				$sql = "INSERT INTO diadiem_dulich (TenDiaDiem, DiaChi, LoaiHinhID, HinhAnh) VALUES ('$ten', '$diachi', $loaihinh, '$hinhanh')";
                // thực thi lệnh truy vấn sql
                mysqli_query($conn, $sql);
                
                // lấy id từ cột vừa thêm có kiểu dữ liệu là AUTO_INCREMENT để trả về
				$id = mysqli_insert_id($conn);
                // đóng kết nối
                mysqli_close($conn);
                // trả về id
				return $id;
		}		

        public function xoa_diadiem($id)
        {
            $conn = $this->getConnection();
            $sql = "DELETE FROM diadiem_dulich WHERE DiaDiemID = ".$id."";
            $kqua = mysqli_query($conn, $sql);
            mysqli_close($conn);
            return $kqua;
        }   



		
		public function capnhat_diadiem($id, $ten, $diachi, $loaihinhid, $hinhanh) {
			$conn = $this->getConnection();
			$sql = "UPDATE diadiem_dulich SET TenDiaDiem = '$ten' , DiaChi = '$diachi', LoaiHinhID = '$loaihinhid', HinhAnh = '$hinhanh' WHERE DiaDiemID = ".$id."";
            mysqli_query($conn, $sql);
			mysqli_close($conn);

		}


    }
?>