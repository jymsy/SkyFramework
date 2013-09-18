<?php 
namespace resource\controllers;
use Sky\utils\Ftp;
use Sky\Sky;
use Sky\base\Controller;
use resource\models\WebSiteModel;
use resource\models\CategoryManageModel;
use skyosadmin\components\PolicyController;
use Sky\web\UploadFile;
 
class WebsiteNavigationController extends PolicyController {
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
    /*
     *  网址导航主分类ID
     *  
     */
    const MAIN_ID = 10;
	/*
	 *  上传主目录
	*/
	private $uploadroot = "";

	/*
	 *  网站根目录,php脚本主目录 
	*/
	private $root = "";
	/*
	 * 远程ftp 主目录 
	 */
	private $rs_root = "";
	
	/*
	 * logo本地目录 
	 */
	private $logoroot = "/website";
	
	
	
 
	
	public function __construct(){
		 
		$this->root = ROOT; //结果= /data/cloudservice 
		$this->rs_root = RS_ROOT; // 结果 = /data/www
		 
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
		$this->searchOn = parent::Strip($_REQUEST['_search']);
	}
	
	//这是一个条件执行方法统一入口
	public function actionNavigation(){
		//开启搜索列表
		if($this->searchOn=='true') {
          return $this->searchNavigationList();
		//正常列表
		}else{
		  return $this->NavigationList();
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
	 
	
	/*
	 *  正常列表
	 */
	public function NavigationList($category_id = ''){
		
		//计算总数
		$this->count = WebSiteModel::getWebSiteCount($category_id);
	 
		//处理分页
		$this->prePage();
		
		$order = array($this->sidx=>$this->sord); 
	 
		//获取资源
		$res =  WebSiteModel::getWebSiteList($this->start,$this->limit,$category_id,$order);
		
		$arr = array(
				"records"=>$this->count, //总条数
				"rows"=>$res,
				"total"=>$this->total_pages, //总页数
				"page"=>$this->page
		);
		 
		 
		return $arr;
	}
    
 

	/*
	 *  搜索列表
	 */
	public function searchNavigationList(){
	   
		$searchField = $_REQUEST['searchField'];
		$searchString  = parent::Strip($_REQUEST['searchString']); 
		 
		$searchArr = array($searchField=>$searchString);
		
		//计算总数
		$this->count = WebSiteModel::searchWebSiteCount($searchArr);
		//处理分页
		$this->prePage();
		 
		$order = array($this->sidx=>$this->sord);
		
		$res = WebSiteModel::searchWebSiteList(
				$searchArr,
				$this->start,
				$this->limit,$order);
		 
		$arr = array(
				"records"=>$this->count, //总条数
				"rows"=>$res,
				"total"=>$this->total_pages, //总页数
				"page"=>$this->page
		);
		return  $arr; 
	}
	
 
	
	/*
	 *  @添加、删除，修改方法
	 */
	
	function actionDoOper(){
		$oper = $_REQUEST['oper'];
		 if($oper=='add'){
		 	return $this->add( $_REQUEST );
		 }elseif ($oper=='edit'){
			return $this->edit( $_REQUEST ); 
		}elseif ($oper=='del'){
			return $this->del( $_REQUEST ); 
		}
	}
	
	/*
	 * 添加
	 */
	public function add($addArr){
		$Arr = array(
				 "site_name"=>$addArr['site_name'],
				 "site_url"=>$addArr['site_url'],
				 "site_logo"=>$addArr['site_logo'],
				 "site_big_logo"=>$addArr['site_big_logo'],
				"category_id"=>$addArr['category_id']
    		);
		return WebSiteModel::insertWebSite($Arr);
	}

	/*
	 * 编辑
	*/
	public function edit($editArr){
		$Arr = array(
				 "site_name"=>$editArr['site_name'],
				 "site_url"=>$editArr['site_url'],
				 "site_logo"=>$editArr['site_logo'],
				 "site_big_logo"=>$editArr['site_big_logo'],
				 "category_id"=>$editArr['category_id'],
				 "website_id"=>$editArr['website_id']
		); 
		return WebSiteModel::updateWebSite($Arr);
	}

	/*
	 * 删除
	*/
	public function del(){
		$id = $_REQUEST['website_id'];
		return WebSiteModel::deleteWebSite($id);
	}
	 
	
	
	/*
	 * 根据id取一条记录
	 */
	public function actionGetNavigationById(){
	    $id = $_REQUEST['id'];
		return WebSiteModel::getOneWebSiteById($id);
	}
	
	/*
	 * 通过分类id取列表
	*/
	public function actionNavigationByCategoryId(){
		$category_id = $_REQUEST['category_id'];
		return $this->NavigationList( $category_id );
	}
	
	/*
	 *  取分类
	 */
	public function actionGetCategory(){
		 $catArr = CategoryManageModel::getCategoryByParentID(self::MAIN_ID,0,1000);
		 $data = array();
		 if(!empty($catArr)){
		    foreach($catArr as $key=>$value){
		 	   $data[$value['category_id']] = $value['category_name'];
		    } 
		 }
		return $data;
	}
	 
	//本地上传Logo
	public function _upload($name){
		if($name=='') return null;
		header('Content-Type: text/html;charset=utf-8'); 
	 
		$_path = UPLOADROOT.$this->logoroot.'/'; //  /rs/website
		$path = $this->root.$_path; 
		$flag = time();
		$fileobj  = UploadFile::getInstanceByName($name); 
		
		$localPath = $path.$flag.$fileobj->getName();
		
		$uploaded = $fileobj->saveAs($localPath);
		
		if($uploaded){
			
			$uploadList = array(
					 //img是否已创建
					$localPath=>$this->rs_root.UPLOADROOT.$this->logoroot.'/'.$flag.$fileobj->getName()
			);
			
			//ftp上传
			if(parent::uploadFtp($uploadList))
			{
					$arr = array(
							'url'=> 'http://'.RS_HostName.$_path.$flag.$fileobj->getName()
					);
					//parent::delete_folder($localPath); //执行这一句报错，因为目录里文件rmdir只能删除空目录 
					exit(json_encode($arr));
			}
			return 0;
		}
		
		return 0;
	}
	
	
	/*
	 * 上传入口
	 */
	public function actionUpload(){
		$name = $_REQUEST['name'];
		$this->_upload($name);
	}
	
	
}