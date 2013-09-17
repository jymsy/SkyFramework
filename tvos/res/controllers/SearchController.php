<?php
namespace res\controllers;

use Sky\base\Controller;
use base\components\PolicyController;
use res\models\SearchModel;

class SearchController extends PolicyController {
	
	public function actions() {
		return array(
				"Wsdl"=>array(
						"class"=>"Sky\\base\\WebServiceAction"
				),
		);
	}
	
	static $searchType=array(
			"ST_ALL"=>-1,
			"ST_AUDIO"=>1,
			"ST_VIDEO"=>2,
			"ST_IMAGE"=>4,
			"ST_WEB"=>8,
			"ST_NEW"=>16,
			"ST_APP"=>32,
			"ST_SNSMSG"=>64,
			"ST_KARAOKE"=>128,
			"ST_CONTINUE"=>1073741824,
			"ST_CUSTOM"=>-2147483648,
			"ST_UNKNOWN"=>0
	);
	
	static $searchName=array(
			"ST_AUDIO"=>"音乐",
			"ST_VIDEO"=>"影视",
			"ST_IMAGE"=>"图片",
			"ST_WEB"=>"网页",
			"ST_NEW"=>"新闻",
			"ST_APP"=>"应用",
			"ST_SNSMSG"=>"新鲜事",
			"ST_KARAOKE"=>"卡拉OK",
			"ST_DTVCHANNEL"=>"电视频道"
	);
	
	public function actionGetHotwords($type,$pagesize) {
		$result = SearchModel::getHotwords($pagesize);
		return $result;
	}
	
	public function actionListObject($key,$type,$startpos,$len,$condition="") {
		global $sysCondition;
		$sysCondition = $this->getPolicyValue($type);
		if ($startpos == '0' || $startpos == 0) SearchModel::updateHotwords($key);
		if (!isset($type) || empty($type)) {
			if (isset($condition) && !empty($condition)) {
				$condition = get_object_vars(json_decode($condition));
				if (isset($condition['category']) && $condition['category'] != '') {
					$type = $condition['category'];
				} else {
					return self::mixSearch($key, $startpos, $len);
				}
			} else {
				return self::mixSearch($key, $startpos, $len);
			}
		}
		$result = self::search($key, $type, $startpos, $len);
		return array("total"=>$result["total"],"result"=>self::mixType($result['result'], $type));
	}
	
	public function actionListSearchObject($key,$type,$startpos,$len,$condition="") {
		global $sysCondition;
		$sysCondition = $this->getPolicyValue($type);
		if ($startpos == '0' || $startpos == 0) SearchModel::updateHotwords($key);
		if (!isset($type) || empty($type)) {
			if (isset($condition) && !empty($condition)) {
				$condition = get_object_vars(json_decode($condition));
				if (isset($condition['category']) && $condition['category'] != '') {
					$type = $condition['category'];
				} else {
					return self::mixSearchSearch($key, $startpos, $len);
				}
			} else {
				return self::mixSearchSearch($key, $startpos, $len);
			}
		}
		$result = self::search($key, $type, $startpos, $len);
		return array("total"=>$result["total"],"result"=>self::mixTypeSearch($result['result'], $type));
	}
	
	private function search($key,$type,$start,$pagesize) {
		switch ($type){
			case "0001":
				$result = $this->getVideo($key, $start, $pagesize);
				break;
			case "0002":
				$result = $this->getAudio($key, $start, $pagesize);
				break;
			case "0005":
				$result = $this->getInfo($key, $start, $pagesize);
				break;
			default:
				$result = array("total"=>0,"result"=>array());
				break;
		}
		return $result;
	}
	
	private function getVideo($key, $start, $pagesize) {
		global $sysCondition;
		$count = SearchModel::getVideoCount($key, $sysCondition);
		$result = SearchModel::getVideoDetail($key, $start, $pagesize, $sysCondition);
		return array("total"=>$count,"result"=>$result);
	}
	
	private function getAudio($key, $start, $pagesize) {
		global $sysCondition;
		$count = SearchModel::getAudioCount($key, $sysCondition);
		$result = SearchModel::getAudioDetail($key, $start, $pagesize, $sysCondition);
		return array("total"=>$count,"result"=>$result);
	}
	
	private function getInfo($key, $start, $pagesize) {
		global $sysCondition;
		$count = SearchModel::getInfoCount($key, $sysCondition);
		$result = SearchModel::getInfoDetail($key, $start, $pagesize, '', $sysCondition);
		return array("total"=>$count,"result"=>$result);
	}
	
	private function mixType($result, $type) {
		if (count($result) > 0) {
			for ($i=0;$i<count($result);$i++) {
				$result[$i] = array_merge($result[$i], array('type'=>$type,'logo'=>$result[$i]['thumb']));
			}
		}
		return $result;
	}
	
	private function mixTypeSearch($result, $type) {
		$list = array();
		foreach ($result as $row) {
			$obj = new \stdClass();
			$obj->type = $type;
			$obj->result = $row;
			array_push($list, $obj);
		}
		return $list;
	}
	
	private function mixSearch($key,$startpos,$len){
		global $sysCondition;
		$audio_count = SearchModel::getAudioCount($key, $sysCondition);
		$video_count = SearchModel::getVideoCount($key, $sysCondition);
		$total = $audio_count + $video_count;
		if ($startpos <= $video_count) {
			if ($startpos + $len <= $video_count) {
				$result_v = SearchModel::getVideoDetail($key, $startpos, $len, $sysCondition);
			} else {
				$result_v = SearchModel::getVideoDetail($key, $startpos, $len, $sysCondition);
				$result_a = SearchModel::getAudioDetail($key, 0, $len - count($result_v), $sysCondition);
			}
		} else {
			$result_a = SearchModel::getAudioDetail($key, $startpos - $video_count, $len, $sysCondition);
		}
		if (isset($result_v)) {
			$result_v = self::mixType($result_v, "0001");
		} else {
			$result_v = array();
		}
		if (isset($result_a)) {
			$result_a = self::mixType($result_a, "0002");
		} else {
			$result_a = array();
		}
		$result = array_merge($result_v, $result_a);
		return array("total"=>$total,"result"=>$result);
	}
	
	private function mixSearchSearch($key,$startpos,$len){
		global $sysCondition;
		$audio_count = SearchModel::getAudioCount($key, $sysCondition);
		$video_count = SearchModel::getVideoCount($key, $sysCondition);
		$total = $audio_count + $video_count;
		if ($startpos <= $video_count) {
			if ($startpos + $len <= $video_count) {
				$result_v = SearchModel::getVideoDetail($key, $startpos, $len, $sysCondition);
			} else {
				$result_v = SearchModel::getVideoDetail($key, $startpos, $len, $sysCondition);
				$result_a = SearchModel::getAudioDetail($key, 0, $len - count($result_v), $sysCondition);
			}
		} else {
			$result_a = SearchModel::getAudioDetail($key, $startpos - $video_count, $len, $sysCondition);
		}
		if (isset($result_v)) {
			$result_v = self::mixTypeSearch($result_v, "0001");
		} else {
			$result_v = array();
		}
		if (isset($result_a)) {
			$result_a = self::mixTypeSearch($result_a, "0002");
		} else {
			$result_a = array();
		}
		$result = array_merge($result_v, $result_a);
		return array("total"=>$total,"result"=>$result);
	}
	
}