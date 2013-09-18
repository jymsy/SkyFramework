<?php
namespace resource\controllers;


use resource\models\HotWordModel;

use Sky\Sky;
use Sky\base\Controller;
use skyosadmin\components\PolicyController; 


class HotWordController extends PolicyController {
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
			return $this->getlist();
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
			
			$this->add($_REQUEST);
			
		}
		
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
	
	public function getSearchList(){
		$key = parent::Strip($_REQUEST['searchString']);
		$this->count = HotWordModel::searchHotwordCount($key);
		$this->prePage();
		$order = array($this->sidx=>$this->sord);
		$arr = array(
				"records"=>$this->count, //总条数
				"rows"=>HotWordModel::searchHotwordLists($key, $this->page, $this->limit,$order),
				"total"=>$this->total_pages, //总页数
				"page"=>$this->page
		);
		return $arr;
	}
	
	public function getlist(){
		$this->count = HotWordModel::getHotWordCount();
		$this->prePage();
		$order = array($this->sidx=>$this->sord);
		$arr = array(
				"records"=>$this->count, //总条数
				"rows"=>HotWordModel::get_hot_word_lists($this->page, $this->limit,$order),
				"total"=>$this->total_pages, //总页数
				"page"=>$this->page
		);
		return $arr;
	}
	
	public function add($array){
		$key = $array['key'];
		$num = $array['num'];
		return HotWordModel::addHotword($key, $num);
	}
	
	public function edit($array){
		$key = $array['key'];
		$num = $array['num'];
		return HotWordModel::updateHotword($key, $num);
	}
	
	public function del($array){
		$key = $array['key'];
		return HotWordModel::delHotword($key);
	}
	
	
	
	
	
	
	
	
}