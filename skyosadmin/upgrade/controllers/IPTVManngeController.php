<?php 
namespace upgrade\controllers;
use Sky\utils\Ftp;
use Sky\Sky;
use Sky\base\Controller;
use upgrade\models\IPTVManngeModel; 
use skyosadmin\components\PolicyController; 
use skyosadmin\components\ApkInfo;
use skyosadmin\components\ApkPackage;
use skyosadmin\components\Page;
use base\terminal\models\DeviceManageModel;
use Sky\web\UploadFile;
 
class IPTVManngeController extends PolicyController {
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
	 * 在线升级包目录 
	 */
	private $upgraderoot = "";
	
	/*
	 * 远程ftp 主目录 
	 */
	private $rs_root = "";
	
  
    
    private $request = array();
     
	public function beforeAction($action){
	    $this->request = parent::getActionParams(); 
	    $this->apkroot = UPLOADROOT.APKROOT; //结果 = /rs/apk
	    $this->iconroot = UPLOADROOT.ICONROOT; //结果 = /rs/icon
	    $this->root = ROOT; //结果= /data/cloudservice
	    $this->ziproot = UPLOADROOT.ZIPROOT; //结果 = /rs/zip
	    $this->upgraderoot = UPLOADROOT.UPGRADEROOT;  // 结果 = /rs/onlineupgrade
	    $this->rs_root = RS_ROOT; // 结果 = /data/www 
	 
	    $sidx = $request['sidx']; //字段名  
	    !isset($this->request['sidx']) ? $this->request['sidx'] = 1:$this->request['sidx'] = $this->request['sidx'];
	    
	    $this->request['searchOn'] = parent::Strip($this->request['_search']);
	    $this->request['searchAreaOn'] = parent::Strip($this->request['searchArea']);
	    
		return true;
	}
	
	
	
	//这是一个条件执行方法统一入口
	public function actionIPTVMannge(){
		//开启搜索列表
		if($this->request['searchOn']=='true' || $this->request['searchAreaOn']=='true') {
          return $this->searchIPTVManngeList();
		//正常列表
		}else{
		  return $this->IPTVManngeList();
		}
	}
	
	/* 
	 *  正常列表
	 */
	public function IPTVManngeList(){
		
	 
		$pager = new Page(IPTVManngeModel::getIPTVCount()); 
		//处理分页
		$pager->prePage();
		
		$order = array(
				$this->request['sidx']=>$this->request['sord']
		); 
	 
		//获取资源
		$res =  IPTVManngeModel::getIPTVLists($pager->start,$pager->limit,$order);
		
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
	public function searchIPTVManngeList(){
	   
		$searchField = $this->request['searchField'];
		$searchString  = parent::Strip($this->request['searchString']);
		 
		$searchArr = array($searchField=>$searchString);
		$pager = new Page(IPTVManngeModel::searchIPTVCount($searchArr)); 
		//处理分页
		$pager->prePage();
		
		$order = array(
				$this->request['sidx']=>$this->request['sord']
		); 
		$res = IPTVManngeModel::searchIPTV(
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
	
	function actionDoOper($oper){
		 if($oper=='add'){
		 	return $this->add( $this->request );
		 }elseif ($oper=='edit'){
			return $this->edit( $this->request ); 
		}elseif ($oper=='del'){
			return $this->del( $this->request ); 
		}
	}
	
 
   public function add($arr){
   	 $Arr = array(
   	 	 
   	 				'iptv_package_name'=>$arr['iptv_package_name'],
   	 				'iptv_package_icon'=>$arr['iptv_package_icon'],
   	 				'core_style'=>$arr['core_style'],
   	 				'core_chip'=>$arr['core_chip'],
   	 				'area_id'=>$arr['city'],
   	 				'iptv_package_version'=>$arr['iptv_package_version'],
   	 				'download_url'=>$arr['download_url'],
   	 				'md5'=>$arr['md5'],
   	 				'filesize'=>$arr['filesize']
   	 		 
   	 );
   	return  IPTVManngeModel::insertIPTV($Arr);
   }
  
 
 
	public function edit($arr){
		$Arr =array(
    					'iptv_package_name'=>$arr['iptv_package_name'],
    					'iptv_package_icon'=>$arr['iptv_package_icon'],
    					'core_style'=>$arr['core_style'],
    					'core_chip'=>$arr['core_chip'],
    					'area_id'=>$arr['city'],
    					'iptv_package_version'=>$arr['iptv_package_version'],
    					'download_url'=>$arr['download_url'],
    					'md5'=>$arr['md5'],
    					'filesize'=>$arr['filesize'],
    					'iptv_package_id'=>$arr['id']
    			);
		//返回1表示成功，返回0表示失败
		return IPTVManngeModel::updateIPTV($Arr);
		//删除 
	}
	
 
	public function del($arr){
		$id = $arr['id'];
		$rec = IPTVManngeModel::deleteIPTV($id); 
		return $rec;	//成功>0，失败0 
	}
	
   //获取所有省份
	public function actionGetProvince(){
		$rec = IPTVManngeModel::getProvinceList();
		return $rec; 
	}
	
	//通过省份获取市
	public function actionAreaList($parent_id){
		$rec = IPTVManngeModel::getAreaList($parent_id);
		return $rec;	
	}
	
	//获取 机型 
	public function actionGetModelAndChip(){
		$rec = DeviceManageModel::getDeviceModelAndChip();
	
		$data = array();
		foreach($rec as $key=>$value){
			$data['chip'][$value['chip']] =  $value['chip'];
			$data['model'][$value['model']] =  $value['model'];
		}
		
		return $data;
	}
	
	
	
	//本地上传zip升级小包zip
	public function actionUploadZip(){
		header('Content-Type: text/html;charset=utf-8'); 
		$_path = $this->upgraderoot.'/';
		$path = $this->root.$_path;
		
		$obj  = UploadFile::getInstanceByName('zip');
		$fileName = $obj->getName();
		$localPath = $path.$fileName;
		$uploaded = $obj->saveAs($localPath);
		if($uploaded){
			 //apk格式要解析
			 $extName = end(explode('.',$fileName));
			 if($extName=='apk'){
			 	$apkPackage = new ApkPackage();
			 	$apkInfo = $apkPackage->parseApkPackage($localPath, 'apk');
			 	$apkIconInfo = parse_url($apkInfo[0]->appIcon);
			 	//求icon本地路径
			 	$iconlocalPath = $this->root.$apkIconInfo['path'];
			 	//求icon的文件名
			    $iconFileName= pathinfo($iconlocalPath);
			 	$uploadList = array(
			 			$localPath=>$this->rs_root.$this->apkroot."/".$fileName,
			 			$iconlocalPath=>$this->rs_root.$this->iconroot."/".$iconFileName['basename'],
			 	);
			 	//上传后的包名，图标，apk地址
			 	$icon = 'http://'.RS_HostName.$this->iconroot."/".$iconFileName['basename'];
			 	$bag_name = $apkInfo[0]->packageName;
			 	$url = 'http://'.RS_HostName.$this->apkroot."/".$fileName;
			 	//求版本
			 	$module_version = $apkInfo[0]->versionCode;
			 }else{
			 	//非apk上传
			 	$url = 'http://'.RS_HostName.$_path.$fileName;
			 	//icon ,bag_name都 为空
			 	$icon = $bag_name =  $module_version = "";
			 	$uploadList = array(
			 			$localPath=>$this->rs_root.$this->upgraderoot."/".$fileName
			 	);
			 }
			
			
			//ftp上传
			if(parent::uploadFtp($uploadList))
			{
				$arr = array(
						'md5'=>md5_file($localPath),
						'filesize'=>$obj->getSize(),
						'iptv_package_icon'=>$icon,
						'iptv_package_name'=>$bag_name,
						'iptv_package_version'=>$module_version,
						'download_url'=>$url
				);
					//parent::delete_folder($localPath); //执行这一句报错，因为目录里文件rmdir只能删除空目录 
					//exit(json_encode($arr));
					Sky::$app->end(json_encode($arr));
			}
			return 0;
		}
		
		return 0;
	}
	  
}