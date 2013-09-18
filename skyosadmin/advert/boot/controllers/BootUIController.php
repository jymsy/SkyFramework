<?php
namespace advert\boot\controllers;

use Sky\Sky;
use Sky\base\Controller;
use advert\boot\models\BootUIManageModel;
use skyosadmin\components\PolicyController;
use Sky\utils\Ftp;
use Sky\web\UploadFile;
use skyosadmin\components\Page;

class BootUIController extends PolicyController {
	
	private $sidx;
	private $sord;
	private $_search;
	private $oper;
	private $searchOn;
	
	private $bootui = "";
	/*
	 *  网站根目录,php脚本主目录
	 */
	private $root = "";
	/*
	 * 远程ftp 主目录
	 */
	private $rs_root = "";
	
	public function __construct(){
		$this->bootui = BOOTUIROOT; //结果 = /rs/bootui		
		$this->root = ROOT; //结果= /data/cloudservice
		$this->rs_root = RS_ROOT; // 结果 = /data/www
		
		$sidx = $_REQUEST['sidx']; //字段名
		$this->sord = $_REQUEST['sord']; //asc or desc		
		!isset($sidx) ? $this->sidx = 1:$this->sidx = $sidx;		
		$this->searchOn = parent::Strip($_REQUEST['_search']);
	}
	
	/**
	 * 列表、查询 入口
	 * @return multitype:NULL multitype:
	 */
	public function actionBootUIManage(){
		//开启搜索列表
		if($this->searchOn=='true') {
			return self::searchBootUIList();
			//正常列表
		}else{
			return self::getBootUIList();
		}
	}
	
	/**
	 * 增加、删除、修改 入口
	 * @return number|Ambigous <\advert\boot\controllers\number, number>|Ambigous <\advert\boot\controllers\添加成功，返回新增id（id>0），0-添加失败, \advert\boot\models\添加成功，返回新增id（id>0），0-添加失败, number, unknown>
	 */
	function actionDoBootUIOper(){
	
		$oper = $_REQUEST['oper'];
			
		if($oper=='edit'){	
			return self::updateBootUI();	
		}elseif ($oper=='del'){	
			return self::deleteBootUI($_REQUEST['id']);	
		}elseif ($oper=='add'){	
			return self::insertBootUI();	
		}
	}
	
	/**
	 * 删除
	 * @param unknown_type $boot_ui_id
	 * @return number
	 */
	private function deleteBootUI($boot_ui_id){ 
		$result = BootUIManageModel::deleteBootUI($boot_ui_id);		
		return $result;
	}
	
	/**
	 * 删除某一类型的全面开机画面
	 * @param unknown_type $type
	 */
	public function actionDeleteBootUIByType($type){ 
		$result = BootUIManageModel::deleteBootUIByType($type);		
		return $result;
	}
	
	/**
	 * 编辑($array)
	 * @param unknown_type $arr
	 * @return
	 */
	private function updateBootUI($formid=NULL){
		$arr['name'] = $_REQUEST['name'];
		$arr['type'] = $_REQUEST['type'];
		$arr['begin_time'] = $_REQUEST['begin_time'];
		$arr['end_time'] = $_REQUEST['end_time'];
		$arr['url'] = $_REQUEST['url'];
		$arr['md5'] = $_REQUEST['md5'];
		$arr['is_deleted'] = $_REQUEST['is_deleted'];
		$arr['is_publish'] = $_REQUEST['is_publish'];
		$arr['boot_ui_id'] = $_REQUEST['id'];
		$result = BootUIManageModel::updateBootUI($arr);
		return $result;
	}
	
	/**
	 * 添加($array)
	 * @param unknown_type $arr
	 * @return 添加成功，返回新增id（id>0），0-添加失败
	 */
	private function insertBootUI($formid=NULL){		
		$arr['name'] = $_REQUEST['name'];
		$arr['type'] = $_REQUEST['type'];
		$arr['begin_time'] = $_REQUEST['begin_time'];
		$arr['end_time'] = $_REQUEST['end_time'];
		$arr['url'] = $_REQUEST['url'];
		$arr['md5'] = $_REQUEST['md5'];
		$arr['is_deleted'] = $_REQUEST['is_deleted'];
		$arr['is_publish'] = $_REQUEST['is_publish'];	
		$result = BootUIManageModel::insertBootUI($arr);
		return $result;
	}
	
	/**
	 * 获取某类型的开机画面信息
	 * @return multitype:
	 */
	public function actionGetAllType(){
		$result = BootUIManageModel::getAllType();
		return $result;
	}
	
	/**
	 * 获取某类型的开机画面信息
	 * @param unknown_type $type
	 * @return multitype:
	 */
	public function actionGetBootUIByType($type){
		$result = BootUIManageModel::getBootUIByType($type);
		return $result;
	}
	
	/**
	 * 获取有效的开机画面, 包括将要推送的和现在正在推送的。
	 * @param unknown_type $type
	 */
	public function actionGetActiveBootUI($type){
		$result = BootUIManageModel::getActiveBootUI();		
		return $result;
	}
	
	/**
	 * 获取开机画面列表
	 * @param unknown_type $start
	 * @param unknown_type $limit
	 * @param unknown_type $orderCondition
	 */
	private function getBootUIList(){
		$pager = new Page(BootUIManageModel::getBootUIListCount()); //cc_id=0,all lists
		//处理分页
		$pager->prePage();
		
		$order = array($this->sidx=>$this->sord);
		
		//获取资源
		$res = BootUIManageModel::getBootUIList($pager->start,$pager->limit,$order);		
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		
		return $arr;
	}
	
	private function searchBootUIList(){
		$searchField = $_REQUEST['searchField'];
		$searchString  = parent::Strip($_REQUEST['searchString']);
			
		$searchArr = array($searchField=>$searchString);
		$pager = new Page(BootUIManageModel::searchBootUIListCount($searchArr));
		//处理分页
		$pager->prePage();
	
		$order = array($this->sidx=>$this->sord);
	
		$res = BootUIManageModel::searchBootUIList(
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
	
	
	/**
	 * 改变开机画面的发布状态
	 * @param unknown_type $boot_ui_id
	 * @param unknown_type $is_publish
	 * @return number:-1 0 >0
	 */
	public function actionUpdateBootUIStatus($boot_ui_id,$oper){
		$is_publish = 0;		
		if($oper == "onsale"){
			$is_publish = 1;				
		}else{
			$is_publish = 0;
		}
		$result = BootUIManageModel::updateBootUIStatus($boot_ui_id,$is_publish);
		return $result;
	}
	
	/**
	 * 上传附件
	 */
	public function actionUploadBootui(){
		header('Content-Type: text/html;charset=utf-8');
	
		//上传
		$_path = $this->bootui;
		$localPath = $this->root.'/'.$_path.'/'; //包解压目录
		
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
					$zipPath=>$this->rs_root.'/'.$_path.'/'.$obj->getName(),
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
						'msg'=>'http://'.RS_HostName.'/'.$this->bootui.'/'.$obj->getName(),
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
	
}