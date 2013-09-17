<?php
namespace tvos\autorun;

class picAddpic{
	
	public function resizeImage($im,$name,$filetype = ".png"){
		$pic_width = imagesx($im);
		$pic_height = imagesy($im);
		$newwidth = 224;
		$newheight = 225;
		$startPoint = 39;
		$endPoint = 38;
		$backPicIm = imagecreatefrompng("http://pic.skysrt.com/img/logo/category/HD.png");
		if(function_exists("imagecopyresampled"))
		{
			imagealphablending($backPicIm, true);
			imagesavealpha($backPicIm, true);
			$trans_colour = imagecolorallocatealpha($backPicIm, 0, 0, 0, 127);
			imagefill($backPicIm, 0, 0, $trans_colour);
			imagecopyresampled($backPicIm,$im,$startPoint,$endPoint,0,0,$newwidth,$newheight,$pic_width,$pic_height);
		}
		else
		{
			imagealphablending($backPicIm, true);
			imagesavealpha($backPicIm, true);
			$trans_colour = imagecolorallocatealpha($backPicIm, 0, 0, 0, 127);
			imagefill($backPicIm, 0, 0, $trans_colour);
			imagecopyresized($backPicIm,$im,$startPoint,$endPoint,0,0,$newwidth,$newheight,$pic_width,$pic_height);
		}
		$name = OPERATOR_CODE.$name.$filetype;
		imagepng($backPicIm,$name);
		imagedestroy($backPicIm);
		imagedestroy($im);
		$url = "http://pic.skysrt.com/uploadPic.php";
		$data = array(
				"file" => "@".ROOT.'/Framework/tvos/autorun/'.$name,
				"uploaddir" => "/data/www/img/logo/category/",
		);
		$result = self::uploadByCURL($data,$url);
		@unlink($name);
		return "http://pic.skysrt.com/img/logo/category/".$name;
	}
	
	private function uploadByCURL($post_data,$post_url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $post_url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl,CURLOPT_USERAGENT,"Mozilla/4.0");
		$result = curl_exec($curl);
		$error = curl_error($curl);
		return $error ? $error : $result;
	}
	
}