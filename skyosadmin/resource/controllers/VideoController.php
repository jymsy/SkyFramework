<?php 
namespace resource\controllers;
use Sky\utils\Ftp;
use Sky\Sky;
use Sky\base\Controller;
use resource\models\VideoModel; 
use skyosadmin\components\PolicyController;
use Sky\web\UploadFile;
use skyosadmin\components\Page;
 
class VideoController extends PolicyController { 
	
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
	public function actionVideo(){
		//开启搜索列表
		if($this->request['searchOn']=='true') {
          return $this->searchVideoList();
		//正常列表
		}else{
		  return $this->VideoList();
		}
	}
	
 
	 
	
	/*
	 *  正常列表
	 */
	public function VideoList(){
		
		$pager = new Page(VideoModel::getVideoCount());
		//处理分页
		$pager->prePage();  
	 
		//获取资源
		$res =  VideoModel::getVideoList($pager->start,$pager->limit,$this->order);
		
	 
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$this->translateSource( $res ),
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		  
		return $arr;
	}
    
 

	/*
	 *  搜索列表
	 */
	public function searchVideoList(){
		//计算总数
		$pager = new Page(VideoModel::searchVideoCount($this->s));
		//处理分页
		$pager->prePage(); 
		
		$res = VideoModel::searchVideoList(
				$this->s,
				$pager->start,
				$pager->limit,$this->order);
		 
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$this->translateSource( $res ),
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		return  $arr; 
	}
	
 
	
	/*
	 *  @添加、删除，修改方法
	 */
	
	function actionDoOper($oper){ 
		 if($oper=='add'){
		 	return null;
		 }elseif ($oper=='edit'){
			$this->edit( $this->request ); 
		}elseif ($oper=='del'){
			$this->del( $this->request ); 
		}
	}
	
	/*
	 *  上下架
	*/ 
	public function actionVideoSale($oper){
		$id = $this->request['v_id'];
		$id = "'".join("','", $id)."'";
		if(isset($id)){
			if($oper=='offsale'){
			 return	VideoModel::videoOffSale($id);
			}elseif ($oper=='onsale'){
			 return	VideoModel::videoOnSale($id);
			}
		}
		//没有设置id
	   return NULL; 
	}
	 

	/*
	 * 编辑
	*/
	public function edit($editArr){
		$Arr = array( 
				'title'=>$editArr['title'],
				'actor'=>$editArr['actor'],
				'classfication'=>$editArr['classfication'],
				'total_segment'=>$editArr['total_segment'],
				'v_id'=>$editArr['id']
		); 
		return VideoModel::updateVideo($Arr);
	}

	/*
	 * 删除
	*/
	public function del($id){
		return VideoModel::deleteVideo($id);
	} 
	
	/*
	 * 取影视分类
	*/
	public function actionGetCategory(){
		$cat = VideoModel::getCategroy();
		$arr = array();
		foreach( $cat as $key=>$value){
			$arr[$value['category']] = $value['category_name'];
		}
	   return array_filter($arr);
	}
	
	
	/*
	 * 翻译来源字段
	*/
	public function translateSource($arr){
		$sourceName = VideoModel::getSourceName();
		foreach($arr as $key=>$value){
			if(isset($value['source']) && !empty($value['source'])){
				$value['source_name'] = $sourceName[$value['source']];
			}else{
				$value['source_name'] = '未知';
			}
			$arr[$key]=$value;
		}
		return $arr;
	}
	
	/*
	 * 来源值 
	 */
	public  function actionGetSource(){
		$source  = VideoModel::getSource();
		
		$arr = array();
		foreach($source as $key =>$value){
			$arr[$value['source']] = $value['source_name'];
		}
		
		return array_filter($arr);
	}
	 
	
	
	/*
	 * 按字段条件取影视列表
	 * 如按分类。。。。。
	 */
	public function actionGetVideoByFieldCondition(){
	 
		
		//计算总数
		$pager = new Page(VideoModel::getVideoByConditionCount($this->s));
		//处理分页
		$pager->prePage();
 
		
		$res = VideoModel::getVideoByConditionList(
				$this->s,
				$pager->start,
				$pager->limit,$this->order);
			
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$this->translateSource($res),
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		return  $arr;
		
	}
	
	/*
	 * 用于推荐在电视主页上面的资源
	 */
	//这是一个条件执行方法统一入口
	public function actionRecomVideo(){
		//开启搜索列表
		if($this->request['searchOn']=='true') {
			return $this->searchRecomVideoList();
			//正常列表
		}else{
			return $this->recomVideoList();
		}
	}
	
	public function searchRecomVideoList(){
 
		//计算总数
		$pager = new Page(VideoModel::searchSourceListCount($this->s));
		//处理分页
		$pager->prePage(); 
		
		$res = VideoModel::searchSourceList(
				$this->s,
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
	 
	public function recomVideoList(){
		$pager = new Page(VideoModel::getSourceListCount());
		//处理分页
		$pager->prePage(); 
		//获取资源
		$res =  VideoModel::getSourceList($pager->start,$pager->limit,$this->order);
		
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		
		return $arr;
	}
	
   public function actionGetVideoSiteList($v_id){
		//计算总数
		$pager = new Page(VideoModel::getVideoSiteCount($v_id));
		//处理分页
		$pager->prePage(); 
		
		$res = VideoModel::getVideoSiteList(
				$v_id,
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
	
   /**
    * 片源扫描结果列表
    * */	
	public function actionGetVideoForAuditList(){
		//计算总数
		$pager = new Page(VideoModel::getVideoForAuditCount());
		//处理分页
		$pager->prePage(); 
		
		$res = VideoModel::getVideoForAuditList(
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

	/**
	 * 片源扫描结果处理
	 * @param audited 操作审核
	 * @param label 标注
	 * */
	public function actionUpdateVideoLabel($editArr){
		$Arr = array(
				'audited'=>$editArr['audited'],
				'label'=>$editArr['label'],
				'vs_id'=>$editArr['vs_id']
		); 
		return VideoModel::updateVideoLabel($Arr);
	}
	
	/**
	 * 更新解析插件
	 * */
	 
	public function actionUpdatePlugIn($editArr){
		$Arr = array(
				'version'=>date('YmdHi'),
				'download_url'=>$editArr['download_url']
		); 
		return VideoModel::updatePlugIn($Arr);
	}
	
}