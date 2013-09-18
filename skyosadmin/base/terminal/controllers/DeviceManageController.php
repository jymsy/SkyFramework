<?php 
namespace base\terminal\controllers;
use Sky\Sky;
use Sky\base;
use Sky\base\Controller;
use skyosadmin\components\PolicyController; 
use skyosadmin\components\Page;
use base\terminal\models\DeviceManageModel;
 
class DeviceManageController extends PolicyController {
 
	private $sidx;
	private $sord; 
	private $_search;
	private $oper; 
    private $searchOn;
 
	
	public function __construct(){
		// get index row - i.e. user click to sort
		// at first time sortname parameter - after that the index from colModel
		$sidx = $_REQUEST['sidx']; //字段名 
		// sorting order - at first time sortorder
		$this->sord = $_REQUEST['sord']; //asc or desc
		  
		!isset($sidx) ? $this->sidx = 1:$this->sidx = $sidx; 
		
		$this->searchOn = parent::Strip($_REQUEST['_search']);
	}
	
	//这是一个条件执行方法统一入口
	public function actionTerminalInfo(){
		//开启搜索列表
		if($this->searchOn=='true') {
          return $this->searchTerminalInfoList();
		//正常列表
		}else{
		  return $this->TerminalInfoList();
		}
	}
	

	/*
	 *  正常列表
	 */
	public function TerminalInfoList(){
		$pager = new Page(DeviceManageModel::getDeviceCount()); 
		//处理分页
		$pager->prePage();
		
		$order = array($this->sidx=>$this->sord); 
	 
		//获取资源
		$res =  DeviceManageModel::getDeviceList($pager->start,$pager->limit,$order);
		
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		
		return $arr;
	}
    
	/*
	 *  把一个列表数组为每项增加一个json字符串值 
	 */
	public function arrayAddValueToJson($res){
		$temp =array();
		foreach($res as $key=>$value){
			$value['json_info'] = json_encode($value);
			$temp[] = $value;
		}
		return $temp;
	}

	/*
	 *  搜索列表
	 */
	public function searchTerminalInfoList(){
	   
		$searchField = $_REQUEST['searchField'];
		$searchString  = parent::Strip($_REQUEST['searchString']);
		 
		$searchArr = array($searchField=>$searchString);
		$pager = new Page(DeviceManageModel::searchDeviceCount($searchArr)); 
		//处理分页
		$pager->prePage();
		
		$order = array($this->sidx=>$this->sord);
		
		$res = DeviceManageModel::searchDeviceDetail(
				$searchArr,
				$pager->start,
				$pager->limit,$order);
		 
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		return  $arr; 
	}
}