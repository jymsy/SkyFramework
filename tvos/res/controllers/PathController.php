<?php
namespace res\controllers;

use Sky\base\Controller;

class PathController extends ResController {
	
	public function actions() {
		return array(
				"Wsdl"=>array(
						"class"=>"Sky\\base\\WebServiceAction"
				),
		);
	}
	
	private $Setting = array(
			array("haschilds"=>true,"hasrelation"=>false,"hasfilter"=>true),
			array("haschilds"=>false,"hasrelation"=>true,"hasfilter"=>false)
	);
	private $g_settype = 1;
	private $g_parent_filter = false;
	private $total = 0;
	
	private function media($topc,$id,$settype,$page,$pagesize,$condition) {
		if($settype == 2) {
			$this->g_parent_filter = true;
		}
		return $this->listPath_Base($topc, $id, $settype, $page, $pagesize, $condition);
	}
	
	private function audio($topc,$id,$settype,$page,$pagesize,$condition) {
		$this->Setting = array(
				array("haschilds"=>true,"hasrelation"=>false,"hasfilter"=>false),
				array("haschilds"=>false,"hasrelation"=>false,"hasfilter"=>false)
		);
		return $this->listPath_Base($topc, $id, $settype, $page, $pagesize, $condition);
	}
	
	private function life($topc,$id,$settype,$page,$pagesize,$condition) {
		$this->Setting = array(
				array("haschilds"=>false,"hasrelation"=>false,"hasfilter"=>false),
				array("haschilds"=>false,"hasrelation"=>false,"hasfilter"=>false)
		);
		return $this->listPath_Base($topc, $id, $settype, $page, $pagesize, $condition);
	}
	
	private function photos($topc,$id,$settype,$page,$pagesize,$condition) {
		$base->Setting = array(
				array("haschilds"=>true,"hasrelation"=>false,"hasfilter"=>false),
				array("haschilds"=>false,"hasrelation"=>false,"hasfilter"=>false)
		);
		return $this->listPath_Base($topc, $id, $settype, $page, $pagesize, $condition);
	}
	
	private function website($topc,$id,$settype,$page,$pagesize,$condition) {
		$settype = 2; //强转成最终分类
		$this->Setting = array(
				array("haschilds"=>false,"hasrelation"=>false,"hasfilter"=>false),
				array("haschilds"=>false,"hasrelation"=>false,"hasfilter"=>false)
		);
		$this->g_settype = 1;
		return $this->listPath_Base($topc, $id, $settype, $page, $pagesize, $condition);
	}
	
	private function info($topc,$id,$settype,$page,$pagesize,$condition) {
		$this->Setting = array(
				array("haschilds"=>true,"hasrelation"=>false,"hasfilter"=>false),
				array("haschilds"=>false,"hasrelation"=>false,"hasfilter"=>false)
		);
		return $this->listPath_Base($topc, $id, $settype, $page, $pagesize, $condition);
	}
	
	private function pathCategory($cid,$page,$pagesize) {
		$list = array();
		$cats = parent::listCategory($cid, $page, $pagesize);
		if (isset($cats['total']) && $cats['total'] > 0) {
			//约定
			$promises = parent::getPromises("list_filter");
			$result = $cats['result'];
			foreach ($result as $row) {
				$item_object = new \stdClass();
				$item_object->id = $row['id'];
				$item_object->name = $row['name'];
				$item_object->logo = $row['logo'];
				if(isset($row["action"]) && !empty($row["action"])) $item_object->action = json_decode($row["action"]);
				if(isset($row["activelogo"])) $item_object->activelogo = $row["activelogo"];
				if($row['final'] == 1) $item_object->settype = 2;
				else $item_object->settype = 0;
				$item_object->haschilds = $this->Setting[0]["haschilds"];
				$item_object->childsnum = $row['childsnum'];
				$item_object->hasrelation = $this->Setting[0]["hasrelation"];
				$item_object->hasfilter = $this->Setting[0]["hasfilter"];
				$item_object->parent_filter = $this->g_parent_filter;
				$item_object->updatenum = $row['updatenum'];
				
				//约定
				$current_id = $item_object->id;
				if(isset($promises) && is_array($promises)){
					foreach ($promises as $p){
						if($p['key'] == "category"){
							$objs = json_decode($p['value']);
							foreach ($objs as $obj){
								if(isset($obj->$current_id)){
									$item_object->hasfilter = $obj->$current_id;
								}
							}
						}
					}
				}
				$list[] = $item_object;
			}
			$this->total = $cats['total'];
		}
		return $list;
	}
	
	private function pathSource($topc,$id,$page,$pagesize,$condition) {
		$list = array();
		if(!isset($condition) || empty($condition)) {
			$con = new \stdClass();
			$con->categoryid = $id;
			$condition = json_encode($con);
		}
		$sources = parent::listSource($topc, $condition, $page, $pagesize);
		$itemIds = array();
		if(isset($sources['total']) && $sources['total'] > 0){
			foreach ($sources['result'] as $row){
				$item_object = new \stdClass();
				$item_object->id = $row['id'];
				$itemIds[] = $row['id'];
				$item_object->name = $row['title'];
				$item_object->logo = $row['thumb'];
				$item_object->settype = $this->g_settype;
				$item_object->haschilds = $this->Setting[1]["haschilds"];
				$item_object->childsnum = 0;
				$item_object->hasrelation = $this->Setting[1]["hasrelation"];
				$item_object->hasfilter = $this->Setting[1]["hasfilter"];
				$item_object->parent_filter = $this->g_parent_filter;
				$list[] = $item_object;
			}
		}
		if(count($itemIds) > 0){
			$itemIds = join(",", $itemIds);
			$itemResult = parent::showSource($topc, $itemIds, "no text");
			for ($i = 0; $i < count($list); $i ++) {
				for ($j = 0; $j < count($itemResult) ; $j ++){
					if($list[$i]->id == $itemResult[$j]['id']){
						$list[$i]->item_json_str = $itemResult[$j];
					}
				}
			}
		}
		$this->total = $sources['total'];
		return $list;
	}
	
	private function pathSubs($topc,$id,$page,$pagesize){
		$list = array();
		$sources = parent::listSegments($topc, $id, $page, $pagesize);
		if(isset($sources)){
			foreach ($sources as $row){
				$item_object = new \stdClass();
				$item_object->id = "sub_".$row['id'];
				$item_object->name = $row['title'];
				$item_object->logo = $row['logo'];
				$item_object->settype = 1;
				$item_object->haschilds = false;
				$item_object->childsnum = 0;
				$item_object->hasrelation = false;
				$item_object->hasfilter = false;
				$item_object->parent_filter = false;
				$list[] = $item_object;
			}
		}
		return $list;
	}
	
	private function listPath_Base($topc,$id,$settype,$page,$pagesize,$condition) {
		$list = array();
		switch($settype) {
			case 0:	// 分类，有子类
				$list = self::pathCategory($id, $page, $pagesize);
				break;
			case 1:	// 资源，无子集
				break;
			case 2:	// 分类，终极分类
				$list = self::pathSource($topc, $id, $page, $pagesize, $condition);
				break;
			case 3: // 资源，有子集
				$lsit = self::pathSubs($topc, $id, $page, $pagesize);
				break;
		}
		return $list;
	}
	
	private function route($topc,$id,$settype,$page,$pagesize,$condition) {
		switch ($topc) {
			case '0001':
				$result = self::media($topc, $id, $settype, $page, $pagesize, $condition);
				break;
			case '0002':
			case '0009':
				$result = self::audio($topc, $id, $settype, $page, $pagesize, $condition);
				break;
			case '0003':
				$result = self::photos($topc, $id, $settype, $page, $pagesize, $condition);
				break;
			case '0005':
				$result = self::info($topc, $id, $settype, $page, $pagesize, $condition);
				break;
			case '00010':
				$result = self::website($topc, $id, $settype, $page, $pagesize, $condition);
				break;
			case '21000':
				$result = self::life($topc, $id, $settype, $page, $pagesize, $condition);
				break;
			default:
				$result = self::media($topc, $id, $settype, $page, $pagesize, $condition);
				break;
		}
		return $result;
	}
	
	/**
	 * path(旧版本保留)
	 * @param string 顶级分类
	 * @param string 分类/资源id
	 * @param string 类型
	 * @param string page
	 * @param string pagesize
	 * @param string condition
	 * @return array_Path path
	 */
	public function actionListPath($topc,$id,$settype,$page=0,$pagesize=20,$condition="") {
		global $sysCondition;
		$sysCondition = $this->getPolicyValue($topc);
		$result = self::route($topc, $id, $settype, $page, $pagesize, $condition);
		return $result;
	}
	
	/**
	 * path
	 * @param string 顶级分类
	 * @param string 分类/资源id
	 * @param string 类型
	 * @param string page
	 * @param string pagesize
	 * @param string condition
	 * @return array_Path path
	 */
	public function actionListPath2($topc,$id,$settype,$page=0,$pagesize=20,$condition="") {
		global $sysCondition;
		$sysCondition = $this->getPolicyValue($topc);
		$result = self::actionListPath($topc, $id, $settype, $page, $pagesize, $condition);
		return array('total'=>$this->total,'result'=>$result);
	}
	
}