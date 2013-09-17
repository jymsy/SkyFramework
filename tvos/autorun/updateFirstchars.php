<?php
namespace tvos\autorun;


use tvos\autorun\models\UpdateFirstCharsModel;
use tvos\autorun\pinyin;

class updateFirstchars extends pinyin{
	
// 	const baseurl = "http://localhost/api/tvos/index.php";
	const baseurl = "http://localhost/Framework/tvos/index.php";
	
	
	function run() {
		$this->cleanupFirstchars();
		$this->updateVideoFirstchars();
		$this->updateMusicFirstchars();
		$this->updateAppFirstchars();
		$this->updateSelfFirstchars();
		
	}
	
	private function cleanupFirstchars(){
		UpdateFirstCharsModel::truncatetable();
	}
	
	private function updateVideoFirstchars(){
		$version = date('Ymd');
		$category = array("dy","dsj","dm");
		foreach ($category as $mid){
			$url = self::baseurl.'?_r=res/api/listSources&_new&ws&topc=0001&condition={"category":"' .$mid. '"}&page=0&pagesize=1';
			$rs = json_decode(file_get_contents($url));
			$total = intval($rs->total);
			$count = ceil($total/1000);
			for ($i=0;$i<$count;$i++){
				$mid_url = self::baseurl.'?_r=res/api/listSources&_new&ws&topc=0001&condition={"category":"' .$mid. '"}&page='. $i .'&pagesize=1000';
				$rs = json_decode(file_get_contents($mid_url));
				$mid_results = $rs->result;
				foreach ($mid_results as $mid_rs){
					$midi_array = $this->GetPys($mid_rs->title);
					$midi_array = array_unique($midi_array);
					$mid_character = implode(",",$midi_array);
					if (!$mid_character){
						continue;
					}
					UpdateFirstCharsModel::insertfirstchars($mid_rs->title, $mid_character, $version, '1','');
				}
			}
		}
	}
	
	private function updateMusicFirstchars(){
		$version = date('Ymd');
		$category_arr = $this->getCategory('0002');
		$mid_array = array();
		foreach ($category_arr as $mid){
			$url = self::baseurl.'?_r=res/api/listSources&_new&ws&topc=0002&condition={"categoryid":"'. $mid->id .'"}&page=0&pagesize=1';
			$rs = json_decode(file_get_contents($url));
			$total = intval($rs->total);
			$count = ceil($total/1000);
			for ($i=0;$i<$count;$i++){
				$mid_url = self::baseurl.'?_r=res/api/listSources&_new&ws&topc=0002&condition={"categoryid":"'. $mid->id .'"}&page='. $i .'&pagesize=1000';
				$rs = json_decode(file_get_contents($mid_url));
				$mid_results = $rs->result;
				foreach ($mid_results as $mid_rs){
					$midi_array = $this->GetPys($mid_rs->title);
					$midi_array = array_unique($midi_array);
					$mid_character = implode(",",$midi_array);
					if (!$mid_character){
						continue;
					}
					UpdateFirstCharsModel::insertfirstchars($mid_rs->title, $mid_character, $version, '1','');
				}
			}
		}
		
	}
	
	private function updateAppFirstchars(){
		$version = date('Ymd');
		$apps = UpdateFirstCharsModel::GetAvailableAppstore();
		foreach ($apps as $app){
			$midi_array = $this->GetPys($app['Product_Name']);
			$midi_array = array_unique($midi_array);
			$mid_character = implode(",",$midi_array);				
			if (!$mid_character){
				continue;
			}
			UpdateFirstCharsModel::insertfirstchars($app['Product_Name'], $mid_character, $version, '1',$app['platform_info']);
		}
	}
	
	private function getCategory($type) {
		$url = self::baseurl."?_r=res/Api/ListCategory&cid=$type&page=0&pagesize=1&ws&_new";
		$rs = json_decode(file_get_contents($url));
		$total = $rs->total;
		$url = self::baseurl."?_r=res/Api/ListCategory&cid=$type&page=0&pagesize=$total&ws&_new";
		$rs = json_decode(file_get_contents($url));
		return $rs->result;
	}
	
	private function updateSelfFirstchars(){
		$this->updateSelfMusicFirstchars();
		$this->updateSelfVideoFirstchars();
		
	}
	
	private function updateSelfVideoFirstchars(){
		$pre_num = UpdateFirstCharsModel::queryrecordprocess('0001', '');
		if (!isset($pre_num) ) {
			UpdateFirstCharsModel::insertrecordprocess('0001', 0);
			$pre_num = 0;
		}
		$pre_num = intval($pre_num);
		$max = UpdateFirstCharsModel::GetVideoMaxId();
		if ($max <= $pre_num){
			return 0;
		}
		$count = ceil(($max - $pre_num)/1000);
		for($i=0;$i<$count;$i++){
			$max_id = $pre_num + $i*1000;
			$rs = UpdateFirstCharsModel::queryvideobyid($pre_num, '',$max_id,1000);
			$max_id = 0;
			foreach ($rs as $mid){
				$id = $mid['v_id'];
				$midi_array = $this->GetPys($mid['title']);
				$midi_array = array_unique($midi_array);
				$mid_character = implode(",",$midi_array);
				if (!$mid_character){
					continue;
				}
				UpdateFirstCharsModel::modifyvideofirstchars($mid_character, $id);
				if ($id>$max_id)$max_id=$id;
			}
			if ($max_id > $pre_num){
				UpdateFirstCharsModel::modifyrecordprocess($max_id, '0001');
			}
		}
		
		
		
	}
	
	private function updateSelfMusicFirstchars(){
		$pre_num = UpdateFirstCharsModel::queryrecordprocess('0005', '');
		if (!isset($pre_num)) {
			UpdateFirstCharsModel::insertrecordprocess('0005', 0);
			$pre_num = 0;
		}
		$pre_num = intval($pre_num);
		$max = UpdateFirstCharsModel::GetMusicTopMaxId();
		if ($max <= $pre_num){
			return 0;
		}
		$count = ceil(($max - $pre_num)/1000);
		for($i=0;$i<$count;$i++){
			$max_id = $pre_num + $i*1000;
			$rs = UpdateFirstCharsModel::querymusictopinfo($pre_num, $max_id, 1000);
			$max_id =0;
			foreach ($rs as $mid){
				$id = $mid['music_top_id'];
				$midi_array = $this->GetPys($mid['title']);
				$midi_array = array_unique($midi_array);
				$mid_character = implode(",",$midi_array);
				if (!$mid_character){
					continue;
				}
				UpdateFirstCharsModel::modifytableinfo($id, $mid_character);
				if ($id>$max_id)$max_id=$id;
			}
			if ($max_id > $pre_num){
				UpdateFirstCharsModel::modifyrecordprocess($max_id, '0005');
			}
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}