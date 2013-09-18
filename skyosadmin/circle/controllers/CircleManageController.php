<?php
namespace circle\controllers;
use Sky\utils\Ftp;
use Sky\Sky;
use Sky\base\Controller;
use circle\models\CircleManageModel;
use skyosadmin\components\PolicyController;
use skyosadmin\components\Page;
use Sky\web\UploadFile;

class CircleManageController extends PolicyController {

	private $sidx;
	private $sord;
	private $_search;
	private $oper;
	private $searchOn;
	/*
	 *  apk 图标本地目录
	 */
	private $iconroot = "";

	/*
	 *  网站根目录,php脚本主目录
	 */
	private $root = "";

	/*
	 * 远程ftp 主目录
	 */
	private $rs_root = "";



	public function __construct(){
		$this->iconroot = UPLOADROOT.ICONROOT; //结果 = /rs/icon
		$this->root = ROOT; //结果= /data/cloudservice
		$this->rs_root = RS_ROOT; // 结果 = /data/www

		// get index row - i.e. user click to sort
		// at first time sortname parameter - after that the index from colModel
		$sidx = $_REQUEST['sidx']; //字段名
		// sorting order - at first time sortorder
		$this->sord = $_REQUEST['sord']; //asc or desc

		!isset($sidx) ? $this->sidx = 1:$this->sidx = $sidx;

		$this->searchOn = parent::Strip($_REQUEST['_search']);
	}

	//这是一个条件执行方法统一入口
	public function actionCircleManage(){
		//开启搜索列表
		if($this->searchOn=='true') {
			return $this->searchCircleManageList();
			//正常列表
		}else{
			return $this->CircleManageList();
		}
	}

	/*
	 *  正常列表
	 */
	public function CircleManageList(){

		$pager = new Page(CircleManageModel::getAllCircleCount(0)); //cc_id=0,all lists
		//处理分页
		$pager->prePage();

		$order = array($this->sidx=>$this->sord);

		//获取资源
		$res =  CircleManageModel::getAllCircleList(0,$pager->start,$pager->limit,$order);
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);

		return $arr;
	}

	/*
	 *  把一个列表数组为每项增加一个json字符串值
	 */
	public function arrayAddValueToJson($res){
		$temp =array();
		foreach($res as $key=>$value){
			$value['json_info'] = json_encode($value);
			$temp[] = $value;
		}
		return $temp;
	}

	/*
	 *  搜索列表
	 */
	public function searchCircleManageList(){

		$searchField = $_REQUEST['searchField'];
		$searchString  = parent::Strip($_REQUEST['searchString']);
			
		$searchArr = array($searchField=>$searchString);
		$pager = new Page(CircleManageModel::searchCircleCount($searchArr));
		//处理分页
		$pager->prePage();

		$order = array($this->sidx=>$this->sord);

		$res = CircleManageModel::searchCircle(
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
		print_r($_REQUEST);	
		if($oper=='edit'){
				
			return $this->edit( $_REQUEST );
				
		}elseif ($oper=='del'){
				
			return $this->del( $_REQUEST );
				
		}elseif ($oper=='add'){
				
			return $this->add($_REQUEST);
				
		}

	}

	/*
	 * 编辑
	 */
	public function add($addArr){


		$Arr = array(
    					'circle_title'=>$addArr['circle_title'],
    					'circle_content'=>$addArr['circle_content'],
    					'circle_pic'=>$addArr['circle_pic'],
				        'circle_state'=>$addArr['circle_state'],
				        'cc_id'=>$addArr['cc_id'],
				        'max_user_count'=>$addArr['max_user_count']
		);
		//返回1表示成功，返回0表示失败
		return CircleManageModel::insertCircle($Arr);
		//删除
	}

	/*
	 * 编辑
	 */
	public function edit($editArr){

		$Arr = array(
    					'circle_title'=>$editArr['circle_title'],
    					'circle_content'=>$editArr['circle_content'],
    					'circle_pic'=>$editArr['circle_pic'],
				        'circle_state'=>$editArr['circle_state'],
				        'cc_id'=>$editArr['cc_id'],
				        'max_user_count'=>$editArr['max_user_count'],
						'circle_id'=>$editArr['id']
		);
		//返回1表示成功，返回0表示失败
		return CircleManageModel::updateCircle($Arr);
		//删除
	}

	/*
	 * 删除
	 */
	public function del($delArr){
		$id = $delArr['id'];
		$rec = CircleManageModel::deleteCircle($id);
		return $rec;	//成功>0，失败0
	}

	/*
	 *  说明logo
	 */
	public function actionUploadIcon(){
		header('Content-Type: text/html;charset=utf-8');

		//上传
		$_path = $this->iconroot.'/';
		$localPath = $this->root.$_path; //包解压目录
			
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
			$zipPath=>$this->rs_root.$this->iconroot.'/'.$obj->getName(),
			);
			try {
				$ftped = parent::uploadFtp($uploadList);
			} catch (Exception $e) {
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
						'msg'=>'http://'.RS_HostName.'/'.$this->iconroot.'/'.$obj->getName(),
						'status'=>1
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

	public function actionCircleCatogeryManage(){
		//开启搜索列表
		if($this->searchOn=='true') {
			return $this->searchCircleCatogeryManageList();
			//正常列表
		}else{
			return $this->CircleCatogeryManageList();
		}
	}

	/*
	 *  搜索列表
	 */
	public function searchCircleCatogeryManageList(){

		$searchField = $_REQUEST['searchField'];
		$searchString  = parent::Strip($_REQUEST['searchString']);
			
		$searchArr = array($searchField=>$searchString);
		$pager = new Page(CircleManageModel::searchCircleCategoryCount($searchArr));
		//处理分页
		$pager->prePage();

		$order = array($this->sidx=>$this->sord);

		$res = CircleManageModel::searchCircleCategoryList(
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

	public function CircleCatogeryManageList(){

		$pager = new Page(CircleManageModel::getCircleCategoryCount()); //cc_id=0,all lists
		//处理分页
		$pager->prePage();

		$order = array($this->sidx=>$this->sord);

		//获取资源
		$res =  CircleManageModel::getCircleCategoryList($pager->start,$pager->limit,$order);

		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);

		return $arr;
	}

	function actionDoCatogeryOper(){

		$oper = $_REQUEST['oper'];
			
		if($oper=='edit'){
				
			return $this->editCatogery( $_REQUEST );
				
		}elseif ($oper=='del'){
				
			return $this->delCatogery( $_REQUEST );
				
		}elseif ($oper=='add'){
				
			return $this->addCatogery($_REQUEST);
				
		}
	}
	
		public function addCatogery($addArr){


		$Arr = array(
    					'cc_name'=>$addArr['cc_name'],
    					'logo'=>$addArr['logo'],
    					'cc_order'=>$addArr['cc_order']
		);
		//返回1表示成功，返回0表示失败
		return CircleManageModel::insertCircleCategory($Arr);
		//删除
	}

	/*
	 * 编辑
	 */
	public function editCatogery($editArr){


		$Arr = array(
    					'cc_name'=>$editArr['cc_name'],
    					'logo'=>$editArr['logo'],
    					'cc_order'=>$editArr['cc_order'],
						'cc_id'=>$editArr['id']
		);
		//返回1表示成功，返回0表示失败
		return CircleManageModel::updateCircleCategory($Arr);
		//删除
	}

	/*
	 * 删除
	 */
	public function delCatogery($delArr){
		$id = $delArr['id'];
		$rec = CircleManageModel::deleteCircleCategory($id);
		return $rec;	//成功>0，失败0
	}	
}