<?php
namespace res\controllers;

use Sky\base\Controller;
use res\models\VideoModel;
use res\models\FilterModel;
use res\models\LogPlayUrlModel;
use res\models\RelationModel;
use res\models\CategoryModel;

class ApiController extends ResController {
	
	public function actions() {
		return array(
				"Wsdl"=>array(
						"class"=>"Sky\\base\\WebServiceAction"
				),
		);
	}
	
	public function actionShowDetails($topc,$sid) {
		global $sysCondition;
		$sysCondition = $this->getPolicyValue($topc);
		switch ($topc) {
			case "0001":
				$result = VideoModel::queryvideobyid($sid, '');
				if (isset($result[0])) {
					$result = parent::loadMedia($result);
					$result[0]['comment'] = VideoModel::queryvideocomment($sid, '');
					$result[0]['poster'] = VideoModel::queryplaybill($sid, '');
				}
				break;
			default:
				$result = array();
				break;
		}
		return $result;
	}
	
	/**
	 * 分类列表
	 * @param string 顶级分类/分类id
	 * @param string 页数
	 * @param string 每页数量
	 * @return array_Category 分类列表
	 */
	public function actionListCategory($cid, $page, $pagesize) {
		global $sysCondition;
		$sysCondition = $this->getPolicyValue($cid);
		return parent::listCategory($cid, $page, $pagesize);
	}
	
	/**
	 * 资源详情
	 * @param string 顶级分类
	 * @param string 资源id
	 * @return array_Media|array_Audio|array_Info|array_EPGChannel|array_EPGProgram 资源详情
	 */
	public function actionShowSource($topc,$sid,$fields='') {
		global $sysCondition;
		$sysCondition = $this->getPolicyValue($topc);
		return parent::showSource($topc, $sid, $fields);
	}
	
	/**
	 * 资源列表
	 * @param string 顶级分类
	 * @param string condition
	 * @param string page
	 * @param string pagesize
	 * @param string Union
	 * @return array_Media|array_Audio|array_Info|array_EPGChannel|array_EPGProgram 资源列表
	 */
	public function actionListSources($topc,$condition,$page,$pagesize,$Union=1) {
		global $sysCondition;
		$sysCondition = $this->getPolicyValue($topc);
		return parent::listSource($topc, $condition, $page, $pagesize, $Union);
	}
	
	/**
	 * 资源子集列表(影视segment>1)(为了兼容旧的保留此接口)
	 * @param string 顶级分类
	 * @param string sid
	 * @param string page
	 * @param string pagesize
	 * @return array_Media|array_Info 资源子集列表
	 */
	public function actionListSegment($topc,$sid,$page,$pagesize) {
		global $sysCondition;
		$sysCondition = $this->getPolicyValue($topc);
		return parent::listSegments($topc, $sid, $page, $pagesize);
	}
	
	/**
	 * 排行
	 * @param string 顶级分类
	 * @param string key
	 * @param string page
	 * @param string pagesize
	 * @return array_Media 排行
	 */
	public function actionListTops($topc='0001',$key,$page=0,$pagesize=10) {
		global $sysCondition;
		$sysCondition = $this->getPolicyValue($topc);
		if ($key == "top10") {
			$result = VideoModel::listtopvideo($sysCondition);
		} else {
			$key = (isset($key) && !empty($key))?$key:"latest";
			$result = VideoModel::listtopvideobykey($key, $sysCondition, $page, $pagesize);
		}
		$result = parent::loadMedia($result);
		return $result;
	}
	
	public function actionTopKeys($topc='0001') {
		global $sysCondition;
		$sysCondition = $this->getPolicyValue($topc);
		$source_type = 1;
		switch ($topc) {
			case "0001":
				$source_type = 1;
				break;
			case "0005":
				$source_type = 5;
				break;
			default:
				break;
		}
		$result = VideoModel::topkeys($source_type, $sysCondition);
		return $result;
	}
	
	/**
	 * News：相关报道，Highlights：花絮
	 * @param string 顶级分类
	 * @param string name
	 * @param string sid
	 * @return array_Media 相关
	 */
	public function actionListRelations($topc,$name,$sid) {
		global $sysCondition;
		$sysCondition = $this->getPolicyValue($topc);
		switch ($topc) {
			case "0001":
				switch ($name) {
					case 'Highlights':
						$result = array();
						break;
					case 'youlike':
					default:
						$result = VideoModel::showvideoforrelation($sid, $sysCondition);
		
						if(count($result) < 2){
							$result = VideoModel::showvideofortop($sid, $sysCondition);
						}
				}
				$result = parent::loadMedia($result);
				break;
			default:
				$result = array();
				break;
		}
		return $result;
	}
	
	/**
	 * 根据节目ID 筛选相关关键字
	 * @param string 顶级分类
	 * @param string sid
	 * @return 
	 */
	public function actionListRelationMarks($topc,$sid) {
		switch ($topc) {
			case "0001":
				$marks = RelationModel::queryarelationmark($sid, 2, '');
				if(!isset($marks) || count($marks) <= 0) {
					$catpath = CategoryModel::querycategorypath($sid, '');
					if(isset($catpath) && !empty($catpath)) {
						$cats = explode("/", $catpath);
						for ($i = count($cats) - 1; $i >= 0; $i--) {
							if(!empty($cats[$i])){
								$marks = RelationModel::queryarelationmark($cats[$i], 1, '');
								if(isset($marks) && count($marks) > 0){
									break;
								}
							}
						}
					} else {
						$marks = RelationModel::queryarelationmark(1, 1, '');
					}
				}
				$result = VideoModel::showvideolistbyid($sid, '');
				if(isset($result) && count($result) > 0) {
					$buildup = array();
					foreach ($marks as $mark) {
						if($mark['isattribute']){
							$fieldname = $mark['name'];
							$keyvalue = $result[0][$fieldname];
							if(strpos($keyvalue, "|") >= 0 || strpos($keyvalue, ",") >= 0){
								$values = explode('|', $keyvalue);
								if(!is_array($values)){
									$values = explode(',', $keyvalue);
								}
								foreach ($values as $value) {
									if(!empty($value)){
										$source = array(
												"id"=>$mark['id'],
												"objectype"=>$mark['objecttype'],
												"objectid"=>$mark['objectid'],
												"name"=>$mark['name'],
												"showname"=>$value,
												"isattribute"=>$mark['isattribute']
										);
										array_push($buildup,$source);
									}
								}
							}else{
								$mark['showname'] = $keyvalue;
								array_push($buildup,$mark);
							}
						}else{
							array_push($buildup,$mark);
						}
					}
				}
				break;
			default:
				$buildup = array();
				break;
		}
		return $buildup;
	}
	
	/**
	 * 根据关系表获取相关列表内容
	 * @param string 顶级分类
	 * @param string sid
	 * @return
	 */
	public function actionListRelationCross($topc,$sid) {
		switch ($topc) {
			case "00061":
				$media = array();
				$music = array();
				$info = array();
				break;
			default:
				$media = array();
				$music = array();
				$info = array();
				break;
		}
		$list = new \stdClass();
		$list->media = $media;
		$list->music = $music;
		$list->info = $info;
		return $list;
	}
	
	private function EPGNameClean($temp) {
		$selectArray = array();
		if (strpos($temp, "：") > 0) $selectArray = explode("：", $temp);
		elseif (strpos($temp, ":") > 0) $selectArray = explode(":", $temp);
		if (count($selectArray) > 1) {
			$temp = $selectArray[1];
		}
		$selectArray = array();
		if (strpos($temp, "（") > 0) $selectArray = explode("（", $temp);
		elseif (strpos($temp, "(") > 0) $selectArray = explode("(", $temp);
		if (count($selectArray) > 1) {
			$temp = $selectArray[0];
		}
		$selectArray = array ();
		$splitArray = str_split($temp);
		$flag = 0;
		for ($index = 0, $max_count = sizeof($splitArray); $index < $max_count; $index++) {
			$array_element = $splitArray[$index];
			if (is_numeric($array_element)) {
				$flag = $array_element;
				break;
			}
		}
		$selecrTitleArray = explode($flag, $temp);
		$selecrTitle = "";
		if (count($selecrTitleArray) > 1) {
			$temp = $selecrTitleArray[0];
		}
		return $temp;
	}
	
	/**
	 * 筛选器
	 * @param string 分类id
	 * @param string attr
	 * @return array_Enums 筛选器
	 */
	public function actionListEnums($cid,$attr) {
		global $sysCondition;
		$sysCondition = $this->getPolicyValue($cid);
		$result = FilterModel::queryenumsbycid($cid, $sysCondition);
// 		if (count($result) > 0) {
// 			$result = FilterModel::queryenumsbypid($cid, $sysCondition);
// 		}
		return $result;
	}
	
// 	public function actionListAllEnums($cid) {
// 		$result = FilterModel::queryenumsbycid($cid);
// 		if (count($result) > 0) {
// 			$result = FilterModel::queryenumsbypid($cid);
// 		}
// 		return $result;
// 	}
	
	/**
	 * 随便看看
	 * @param string u_id
	 * @param string 顶级分类
	 * @param string pagesize
	 * @return array_Media 随便看看
	 */
	public function actionListCasuals($u_id,$topc,$pagesize) {
		global $sysCondition;
		$sysCondition = $this->getPolicyValue($topc);
		switch ($topc) {
			case "0001":
				$result = VideoModel::listcasuals($pagesize, $sysCondition);
				$count = count($result);
				break;
			default:
				$count = 0;
				$result = array();
				break;
		}
		return array("total"=>$count,"result"=>$result);
	}
	
	/**
	 * 提交资源信息
	 * @param string url
	 * @param string playurl
	 * @param string append
	 * @param int expired
	 * @return
	 */
	public function actionSetRealPlayAddress($url,$realPlayAddress,$append='',$expired=0) {
		LogPlayUrlModel::InsertLogPlayUrl($realPlayAddress, $url, $append, $expired);
	}
	
}