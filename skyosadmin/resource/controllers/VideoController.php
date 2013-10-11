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
	
	private $plugin_root = "";
	/*
	 *  网站根目录,php脚本主目录
	 */
	private $root = "";
	/*
	 * 远程ftp 主目录
	 */
	private $rs_root = "";
	
	/*
	 *  在加本类中所有方法前必加载此方法，方法最后必须以return true才生效
	 */
	public function beforeAction($action){
		$this->plugin_root = "/rs/zip"; //结果 = /rs/zip		
		$this->root = ROOT; //结果= /data/cloudservice
		$this->rs_root = RS_ROOT; // 结果 = /data/www
		
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
		//开启搜索列表
		if($this->request['searchOn']=='true') {
          return $this->SearchVideoForAudith();
		//正常列表
		}else{
		  return $this->GetVideoForAuditList();
		}
	}
	
	public function GetVideoForAuditList(){
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
	public function actionUpdateVideoLabel($audited,$label,$vs_id){
		return VideoModel::updateVideoLabel($vs_id,$label,$audited);
	}
	
	/**
	 * 更新解析插件
	 * */
	 
	public function actionUpdatePlugIn($download_url){
		$version = date('YmdHi');
		return VideoModel::updatePlugIn($version,$download_url);
	}
	
	/**
	 * 扫描当前页
	 * */
	 public function actionCheckCurrPage($id){
	 	$idArr = explode(',',$id);
	 	$urls = VideoModel::getURLById($idArr);
	 	try
	 	{
	 		foreach($urls as $url){
		 		$request = 'http://42.121.104.9/mediastatus/mediastatus.php?url=' . urlencode($url['url']);
		 		$data = @file_get_contents($request);
		 		if($data == '-1'){
		 			$width =-1;
		 			$height = -1;
		 			$run_time = -1;
		 		} 
		 		elseif($data == '-2'){
		 			$width =-2;
		 			$height = -2;
		 			$run_time = -2;
		 		}
		 		elseif(strpos($data,'duration')){
		 			$info = $this->getMeInfo($data);
					$width = $info['width'];
					$height = $info['height'];
					$duration = $info['duration'];
					$run_time = ceil((int)$duration / 1000000);
		 		} else {
		 			$width =-2;
		 			$height = -2;
		 			$run_time = -2;
		 		}
		 		VideoModel::updateVideoRunTime($url['vs_id'],$run_time,$width,$height);
	 		}
	 		return '1';
	 	}
	 	catch (Exception $e)
	 	{
	 		return $e->getMessage();
	 	}
	 	
	 }
	 
	/**
     * 解析返回数据
     * */
    public function getMeInfo($meinfo)
	{
		$meinfosplit = explode(',',$meinfo);
	    $width = $meinfosplit[0];
	    $width = explode('=',$width);
	    $width = trim($width[1]);
	    $height = $meinfosplit[1];
	    $height = explode('=',$height);
	    $height = trim($height[1]);
	    $duration = $meinfosplit[2];
	    $duration = explode('=',$duration);
	    $duration = trim($duration[1]);
	    $result = array('width'=>$width,'height'=>$height,'duration'=>$duration);
	    return $result;
	}
	 
	 /**
	  * 全库扫描
	  * */
	 public function actionCheckAll(){
	 	$cmd = 'nohup php /data/cloudservice/autorun/checkMedia.php &';
	 	try
	 	{
	 		exec($cmd,$op);
	 		echo $op[0];
	 		return '1';
	 	}
	 	catch (Exception $e)
	 	{
	 		return $e->getMessage();
	 	}
	 }
	
	/**
	 * 上传插件
	 */
	public function actionUploadPlugin(){
		header('Content-Type: text/html;charset=utf-8');
	
		//上传
		$_path = $this->plugin_root;
		$localPath = $this->root.$_path.'/'; //包解压目录
		//创建目录
		if(!is_dir($localPath)){	
			parent::RecursiveMkdir($localPath,0777);
		}
		$name = $_REQUEST['name'];
		$obj  = UploadFile::getInstanceByName($name);
		$zipPath = $localPath.$obj->getName();
		$uploaded = $obj->saveAs($zipPath);		
		
		if($uploaded){
	
			//FTP上传path目录
			$uploadList = array(					
					$zipPath=>$this->rs_root.$_path.'/'.$obj->getName(),
			);
			try {
				$ftped = parent::uploadFtp($uploadList);
			} catch (\Exception $e) {
				$arr =  array(
						'msg'=>$e->getMessage(),
						'status'=>0
				);
				Sky::$app->end(json_encode($arr));
			}
			//ftp上传
			if($ftped)
			{
				//parent::delete_folder($localPath); //执行这一句报错，因为目录里文件rmdir只能删除空目录
				//返回成功上传的url说明包页地址
				$arr = array(
						'msg'=>'http://'.RS_HostName.$this->plugin_root.'/'.$obj->getName(),
						'status'=>1,
						'md5'=>md5($zipPath)
				);
				Sky::$app->end(json_encode($arr));
	
			}
		}
		$arr = array(
				'msg'=>'上传失败',
				'status'=>0
		);
	
		Sky::$app->end(json_encode($arr));
	}
	
	/**
	 * 资源审核页面搜索功能
	 * */
	 public function SearchVideoForAudith(){
	 			//计算总数
				$pager = new Page(VideoModel::searchVideoForAuditCount($this->s));
				//处理分页
				$pager->prePage(); 
				
				$res = VideoModel::searchVideoForAuditList(
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
}