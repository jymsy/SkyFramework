<?php 
namespace appstore\controllers;
use Sky\utils\Ftp;
use Sky\Sky;
use Sky\base\Controller;
use appstore\models\SkyAppstoreModel; 
use skyosadmin\components\PolicyController;
use skyosadmin\components\Page;
 
 
class PlatformAndTypeController extends PolicyController {
	
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
	public function actionPlatform(){
		//开启搜索列表
		if($this->request['searchOn']=='true') {
          return $this->searchPlatformList();
		//正常列表
		}else{
		  return $this->PlatformList();
		}
	}
	

	 
	public function actionType(){
		//开启搜索列表
		if($this->request['searchOn']=='true') {
			return $this->searchTypeList();
			//正常列表
		}else{
			return $this->TypeList();
		}
	}
	 
	/*
	 *  正常列表
	 */
	public function PlatformList(){
		//计算总数
		$pager = new Page(SkyAppstoreModel::getAllPlatformCount()); 
		//处理分页
		$pager->prePage();
		//获取资源
		$res =  SkyAppstoreModel::getAllPlatform($pager->start,$pager->limit,$this->order);
	  
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
	public function searchPlatformList(){
		//计算总数
		$pager = new Page(SkyAppstoreModel::searchPlatformCount($this->s));
		//处理分页
		$pager->prePage();
		
		$res = SkyAppstoreModel::searchPlatformDetail(
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
	
  
	/*
	 *  正常列表
	*/
	public function TypeList(){
		//计算总数
		$pager = new Page(SkyAppstoreModel::getAllTypeCount());
		
		//处理分页
		$pager->prePage(); 
		//获取资源
		$res =  SkyAppstoreModel::getAllType($pager->start,$pager->limit,$this->order);
		 
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
	public function searchTypeList(){ 
		//计算总数
		$pager = new Page(SkyAppstoreModel::searchTypeCount($this->s));
		//处理分页
		$pager->prePage(); 
		
		$res = SkyAppstoreModel::searchTypeDetail(
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
	
	
	
	/*
	 *  @添加、删除，修改方法
	 */
	
	function actionDoTypeOper($oper){
		
		if($oper=='add'){ 
			return $this->addType( $this->request );
			
		}elseif ($oper=='edit'){
			
			return $this->editType( $this->request );
			
		}elseif ($oper=='del'){
			
			return $this->delType( $this->request );
			
		}
		
	}
	
	/*
	 *  添加方法
	 */
	public function addType( $addArr ){
		$arr = array(
			"product_type_name"=>$addArr['product_type_name'] 
		);
		return SkyAppstoreModel::insertType($arr);
	}
	
	/*
	 * 编辑
	 */
	public function editType($editArr){
		$arr = array(
			"product_type_name"=>$editArr['product_type_name'], 
			"product_type_id"=>$editArr['product_type_id']
		);
		return SkyAppstoreModel::updateType($arr);
	}
	
	/*
	 * 删除
	 */
	public function delType($delArr){
		return SkyAppstoreModel::deleteType($delArr['product_type_id']);
	}
	
	
	
	/*
	 *  @添加、删除，修改方法
	*/
	
	function actionDoPlatformOper($oper){
		
		if($oper=='add'){
 
			return $this->addPlatform($this->request['platform_info']);
				
		}elseif ($oper=='edit'){
				
			return $this->editPlatform( $this->request );
				
		}elseif ($oper=='del'){
				
			return $this->delPlatform( $this->request );
				
		}
	
	}
	
	/*
	 *  添加方法
	*/
	public function addPlatform( $platform_info ){
		return SkyAppstoreModel::insertPlatform($platform_info);
	}
	
	/*
	 * 编辑
	*/
	public function editPlatform($editArr){
		$arr = array(
			"platform_id"=>$editArr['id'],
			"platform_info"=>$editArr['platform_info'] 
		);
		return SkyAppstoreModel::updatePlatform($arr);
	}
	
	/*
	 * 删除
	*/
	public function delPlatform($delArr){
		return SkyAppstoreModel::deletePlatform($delArr['id']);
	}
	
 
  
}