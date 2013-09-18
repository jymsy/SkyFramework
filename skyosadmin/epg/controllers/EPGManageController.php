<?php
namespace epg\controllers;
use Sky\Sky;
use Sky\base\Controller;
use epg\models\EPGManageModel;
use skyosadmin\components\PolicyController;
use skyosadmin\components\Page;

class EPGManageController extends PolicyController {

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
	public function actionEPGManage(){
		//开启搜索列表
		if($this->searchOn=='true') {
			return $this->searchEPGManageList();
			//正常列表
		}else{
			return $this->EPGManageList();
		}
	}

	/*
	 *  正常列表
	 */
	public function EPGManageList(){

		$pager = new Page(EPGManageModel::getNewProgramCount());
		//处理分页
		$pager->prePage();

		$order = array($this->sidx=>$this->sord);

		//获取资源
		$res =  EPGManageModel::getNewProgram($pager->start,$pager->limit,$order);

		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);

		return $arr;
	}


	/*
	 *  搜索列表
	 */
	public function searchEPGManageList(){

		$searchField = $_REQUEST['searchField'];
		$searchString  = parent::Strip($_REQUEST['searchString']);
			
		$searchArr = array($searchField=>$searchString);
		$pager = new Page(EPGManageModel::searchNewProgramCount($searchArr));
		//处理分页
		$pager->prePage();

		$order = array($this->sidx=>$this->sord);

		$res = EPGManageModel::searchNewProgram(
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

	/*
	 *  @添加、删除，修改方法
	 */

	function actionDoOper(){

		$oper = $_REQUEST['oper'];
			
		if($oper=='edit'){
				
			return $this->edit( $_REQUEST );
				
		}

	}

	/*
	 * 编辑
	 */
	public function edit($editArr){
		//返回1表示成功，返回0表示失败
		return EPGManageModel::updateProgram($editArr['id'],$editArr['pg_name'],$editArr['epg_cat_id']);
		//删除
	}

	/*
	 * 删除
	 */
	public function del($delArr){
		$id = $delArr['id'];
		$rec = EPGManageModel::deleteEPGCategory($id);
		return $rec;	//成功>0，失败0
	}
	
	

	//这是一个条件执行方法统一入口
	public function actionEPGCategoryManage(){
		//开启搜索列表
		if($this->searchOn=='true') {
			return $this->searchEPGCategoryManageList();
			//正常列表
		}else{
			return $this->EPGCategoryManageList();
		}
	}

	/*
	 *  正常列表
	 */
	public function EPGCategoryManageList(){

		$pager = new Page(EPGManageModel::getAllEPGCategoryCount());
		//处理分页
		$pager->prePage();

		$order = array($this->sidx=>$this->sord);

		//获取资源
		$res =  EPGManageModel::getAllEPGCategoryList($pager->start,$pager->limit,$order);

		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);

		return $arr;
	}


	/*
	 *  搜索列表
	 */
	public function searchEPGCategoryManageList(){

		$searchField = $_REQUEST['searchField'];
		$searchString  = parent::Strip($_REQUEST['searchString']);
			
		$searchArr = array($searchField=>$searchString);
		$pager = new Page(EPGManageModel::searchEPGCategoryCount($searchArr));
		//处理分页
		$pager->prePage();

		$order = array($this->sidx=>$this->sord);

		$res = EPGManageModel::searchEPGCategory(
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

	/*
	 *  @添加、删除，修改方法
	 */

	function actionDoEPGCategoryOper(){

		$oper = $_REQUEST['oper'];
			
		if($oper=='edit'){
				
			return $this->editEPGCategory( $_REQUEST );
				
		}elseif ($oper=='del'){
				
			return $this->delEPGCategory( $_REQUEST );
				
		}elseif($oper=='add'){
			return $this->addEPGCategory( $_REQUEST );
		}

	}

	/*
	 * 编辑
	 */
	public function addEPGCategory($addArr){

		$Arr = array(
    					'epg_cat_name'=>$addArr['epg_cat_name'],
    					'index'=>$addArr['index']
		);
		//返回1表示成功，返回0表示失败
		return EPGManageModel::insertEPGCategory($Arr);
		//删除
	}
	
	/*
	 * 编辑
	 */
	public function editEPGCategory($editArr){

		$Arr = array(
    					'epg_cat_name'=>$editArr['epg_cat_name'],
    					'index'=>$editArr['index'],
    					'epg_cat_id'=>$editArr['id']
		);
		//返回1表示成功，返回0表示失败
		return EPGManageModel::updateEPGCategory($Arr);
		//删除
	}

	/*
	 * 删除
	 */
	public function delEPGCategory($delArr){
		$id = $delArr['id'];
		$rec = EPGManageModel::deleteEPGCategory($id);
		return $rec;	//成功>0，失败0
	}
}