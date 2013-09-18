<?php
namespace resource\controllers;

use resource\models\ContentAuditModel;

use skyosadmin\models\ContentAuditMode;

use Sky\Sky;
use Sky\base\Controller;
use skyosadmin\components\PolicyController;
use skyosadmin\components\Page;


class AuditController extends PolicyController {
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


	public function actiongetInvalidSourceCount(){
		return ContentAuditModel::getInvalidSourceCount();
	}

	public function actiongetInvalidSource($page,$pagesize,$order){
		$list = ContentAuditModel::getInvalidSource($page, $pagesize,$order);
		foreach ($list as &$li){
			$mid = explode('#', $li['append']);
			if ($mid[0]!=''){
				$detial = ContentAuditModel::getVideoById($mid[0]);
				$li['detial'] = '';
				if ($detial['title'])
				$li['detial'] .= "名称:".$detial['title'];
				switch ($detial['category']){
					case 'dm':
						$mid = '动漫';
						break;
					case 'dy':
						$mid = '电影';
						break;
					case 'dsj':
						$mid = '电视剧';
						break;
					case 'zy':
						$mid = '综艺';
						break;
					default:
						$mid = '';
				}
				if ($mid)$li['detial'] .= "类型:".$mid;
			}else {
				$li['detial'] = '';
			}
		}
		return $list;
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

	public function actiongetAllSource(){
		$this->count = self::actiongetInvalidSourceCount();
		$this->prePage();
		$order = array($this->sidx=>$this->sord);
		$arr = array(
				"records"=>$this->count, //总条数
				"rows"=>self::actiongetInvalidSource($this->page, $this->limit,$order),
				"total"=>$this->total_pages, //总页数
				"page"=>$this->page
		);
		return $arr;/**/
	}

	function actionDoOper(){

		$oper = $_REQUEST['oper'];
		$sid = $_REQUEST['log_playurl_id'];

		if($oper=='offsale'){

			$this->actionexpiredVideo($sid);

		}elseif ($oper=='ignore'){

			$this->actiondeleteInvalidSource($sid);

		}

	}

	public function actionexpiredVideo($sid){
		$append = ContentAuditModel::getAppend($sid);
		//		$mid = explode('#', $append);
		//		if ($mid[0]!='') {
		//		}
		ContentAuditModel::expiredVideo($append);
		self::actiondeleteInvalidSource($sid);
		//ContentAuditModel::deleteInvalidSource($sid);
	}

	public function actiondeleteInvalidSource($sid){
		ContentAuditModel::deleteInvalidSource($sid);
	}

	public function getVideoById($vid){
		return ContentAuditModel::getVideoById($vid);
	}

	public function actionGetInvalidSourceNew(){

		$pager = new Page(ContentAuditModel::getInvalidSourceCount());
		//处理分页
		$pager->prePage();

		$order = array($this->sidx=>$this->sord);

		//获取资源
		$res =  ContentAuditModel::getInvalidSource($pager->start,$pager->limit,$order);

		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
			
			
		return $arr;
	}

	public function actionGetVideoSite(){

		$v_id = $_REQUEST['v_id'];
		//获取资源
		$res =  ContentAuditModel::getVideoSite($v_id);

		$arr = array(
				"records"=>count($res), //总条数
				"rows"=>$res,
				"total"=>1, //总页数
				"page"=>1
		);
						
		return $arr;
	}

	public function actionExpiredVideoSite(){

		$vs_id = $_REQUEST['vs_id'];
		$log_playurl_id = $_REQUEST['log_playurl_id'];

		$res =  ContentAuditModel::expiredVideoSite($vs_id,$log_playurl_id);

		return $res;
	}
	
	public function actionIgnoredVideo(){

		$log_playurl_id = $_REQUEST['log_playurl_id'];
		//获取资源
		$res =  ContentAuditModel::ignoredVideo($log_playurl_id);

		return $res;
	}

}