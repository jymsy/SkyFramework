<?php 
namespace resource\controllers;
 
use Sky\Sky;
use Sky\base\Controller;
use resource\models\MergeVideoModel; 
use skyosadmin\components\PolicyController;
use skyosadmin\components\Page;
 
 
class MergeVideoController extends PolicyController {
 
/*
	 * 用于保存所有url的参数
	 */
	private $request = array();
	/*
	 * 用于保存排序字段
	 */
	private $order = array();
	/*
	 * 用于保存搜索的字段
	 */
	private $s = array();
	
	/*
	 *  在加本类中所有方法前必加载此方法，方法最后必须以return true才生效
	 */
	public function beforeAction($action){
		//url中所有参数加载
		$this->request = parent::getActionParams();
		
		//搜索原始参数处理，是否开启搜索参数  _search=false 为关闭搜索，true为开启搜索
		if(isset($this->request['_search'])){
			$this->request['searchOn'] = parent::Strip($this->request['_search']);
		}
		//单字段排序处理
		if(isset($this->request['sidx'])&&isset($this->request['sord'])){
			$this->order = array($this->request['sidx']=>$this->request['sord']);
		}
		//单字段搜索处理
		if(isset($this->request['searchField'])&& isset($this->request['searchString'])){
			 $this->s = array(
			 		$this->request['searchField']=>parent::Strip($this->request['searchString'])
			 );
		}
		return true;
	}
	
	//这是一个条件执行方法统一入口
	public function actionMergeVideo($filters){
		//开启搜索列表
		if($this->request['searchOn']=='true') {
          return $this->getVideoByfilters($filters);
		//正常列表
		}else{
		  return $this->mergeVideoList();
		}
	}
	
  
	
	
	/*
	 *  正常列表
	 */
	public function mergeVideoList(){
		
		$pager = new Page(MergeVideoModel::getVideoListCount()); 
		//处理分页
		$pager->prePage(); 
		//获取资源
		$res =  MergeVideoModel::getVideoList($pager->start,$pager->limit,$this->order);
		
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
	
	public function searchMergeVideoList(){
	 
		//$searchField = $_REQUEST['searchField'];
		//$searchString  = parent::Strip($_REQUEST['searchString']);
		$v_pid = $_REQUEST['v_pid'];
		 
		//$searchArr = array($searchField=>$searchString);
		 
		$pager = new Page(MergeVideoModel::getVideoByPidCount($v_pid)); 
		//处理分页
		$pager->prePage();
		
		//$order = array($this->sidx=>$this->sord);
		
		$res = MergeVideoModel::getVideoByPid(
				$v_pid,
				$pager->start,
				$pager->limit);
		 
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		return  $arr; 
	}
	 */
	
	/*
	 *  @添加、删除，修改方法
	 */
	
	function actionDoOper($oper){
		if($oper=='merge'){
			return $this->merge(); 
		}
		
	}
	
 
	 
	
	public function merge($vid,$vpid){
	    $vid  = array_flip($vid); 
	    unset($vid[$vpid]);
	    $vid = array_flip($vid);
		//返回1表示成功，返回0表示失败
		return MergeVideoModel::updateVideoAndSite($vid,$vpid);
		//删除 
	}
	
	 
	
	
	
	/*
	 * 根据id取一条记录 
	 */
	public function actionGetVideoByVid($id){ 
		return MergeVideoModel::getVideoByVid($id);
	}
	
    /*
     *  根据搜索标题显示列表，子列表页面
     */
	 public function  getVideoByfilters( $filters ){
	 	
	 	$jsona = json_decode($filters,true);
	    $searchString = " AND ".parent::getStringForGroup($jsona);
	   
	 	//$searchArr = array($searchField=>$searchString);
	 	$pager = new Page(MergeVideoModel::getVideoTitleCount($searchString));
	 
	 	//处理分页
	 	$pager->prePage();
	   
	 
	 	$res = MergeVideoModel::getVideoTitleRelation(
	 			$searchString,
	 			$pager->start,
	 			$pager->limit,$this->order);
	 		
	 	$arr = array(
	 			"records"=>$pager->count, //总条数
	 			"rows"=>$res,
	 			"total"=>$pager->total_pages, //总页数
	 			"page"=>$pager->page
	 	);
	 	return  $arr;
	 }
	 
	
 
	
}