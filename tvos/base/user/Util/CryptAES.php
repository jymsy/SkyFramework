<?php

namespace base\user\util;

class CryptAES{
	private $cipher     = "rijndael-128";
	private $mode       = "ecb";
	private $secret_key = "79JKuie9kjKJ7A0M";   // transfer key : the same with client.
	private $iv         = "1010011918423013";

	function CryptAES($key = NULL,$iv = NULL){
		if (isset($key)){
			$this->secret_key = $key;
		}
		if(isset($iv)){
			$this->iv = $iv;
		}

	}

	function set_config($cipher,$mode,$iv){
		if ($cipher != '') $this->cipher = $cipher;
		if ($mode != '') $this->mode = $mode;
		if ($iv != '') $this->iv = $iv;
	}

	function encrypt($str,$completmentMode=true){
		$td = mcrypt_module_open($this->cipher, "", $this->mode, $this->iv);
		mcrypt_generic_init($td, $this->secret_key, $this->iv);
		$len = strlen($str);
		if ($completmentMode){
			$completment_str = str_repeat(chr(16-$len % 16), 16-$len % 16);
		}else $completment_str = "";
		$cyper_text = mcrypt_generic($td, $str.$completment_str);
		$r = bin2hex($cyper_text);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return $r;
	}

	function decrypt($str,$completmentMode=true){
		$td = mcrypt_module_open($this->cipher, "", $this->mode, $this->iv);
		mcrypt_generic_init($td, $this->secret_key, $this->iv);
		$decrypted_text = mdecrypt_generic($td, $this->hex2bin($str));
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
//		print_r($this->ascii($decrypted_text));
		if ($completmentMode){
			return substr($decrypted_text, 0,-ord(substr($decrypted_text, strlen($decrypted_text)-1,1)));
		}else{
			return str_replace("\0", "", $decrypted_text);
		}
	}

	function ascii($str){
		$ascii = array();
		for ($i = 0;$i<strlen($str);$i++){
			array_push($ascii, ord(substr($str, $i,1)));
		}
		return $ascii;
	}

	private function hex2bin($hexdata) {
		$bindata="";
		for ($i=0;$i<strlen($hexdata);$i+=2) {
			$bindata.=chr(hexdec(substr($hexdata,$i,2)));
		}
		return $bindata;
	}
				
	function is_same_bin($hexdata1,$hexdata2){
		if($this->hex2bin($hexdata1)==$this->hex2bin($hexdata2)){
			echo "same data","<br/>";
		}else echo "different data","<br/>";
		return 1;
	}

	function encrypt_pw_all($list){
		$count = count($list);
		if ($count > 0 && isset($list[0]->u_password)){
			for($i = 0; $i < $count; $i++){
					$list[$i]->u_password = $this->encrypt($list[$i]->u_password);
			}
		}
        return $list;
	}
}
