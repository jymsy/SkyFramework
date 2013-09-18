<?php
namespace resource\controllers;


use resource\models\InfoManageModel;

use Sky\Sky;
use Sky\base\Controller;
use skyosadmin\components\PolicyController; 


class NewsManageController extends PolicyController {
	const baseurl = "http://localhost/Framework/tvos/index.php";
	//const baseurl = "http://dev.tvos.skysrt.com/Framework/tvos/index.php";
	private $page;
	private $limit;
	private $sidx;
	private $sord;
	private $count;
	private $total_pages;
	private $start;
	private $_search;
	private $oper; 
    private $searchOn;
   
	
	public function actions() {
		return array(
				"Wsdl"=>array(
						"class"=>"Sky\\base\\WebServiceAction"
				),
		);
	}
	
	public function actionTest(){
		echo 'aa';
	}
	
	public function __construct(){
		
		// get the requested page
		$this->page = $_REQUEST['page'];
		// get how many rows we want to have into the grid
		// rowNum parameter in the grid
		$this->limit = $_REQUEST['rows'];
		// get index row - i.e. user click to sort
		// at first time sortname parameter - after that the index from colModel
		$sidx = $_REQUEST['sidx']; //字段名 
		// sorting order - at first time sortorder
		$this->sord = $_REQUEST['sord']; //asc or desc
		  
		!isset($sidx) ? $this->sidx = 1:$this->sidx = $sidx; 
		$this->sidx==''?$this->sidx=1:'';
		$this->searchOn = parent::Strip($_REQUEST['_search']);
	}
	
	public function actiongetList(){
		if ($this->searchOn == 'true'){
			return $this->getSearchList();
		}else{
			return $this->getList();
		}
	}
	
	/*
	 *  @添加、删除，修改方法
	 */
	
	function actionDoOper(){
	     
		$oper = $_REQUEST['oper']; 
		 
		if($oper=='edit'){
			
			$this->edit( $_REQUEST );
			
		}elseif ($oper=='del'){
			
			$this->del( $_REQUEST );
			
		}elseif ($oper == 'add'){
			
			//$this->add($_REQUEST);
			
		}
		
	}
	
	public function actiongetCategory(){
		$url = self::baseurl."?_r=res/Api/ListCategory&cid=1005&page=0&pagesize=1&ws&_new";
		$rs = json_decode(file_get_contents($url));
		$total = $rs->total;
		$url = self::baseurl."?_r=res/Api/ListCategory&cid=1005&page=0&pagesize=$total&ws&_new";
		$rs = json_decode(file_get_contents($url));
		$detials = $rs->result;
		$array = array();
		foreach ($detials as $mid){
			$array[$mid->id] = $mid->name;
		}
		return $array;
	}
	
	public function actionPriceInfo(){
		$array = array();
		$array['1'] = "外网收费";
		$array['0'] = "外网免费";
		return $array;
	}
	
	
	//处理分页参数预先存入对像属性字段中
	public function prePage(){
		 
		if( $this->count >0 ) {
			$this->total_pages = ceil($this->count/$this->limit);
		} else {
			$this->total_pages = 0;
		}
		
		if ($this->page > $this->total_pages) 
			$this->page = $this->total_pages;
		
		// do not put $limit*($page - 1)
		$this->start = $this->limit*$this->page - $this->limit; 
 
		if($this->start <0) $this->start = 0; 
	}
	
	public function getList(){
		$this->count = InfoManageModel::getNewsCount();
		$this->prePage();
		$order = array($this->sidx=>$this->sord);
		$arr = array(
				"records"=>$this->count, //总条数
				"rows"=>InfoManageModel::getNewsList($this->start, $this->limit,$order),
				"total"=>$this->total_pages, //总页数
				"page"=>$this->page
		);
		return $arr;
	}
	
	public function getSearchList(){
		$searchField = $_REQUEST['searchField'];
		$searchString  = parent::Strip($_REQUEST['searchString']); 
		 
		$searchArr = array($searchField=>$searchString);
		
		$this->count = InfoManageModel::searchNewsCount($searchArr);
		$this->prePage();
		$order = array($this->sidx=>$this->sord);
		$arr = array(
				"records"=>$this->count, //总条数
				"rows"=>InfoManageModel::searchNewsList($searchArr, $this->start, $this->limit,$order),
				"total"=>$this->total_pages, //总页数
				"page"=>$this->page
		);
		return $arr;
	}
	
	
	public function edit($array){
		InfoManageModel::updateNews($array);
		$detial = $array['detail'];
		$news_id = $array['news_id'];
		return InfoManageModel::updateNewsDetail($detial,$news_id); 
	}
	
	public function del($array){
		$id = $array['news_id'];
		return InfoManageModel::deleteNews($id);
	}
	
	
	
	
	
}