<?php 
namespace resource\controllers;
use Sky\utils\Ftp;
use Sky\Sky;
use Sky\base\Controller;
use resource\models\MenuManageModel; 
use skyosadmin\components\PolicyController;
use skyosadmin\components\Page;
use Sky\web\UploadFile;
 
class IndexMenuController extends PolicyController {
		/*
	 *  远程接口基本访问地址
	*/
	private $remote_url = "";
	/*
	 *  apk包本地目录
	*/
	private $apkroot = "";
	/*
	 *  apk 图标本地目录
	*/
	private $iconroot = "";
	
	/*
	 *  上传主目录
	*/
	private $uploadroot = "";
	
	/*
	 *  网站根目录,php脚本主目录
	*/
	private $root = "";
	
	/*
	 * 上传压缩包目录
	*/
	private $ziproot = "";
	
	/*
	 * 上传网站目录 
	*/
	private $websiteDir = "/website";
	
	/*
	 * 远程ftp 主目录
	*/
	private $rs_root = "";
	
	
	
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
		$this->root = ROOT; //结果= /data/cloudservice
		$this->ziproot = UPLOADROOT.ZIPROOT; //结果 = /rs/zip
		$this->websiteDir = UPLOADROOT.$this->websiteDir;  // 结果 = /rs/website
		$this->rs_root = RS_ROOT; // 结果 = /data/www
	
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
	
	
  
	
	
	
	/*
	 *  首页菜单管理
	 */
	public function actionIndexMenu(){
		//开启搜索列表
		if($this->request['searchOn']=='true') {
          return $this->searchIndexMenuList();
		//正常列表
		}else{
		  return $this->indexMenuList();
		}
	}
	
	
	/*
	 * 首页内容管理
	 */
	public function actionIndexContent(){
		//开启搜索列表
		if($this->request['searchOn']=='true') {
			return $this->searchIndexContentList();
			//正常列表
		}else{
			return $this->indexContentList();
		}
	}
	
	
	/*
	 *  @添加、删除，修改方法
	*/
	
	public function actionDoOperContent($oper){
		if($oper=='add'){
			return $this->addContent( $this->request );
		}elseif ($oper=='edit'){
			return $this->editContent( $this->request );
		}elseif ($oper=='del'){
			return $this->delContent( $this->request );
		}
	}
	
	

	/*
	 *  @添加、删除，修改方法
	*/
	
	public function actionDoOperMenu($oper){
		if($oper=='add'){
			return $this->addMenu( $this->request );
		}elseif ($oper=='edit'){
			return $this->editMenu( $this->request );
		}elseif ($oper=='del'){
			return $this->delMenu( $this->request );
		}
	}
	
	public function searchIndexMenuList(){

		 
		$pager = new Page(MenuManageModel::searchMenuCategoryCount($this->s));
		//处理分页
		$pager->prePage();
		
		 
		$res = MenuManageModel::searchMenuCategory(
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
	
	public function indexMenuList(){
		$pager = new Page(MenuManageModel::getMenuCategoryCount());
		//处理分页
		$pager->prePage();
		 
		
		//获取资源
		$res =  MenuManageModel::getMenuCategory($pager->start,$pager->limit,$this->order);
		
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		
		return $arr;
		
	}
	
    public function actionGetMenuList(){
		$res =  MenuManageModel::getMenuCategory(0,100); 
		$arr = array();
		foreach($res as $key=>$value){
			$arr[$value["menu_cat_id"]] = $value["menu_cat_name"];
		}
		return $arr;
	}
	
	public function searchIndexContentList(){
 
		$pager = new Page(MenuManageModel::searchMenuResMapCount($this->s));
		//处理分页
		$pager->prePage();
		 
		$res = MenuManageModel::searchMenuResMap(
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
	
	public function indexContentList(){
		$pager = new Page(MenuManageModel::getMenuResMapCount());
		//处理分页
		$pager->prePage();
		 
		//获取资源
		$res =  MenuManageModel::getMenuResMap($pager->start,$pager->limit,$this->order);
		
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		
		return $arr;
	}
	
	
	public function addContent($arr){
		 
		$Arr = array(
				  "menu_cat_id" =>$arr['menu_cat_id'],
				  "res_id" =>$arr['res_id'],
				  "res_type" =>$arr['res_type'],
				  "title" =>$arr['title'],
				  "url" =>$arr['url'],
				  "img_url" =>$arr['img_url'],
				  "img_url_big" =>$arr['img_url_big'],
				  "index" =>$arr['index'],
				  "state"=>$arr['state'],
				  "pa_name" =>$arr['pa_name'],
				  "platform_info" =>$arr['platform_info'],
				  "source" =>$arr['source'],
				  "pic_flag" =>$arr['pic_flag'],
				  "version" =>$arr['version'],
				  "version_int" =>$arr['version_int'],
				  "pre_url" =>$arr['pre_url'],
				  "res_size" =>$arr['res_size']
		
		);
		return  MenuManageModel::insertMenuResMap($Arr);
	}
	
	public function editContent($arr){
		$Arr = array(
				"menu_cat_id" =>$arr['menu_cat_id'],
				"res_id" =>$arr['res_id'],
				"res_type" =>$arr['res_type'],
				"title" =>$arr['title'],
				"url" =>$arr['url'],
				"img_url" =>$arr['img_url'],
				"img_url_big" =>$arr['img_url_big'],
				"index" =>$arr['index'],
				"state"=>$arr['state'],
				"pa_name" =>$arr['pa_name'],
				"platform_info" =>$arr['platform_info'],
				"source" =>$arr['source'],
				"pic_flag" =>$arr['pic_flag'],
				"version" =>$arr['version'],
				"version_int" =>$arr['version_int'],
				"res_size" =>$arr['res_size'],
				"pre_url" =>$arr['pre_url'],
				"menu_res_map_id" =>$arr['id']
		);
		return  MenuManageModel::updateMenuResMap($Arr);
	}
	public function delContent($arr){
		$id = $arr['id'];
		$rec = MenuManageModel::deleteMenuResMap($id);
		return $rec;	//成功>0，失败0
	}
	public function addMenu($arr){
		$Arr = array(
			   "menu_cat_name"=>$arr['menu_cat_name'],
			   "menu_cat_type"=>$arr['menu_cat_type'],
			   "pack_name"=>$arr['pack_name'],
			   "pack_para"=>$arr['pack_para']
		);
		return  MenuManageModel::insertMenuCategory($Arr);
	}
	public function editMenu($arr){
		$Arr = array(
				  "menu_cat_name"=>$arr['menu_cat_name'],
				  "menu_cat_type"=>$arr['menu_cat_type'],
				  "pack_name"=>$arr['pack_name'],
				  "pack_para"=>$arr['pack_para'],
				  "menu_cat_id"=>$arr['id']
		);
		return  MenuManageModel::updateMenuCategory($Arr);
	}
	
	public function delMenu($arr){
		$id = $arr['id'];
		$rec = MenuManageModel::deleteMenuCategory($id);
		return $rec;	//成功>0，失败0
	}
	
	/*
	 *   上传方法
	*/
	public function actionUpload($file){
		header('Content-Type: text/html;charset=utf-8');
		$fileobj  = UploadFile::getInstanceByName($file);
		$_path = $this->websiteDir.'/'; //  /rs/website
		$path = $this->root.$_path;
		$flag = time();
		
		$localPath = $path.$flag.$fileobj->getName();
		
		$uploaded = $fileobj->saveAs($localPath);
		
		if($uploaded){
				
			$uploadList = array(
					//img是否已创建
					$localPath=>$this->rs_root.$this->websiteDir.'/'.$flag.$fileobj->getName()
			);
				
			//ftp上传
			if(parent::uploadFtp($uploadList))
			{
				Sky::$app->end(json_encode(array(
				'msg'=> 'http://'.RS_HostName.$_path.$flag.$fileobj->getName(),
				'status'=>1
				)));
			}
				
		
			Sky::$app->end(json_encode(array(
			'msg'=>'ftp上传失败',
			'status'=>0
			)));
		}
			
		Sky::$app->end(json_encode(array(
		'msg'=>'本地上传失败',
		'status'=>0
		)));
	}
 
 
	
	
	
	 
	
}