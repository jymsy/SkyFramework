<?php
namespace tvos\autorun;

use tvos\autorun\models\UpdateCategoryModel;

class updateCategory {
	
// 	const baseurl = "http://localhost/api/tvos/index.php";
	const baseurl = "http://localhost/Framework/tvos/index.php";
	
	static $MediaType = array(
			"电影"=>"dy",
			"电视剧"=>"dsj",
			"动漫"=>"dm",
			"纪录片"=>"jlp",
			"生活"=>"sh",
			"综艺"=>"zy",
			"MV"=>"mv",
			"新闻"=>"xw",
			"短视频"=>"dsp",
			'体育'=>'ty',
			'娱乐'=>'yl',
			'搞笑'=>'gx',
			'教育'=>'jy',
			'旅游.纪录片'=>'lyjlp',
			'时尚.生活'=>'sssh',
			'片花'=>'ph',
			'音乐'=>'yy',
			'影视大厅'=>'ysdt'
	);
	
	function run() {
		foreach (array("0001","0002") as $type) {
			$category = self::getCategory($type);
			self::SetChildNums_Base($type, $category);
			self::SetUpdateNums_Base($type, $category);
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

	private function SetChildNums_Base($type,$category) {
		$ri = new picAddpic();
		foreach ($category as $row) {
			$condition = '{"categoryid":"'.$row->id.'"}';
			$url = self::baseurl."?_r=res/Api/ListSources&topc=$type&condition=$condition&page=0&pagesize=1&union=1&ws&_new";
			$rs = json_decode(file_get_contents($url));
			$total = $rs->total;
			UpdateCategoryModel::modifychildsnum($total, $row->id);
			
			//分类海报生成
			if ($total > 0) {
				$result = $rs->result;
				$thumb = $result[0]->thumb;
				$im = imagecreatefromjpeg($thumb);
				$thumb = $ri->resizeImage($im,$row->id);
				UpdateCategoryModel::modifycategorylogo($thumb, $row->id);
			}
		}
	}

	private function SetUpdateNums_Base($type,$category) {
		$now = date("Y-m-d");
		foreach ($category as $row) {
			switch ($type) {
				case "0001":
					if (in_array($row->id, array("10090","10091","10092"))) {
						$total = 0;
					} else {
						$name = $row->name;
						$pinyin = self::$MediaType[$name];
						$total = UpdateCategoryModel::totalvideosite('', $pinyin, $now);
					}
					break;
				case "0002":
					$total = UpdateCategoryModel::totalmusictop($row->id, $now, '');
					break;
				default:
					break;
			}
			UpdateCategoryModel::modifychildsupdatenum($total, $row->id);
		}
	}
	
}