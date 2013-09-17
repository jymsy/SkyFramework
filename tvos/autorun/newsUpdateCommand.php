<?php
namespace tvos\autorun;

use Sky\console\ConsoleCommand;
use tvos\autorun\models\ResUpdateModel;

class newsUpdateCommand extends ConsoleCommand {
	
	const site = "http://lrc.skysrt.com/news/index.php";
	
	function GetData() {
		$max = ResUpdateModel::getNewsMaxResmark();
		$json = @file_get_contents(self::site.'?id='.$max);
		file_put_contents("temp_news.txt", $json);
		echo "\n news_update GetData finish!";
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
		$handle = fopen ( "temp_news.txt", "r" );
		while ( ! feof ( $handle ) ) {
			$row = fgets ( $handle );
			if (isset ( $row ) && ! empty ( $row )) {
				$object = json_decode ( $row );
				if (isset ( $object )) {
					$array = get_object_vars ( $object );
					$areabiz_type = $object->areabiz_type;
					$areabiz_table = $object->areabiz_table;
					$areabiz_id = $object->areabiz_id;
					$kvarray = self::GetKV($areabiz_type, $areabiz_table);
					$v_schema = $kvarray['areabiz_schema'];
					$v_table = $kvarray['areabiz_table'];
					switch ($areabiz_type) {
						case "insert" :
							//排重
							$wheres = self::GetCondition($areabiz_id, $areabiz_type, $areabiz_table);
							if (intval(ResUpdateModel::querydatacount($v_schema, $v_table, $wheres, '')) > 0) continue;
							
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
							$id = ResUpdateModel::importdata($v_schema, $v_table, $keys, $values);
							break;
					}
				}
			}
		}
		fclose ( $handle );
		fclose($log);
		echo "\n news_update Update finish!";
	}
	
	public function actionRun() {
		self::GetData();
		self::Update();
	}
	
}