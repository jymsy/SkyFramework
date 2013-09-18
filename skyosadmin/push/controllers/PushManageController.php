<?php
namespace push\controllers;

use Sky\Sky;
use Sky\base\Controller;
use Sky\utils\Ftp;
use Sky\web\UploadFile;
use push\models\PushManageModel;
use skyosadmin\components\Page;
use skyosadmin\components\PolicyController;

class PushManageController extends PolicyController {
	
	private $sidx;
	private $sord;
	private $_search;
	private $oper;
	private $searchOn;
	
	private $pushroot = "";
	/*
	 *  网站根目录,php脚本主目录
	 */
	private $root = "";
	/*
	 * 远程ftp 主目录
	 */
	private $rs_root = "";
	
	public function __construct(){
		$this->pushroot = PUSHROOT; //结果 = /rs/bootui		
		$this->root = ROOT; //结果= /data/cloudservice
		$this->rs_root = RS_ROOT; // 结果 = /data/www
		
		$sidx = $_REQUEST['sidx']; //字段名
		$this->sord = $_REQUEST['sord']; //asc or desc		
		!isset($sidx) ? $this->sidx = 1:$this->sidx = $sidx;		
		$this->searchOn = parent::Strip($_REQUEST['_search']);
	}

	
	/**
	 * 查询入口
	 * @param unknown_type $type ： delivery，notice
	 * @return NULL|multitype:NULL multitype:
	 */
	public function actionPushManage(){
		$pushType = $_REQUEST['pushType'];
		//开启搜索列表
		if($this->searchOn=='true') {
			return self::searchPushList("'".$pushType."'");	
			//正常列表
		}else{
			return self::getPushList("'".$pushType."'");
		}
	}
	
	/**
	 * 增加、删除、修改 入口
	 * @param unknown_type $type ： delivery，notice
	 */
	function actionDoPushOper(){
		$pushType = $_REQUEST['pushType'];
		$oper = $_REQUEST['oper'];
		if($oper=='edit'){
			return null;
		}elseif ($oper=='del'){
			return self::deletePushManage($_REQUEST['id']);
		}elseif ($oper=='add'){
			return self::insertPushManage($pushType);
		}
	}
	
	/**
	 * 删除
	 * @param unknown_type $boot_ui_id
	 * @return number
	 */
	private function deletePushManage($msgid){ 
		$result = PushManageModel::deletePushMessage($msgid);		
		return $result;
	}		
		
	/**
	 * 添加
	 * @param unknown_type $type ： delivery，notice
	 * @return unknown
	 */
	private function insertPushManage($type){
		switch ($type){
			case 'delivery':
				$recive_ids = $_REQUEST['radRec_id'];
				if ($recive_ids == 'A'){
					$recive_ids = $_REQUEST['txtRec_id'];
				}				
				$title = $_REQUEST['title'];
				$content = $_REQUEST['content'];
				$res_type = $_REQUEST['res_type'];
				$res_url = $_REQUEST['res_url'];
				$expired_date = $_REQUEST['expired_date'];
				return PushManageModel::pushDeliveryNews(10001, $recive_ids, $title, 
						$content, $res_type, $res_url, 0, $expired_date, 0);
				break;
			case 'notice':
				$msgType = $_REQUEST["msg_type"];
				$title = $_REQUEST["title"];				
				$std = new \stdClass();
				$std->linfo_type = "common";
				$std->linfo_thumbnail = $_REQUEST["thumbnail"];//"http://pic.skysrt.com/img/snake_logo.png";				
				$std->linfo_title = $msgType.':'.$title;					
				$std->linfo_content = $_POST["content"];
				$std->linfo_web_url = "";
				$std->linfo_extend = "";
				$std->linfo_level = "0";
				return PushManageModel::pushMsgAndSave(0, 'B', $type, "[".json_encode($std)."]",0, '');
				break;
			default:
				break;			
		}	
	}
		
	/**
	 * 获取开机画面列表
	 * @param unknown_type $start
	 * @param unknown_type $limit
	 * @param unknown_type $orderCondition
	 */
	private function getPushList($type){
		$pager = new Page(PushManageModel::getPushMessageListCount($type)); 
		//处理分页
		$pager->prePage();
		
		$order = array($this->sidx=>$this->sord);
		
		//获取资源
		$res = PushManageModel::getPushMessageList($type,$pager->start,$pager->limit,$order);
		
		for ($i=0;$i<count($res);$i++){
			$sContent = $res[$i]['pm_content'];
			$sContent = json_decode($sContent);	

			if (!$sContent){
				switch ($type){
					case "'delivery'" :
						$res[$i]['title'] = '';
						$res[$i]['content'] = '';
						$res[$i]['res_type'] = '';
						$res[$i]['res_url'] = '';
						break;
					case "'notice'":
						$res[$i]['title'] = '';
						$res[$i]['thumbnail'] = '';
						$res[$i]['content'] = '';
						break;
					default:break;
				}
				continue;
			}
			switch ($type){			
				case "'delivery'" :
					$res[$i]['title'] = $sContent[0]->dn_res_title;
					$res[$i]['content'] = $sContent[0]->dn_res_content;
					$res[$i]['res_type'] = $sContent[0]->dn_res_type;
					$res[$i]['res_url'] = $sContent[0]->dn_res_url;					
					break;
				case "'notice'":					
					$res[$i]['title'] = $sContent[0]->linfo_title;
					$res[$i]['thumbnail'] = $sContent[0]->linfo_thumbnail;
					$res[$i]['content'] = $sContent[0]->linfo_content;					
					break;
				default:break;
			}
		}
		
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		
		return $arr;
	}	
		
	private function searchPushList($type){
		$searchField = $_REQUEST['searchField'];
		$searchString  = parent::Strip($_REQUEST['searchString']);
			
		$searchArr = array($searchField=>$searchString);
		$pager = new Page(PushManageModel::searchPushMessageListCount($type, $searchArr));
		//处理分页
		$pager->prePage();
	
		$order = array($this->sidx=>$this->sord);
	
		$res = PushManageModel::searchPushMessageList(
				$type, 
				$searchArr,
				$pager->start, 
				$pager->limit,
				$order);		
			
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		return  $arr;
	}
	
	/**
	 * 上传附件
	 */
	public function actionUploadPushManage(){
		header('Content-Type: text/html;charset=utf-8');
	
		//上传
		$_path = $this->pushroot;
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
						'msg'=>'http://'.RS_HostName.'/'.$this->pushroot.'/'.$obj->getName(),
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