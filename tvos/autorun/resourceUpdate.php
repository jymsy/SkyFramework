<?php
namespace tvos\autorun;

use tvos\autorun\models\ResUpdateModel;
use tvos\autorun\models\CrawlerFileVersionModel;

class resourceUpdate {
	
	public function before() {
		ResUpdateModel::updateVideoIsOld();
		ResUpdateModel::deleteExtraWeight();
	}
	
	public function GetData($date) {
		$server = 'lrc.skysrt.com';
// 		$server = '10.200.240.211';
		$floder = 'allInPython';
		$json = @file_get_contents("http://$server/$floder/$date");
		file_put_contents("temp.txt", $json);
		echo "\n resource_update GetData finish!";
	}
	
	private function GetKV($areabiz_type,$areabiz_table) {
		$kv = new kv();
		return $kv->GetArray($areabiz_type, $areabiz_table);
	}
	
	private function GetCondition($areabiz_id,$areabiz_type,$areabiz_table) {
		$sign = get_object_vars($areabiz_id);
		$kvarray = self::GetKV($areabiz_type, $areabiz_table);
		$wheres = "";
		foreach($sign as $key=>$value) {
			if (isset($kvarray[$key])) $key = $kvarray[$key];
			else continue;
			if(!empty($wheres)) $wheres .= " and ";
			if (is_int($value)) {
				$wheres .= "`$key`=$value";
			} else {
				$wheres .= "`$key`='".addslashes($value)."'";
			}
		}
		return $wheres;
	}
	
	function Update() {
		$handle = fopen("temp.txt", "r");
		while(!feof($handle)) {
			$row = fgets($handle);
			if (isset($row) && !empty($row)) {
				$object = json_decode($row);
				if (isset($object)) {
					$array = get_object_vars($object);
					$areabiz_type = $object->areabiz_type;
					$areabiz_table = $object->areabiz_table;
					$areabiz_id = $object->areabiz_id;
					$kvarray = self::GetKV($areabiz_type, $areabiz_table);
					if (count($kvarray) == 0)continue;
					$v_schema = $kvarray['areabiz_schema'];
					$v_table = $kvarray['areabiz_table'];
					switch ($areabiz_type) {
						case 'insert':
							//排重
							$wheres = self::GetCondition($areabiz_id, $areabiz_type, $areabiz_table);
							if (intval(ResUpdateModel::querydatacount($v_schema, $v_table, $wheres)) > 0) continue;
							
							$keys = '';
							$values = '';
							foreach ($array as $key=>$value) {
								if ($key == "areabiz_table" || $key == "areabiz_type" || $key == "areabiz_id") continue;
								if (isset($kvarray[$key])) $key = $kvarray[$key];
								else continue;
								if ($keys != '') $keys .= ',';
								$keys .= "`$key`";
								if ($values != '') $values .= ',';
								if (is_int($value)) {
									$values .= $value;
								} else {
									$values .= "'".addslashes($value)."'";
								}
							}
							if($areabiz_table == 'channel' || $areabiz_table == 'program'){
								$id = ResUpdateModel::replaceData($v_schema, $v_table, $keys, $values);
							} else {
								$id = ResUpdateModel::importdata($v_schema, $v_table, $keys, $values);
							}
							if ($id > 0) {
								switch ($areabiz_table) {
// 									case 'sokuSite':
// 										ResUpdateModel::modifyvideosite($id);
// 										break;
// 									case 'sokuUrl':
// 										ResUpdateModel::modifyvideourl($id, '');
// 										break;
// 									case 'sokuCommment':
// 										ResUpdateModel::modifyvideocomment($id);
// 										break;
									case 'kuwoTop':
										ResUpdateModel::modifymusictopbyid($id);
										break;
									case 'playbill':
										ResUpdateModel::modifyplaybillbyid($id);
										break;
									default:
										break;
								}
							}
							break;
						case 'update':
							$wheres = self::GetCondition($areabiz_id, $areabiz_type, $areabiz_table);
							if ($wheres == '') continue;
							$sets = '';
							foreach ($array as $key=>$value) {
								if ($key == "areabiz_table" || $key == "areabiz_type" || $key == "areabiz_id") continue;
								if (isset($kvarray[$key])) $key = $kvarray[$key];
								else continue;
								if ($sets != '') $sets .= ',';
								if (is_int($value)) {
									$sets .= "`$key`=$value";
								} else {
									$sets .= "`$key`='".addslashes($value)."'";
								}
							}
							ResUpdateModel::modifydata($v_schema, $v_table, $sets, $wheres);
							break;
						default:
							break;
					}
				}
			}
		}
		fclose($handle);
		echo "\n resource_update Update finish!";
	}
	
	function Other() {
		ResUpdateModel::insertvidesitebyvideourl();
 		ResUpdateModel::modifymusictop();
// 		ResUpdateModel::modifyplaybill();
		echo "\n resource_update Other finish!";
	}
	
	function run() {
		$pre = intval(CrawlerFileVersionModel::querycrawlerfileversion());
		$today = intval(strtotime(date('Y-m-d'), time()));
		if ($pre == 0) {
			$pre = $today - 1;
		}
// 		if (!$pre) {
// 			self::Update();
// 			self::Other();
// 			$handle = fopen("temp.txt", "w");
// 			fwrite($handle, '');
// 			fclose($handle);
// 			$fv->insertcrewlerfileversion($pre, date('Y-m-d', $today));
// 		} else {
		while ($pre < $today) {
			$pre = $pre + 3600*24;
			self::before();
			self::GetData(date('Y-m-d', $pre));
			self::Update();
//			self::Other();
			$handle = fopen("temp.txt", "w");
			fwrite($handle, '');
			fclose($handle);
			self::GetLocalData(date('Y-m-d', $pre));
			self::Update();
			self::Other();
			$handle = fopen("temp.txt", "w");
			fwrite($handle, '');
			fclose($handle);
			CrawlerFileVersionModel::insertcrewlerfileversion($pre, date('Y-m-d', $pre));
		}
// 		}
	}
	
	function GetLocalData($date){
		try {
			$server = 'lrc.skysrt.com';
			$server = '10.200.240.211';
			$floder = 'Dreptiles';
			$local = self::GetLocal();
			$json = @file_get_contents("http://$server/$floder/$local/$date");
			file_put_contents("temp.txt", $json);
			echo "\n resource_update GetData finish!";
		}catch (Exception $e){
			$handle = fopen("temp.txt", "w");
			fwrite($handle, '');
			fclose($handle);
		}
	}
	
	function GetLocal(){
		$hostname = HostName;
		$mid_array = explode('tvos.skysrt.com', $hostname);
		if ($mid_array[0]=='')return 'tvos';
		if (count($mid_array)>1){
			$mid_string = $mid_array[0];
			$mid_array = explode("//", $mid_string);
			$mid_len = count($mid_array);
			if ($mid_len>1)$mid_string = $mid_array[$mid_len-1];
			else $mid_string = $mid_array[0];
			if ($mid_string=='')return 'tvos';
			$mid_array = explode(".", $mid_string);
			$mid_len = count($mid_array);
			if ($mid_array[$mid_len-2]=='www')return 'tvos';
			else return $mid_array[$mid_len-2];
		}
		return '';
	}
	
	function run_test(){
		/*
		$server = 'lrc.skysrt.com';
		$server = '10.200.240.211';
		$floder = 'temp';
		$data = '2013-08-24';
		$json = @file_get_contents("http://$server/$floder/$date");
		file_put_contents("temp.txt", $json);
		echo "\n resource_update GetData finish!";
		self::Update();
		self::Other();
		
		ResUpdateModel::deleteExtraWeight();
		$server = 'lrc.skysrt.com';
		#$server = '10.200.240.211';
		$floder = 'temp';
		$data = '2013-09-17';
		$json = @file_get_contents("http://$server/$floder/$date");
		file_put_contents("temp.txt", $json);*/
		echo "\n resource_update GetData finish!";
		self::Update();
		self::Other();
		
	}
	
}