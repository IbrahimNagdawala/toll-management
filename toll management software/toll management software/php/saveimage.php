<?php
	session_start();

	if(isset($_POST['vl_img'])){
		$image = $_POST['vl_img'];
		$arr = explode(",",$image);

		$path = 'vehicle_images/'.date("Y_m_d_H_i_s").'.jpeg';

		$status = file_put_contents($path,base64_decode($arr[1]));
		if($status){
			$_SESSION['vehi_image'] = $path;
		 echo "Successfully Uploaded";
		}else{
		 echo "Upload failed - ".$image;
		}
	}
?>