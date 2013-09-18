<?php 
namespace upgrade\controllers;
use Sky\utils\Ftp;
use Sky\Sky;
use Sky\base\Controller;
use upgrade\models\UpgradeModel; 
use skyosadmin\components\PolicyController; 
use skyosadmin\components\ApkInfo;
use skyosadmin\components\ApkPackage;
use skyosadmin\components\Page;
use Sky\web\UploadFile;
 
class UpgradeController extends PolicyController {
 
	private $sidx;
	private $sord; 
	private $_search;
	private $oper; 
    private $searchOn;
    private $area_code;
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
	
 
	
	public function __construct(){
		$this->apkroot = UPLOADROOT.APKROOT; //结果 = /rs/apk
		$this->iconroot = UPLOADROOT.ICONROOT; //结果 = /rs/icon
		$this->root = ROOT; //结果= /data/cloudservice
		$this->ziproot = UPLOADROOT.ZIPROOT; //结果 = /rs/zip
		$this->upgraderoot = UPLOADROOT.UPGRADEROOT;  // 结果 = /rs/onlineupgrade
		$this->rs_root = RS_ROOT; // 结果 = /data/www
 
		// get index row - i.e. user click to sort
		// at first time sortname parameter - after that the index from colModel
		$sidx = $_REQUEST['sidx']; //字段名 
		// sorting order - at first time sortorder
		$this->sord = $_REQUEST['sord']; //asc or desc
		  
		!isset($sidx) ? $this->sidx = 1:$this->sidx = $sidx; 
		//取区域码
		$this->area_code = $_REQUEST['area_code'];
		$this->searchOn = parent::Strip($_REQUEST['_search']);
	}
	
	//这是一个条件执行方法统一入口
	public function actionUpgrade(){
		//开启搜索列表
		if($this->searchOn=='true') {
          return $this->searchUpgradePackageList();
		//正常列表
		}else{
		  return $this->upgradePackageList();
		}
	}
	
 
   //☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆
   //☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆
   //☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆远程代码段begin☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆
   //☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆
   //☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆
   //☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆
	 
	 /*
	  *  用于远程获取数据列表
	  */
	public function actionRemoteUpgrade(){
		//开启搜索列表
		if($this->searchOn=='true') {
			return $this->searchRemoteUpgradePackageList();
			//正常列表
		}else{
			return $this->remoteupgradePackageList();
		}
	}
	
	/*
	 *  远程获取搜索列表
	 */
	public function searchRemoteUpgradePackageList(){
		$searchField = $_REQUEST['searchField'];
		$searchString  = parent::Strip($_REQUEST['searchString']); 
		 
		$searchArr = array($searchField=>$searchString);
		
		$pager = new Page(UpgradeModel::searchSysUpgradeCountByArea($this->area_code,$searchArr));
		//处理分页
		$pager->prePage();
		 
		 
		$order = array($this->sidx=>$this->sord);
		
		$res = UpgradeModel::searchSysUpgradeByArea(
				$this->area_code, 
				$pager->start,
				$pager->limit,$searchArr,$order);
		 
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$this->arrayAddValueToJson($res),
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		return  $arr;
	}
	/*
	 * 远程获取 正常列表
	 */
	public function remoteupgradePackageList(){
	 
		$pager = new Page(UpgradeModel::getSysUpgradeCountByArea($this->area_code));
		//处理分页
		$pager->prePage();
		
		$order = array($this->sidx=>$this->sord);
		
		//获取资源
		$res =  UpgradeModel::getSysUpgradeByArea($this->area_code,$pager->start,$pager->limit,$order);
		 
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$this->arrayAddValueToJson($res),
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
			
			
		return $arr;
	}
	
	//☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆
	//☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆
	//☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆远程代码段end☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆
	//☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆
	//☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆
	//☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆
	
	
	
	/*
	 *  正常列表
	 */
	public function upgradePackageList(){
		
		$pager = new Page(UpgradeModel::getSysUpgradeCount()); 
		//处理分页
		$pager->prePage();
		
		$order = array($this->sidx=>$this->sord); 
	 
		//获取资源
		$res =  UpgradeModel::getSysUpgrade($pager->start,$pager->limit,$order);
		
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$this->arrayAddValueToJson($res),
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
	public function searchUpgradePackageList(){
	   
		$searchField = $_REQUEST['searchField'];
		$searchString  = parent::Strip($_REQUEST['searchString']);
		 
		$searchArr = array($searchField=>$searchString);
		$pager = new Page(UpgradeModel::searchUpgradeCount($searchArr)); 
		//处理分页
		$pager->prePage();
		
		$order = array($this->sidx=>$this->sord);
		
		$res = UpgradeModel::searchUpgradeInfo(
				$searchArr,
				$pager->start,
				$pager->limit,$order);
		 
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$this->arrayAddValueToJson($res),
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
		 
		if($oper=='edit'){
			
			return $this->edit( $_REQUEST );
			
		}elseif ($oper=='del'){
			
			return $this->del( $_REQUEST );
			
		}
		
	}
	
	/*
	 *  @子包列表添加、删除，修改方法
	*/
	
	function actionDoModuleOper(){
		$oper = $_REQUEST['oper'];
		 if($oper=='add'){
		 	return $this->moduleAdd( $_REQUEST );
		 }elseif ($oper=='edit'){
			return $this->moduleEdit( $_REQUEST ); 
		}elseif ($oper=='del'){
			return $this->moduleDel( $_REQUEST ); 
		}
	
	}
	
	/*
	 * 添加小包
	 */
	public function moduleAdd($addArr){
		$Arr = array(
    					'upgrade_id'=>$addArr['upgrade_id'],
    					'module_name'=>$addArr['module_name'],
    					'module_type'=>$addArr['module_type'],
    					'module_version'=>$addArr['module_version'],
    					'download_url'=>$addArr['download_url'],
    					'is_enforce'=>$addArr['is_enforce'],
    					'md5'=>$addArr['md5'],
				        'mac_start'=>$addArr['mac_start'],
				        'mac_end'=>$addArr['mac_end'],
				        'desc'=>$addArr['desc'],
				        'filesize'=>$addArr['filesize'],
				        'icon'=>$addArr['icon'],
				        'bag_name'=>$addArr['bag_name']
    				);
		return UpgradeModel::insertModuleUpgrade($Arr);
	}

	/*
	 * 编辑小包
	*/
	public function moduleEdit($editArr){

		$Arr = array(
				'upgrade_id'=>$editArr['upgrade_id'],
				'module_name'=>$editArr['module_name'],
				'module_type'=>$editArr['module_type'],
				'module_version'=>$editArr['module_version'],
				'download_url'=>$editArr['download_url'],
				'is_enforce'=>$editArr['is_enforce'],
		        'mac_start'=>$editArr['mac_start'],
		        'mac_end'=>$editArr['mac_end'],
				'md5'=>$editArr['md5'],
				'filesize'=>$editArr['filesize'],
				'desc'=>$editArr['desc'],
				'icon'=>$editArr['icon'],
				'bag_name'=>$editArr['bag_name'],
				'upgrade_module_id'=>$editArr['id'] //module自增长id
		); 
		return UpgradeModel::updateModuleUpgrade($Arr);
	}

	/*
	 * 删除小包
	*/
	public function moduleDel($addArr){
		$id = $_REQUEST['id'];
		return UpgradeModel::deleteModuleUpgrade($id);
	}
	 
	/*
	 * 大包编辑
	 */
	public function edit($editArr){
	 
		
		$Arr = array(
    					'mac_start'=>$editArr['mac_start'],
    					'mac_end'=>$editArr['mac_end'],
    					'upgrade_id'=>$editArr['id'],
				        'core_style'=>$editArr['core_style'],
				        'core_chip'=>$editArr['core_chip'],
				        'init_version'=>$editArr['init_version'],
				        'final_version'=>$editArr['final_version'],
				        'area'=>$editArr['area'],
				        'platform'=>$editArr['platform'],
				        'bag_type'=>$editArr['bag_type'],
				        'screen_size'=>$editArr['screen_size'],
				        'thirdparty_info'=>$editArr['thirdparty_info'],
				        'desc'=>$editArr['desc'],
				        'core_style'=>$editArr['core_style']
    					);
		//返回1表示成功，返回0表示失败
		return UpgradeModel::updateSysUpgrade($Arr);
		//删除 
	}
	
	/*
	 * 大包删除
	 */
	public function del($delArr){
		$id = $delArr['id'];
		$rec = UpgradeModel::deleteSysUpgrade($id); 
		return $rec;	//成功>0，失败0 
	}
	
	
	
	/*
	 * 根据id取一条记录（大包信息）
	 */
	public function actionGetSysUpgradeById(){
	    $id = $_REQUEST['id'];
		return UpgradeModel::getSysUpgradeById($id);
	}
	 
	/*
	 *  子模块列表
	 */
	public function actionModuleList(){
		//开启搜索列表
		if($this->searchOn=='true') {
			return $this->searchSysUpgradeByIdList();
			//正常列表
		}else{
			return $this->getSysUpgradeByIdList();
		}
	}
	
	/*
	 *  通过某父id来获取子列表（小包信息）
	*/
	public function getSysUpgradeByIdList(){
		$id = $_REQUEST['upgrade_id'];
		
		$pager = new Page(UpgradeModel::getModuleUpgradeCount($id)); 
		//处理分页
		$pager->prePage();
		
		$order = array($this->sidx=>$this->sord);
		
		//获取资源
		$res =  UpgradeModel::getModuleUpgradeLists($id,$pager->start,$pager->limit,$order); 
			
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
			
			
		return $arr;
		
	}
	
	/*
	 *   子包列表搜索接口
	 */
	public function searchSysUpgradeByIdList(){
		$id = $_REQUEST['upgrade_id'];
		$searchField = $_REQUEST['searchField'];
		$searchString  = parent::Strip($_REQUEST['searchString']);
			
		$searchArr = array($searchField=>$searchString);
		
		$pager = new Page(UpgradeModel::searchModuleUpgradeCount($id,$searchArr));
		 
	   //处理分页
		$pager->prePage();
			
		$order = array($this->sidx=>$this->sord);
		
		$res = UpgradeModel::searchModuleUpgrade(
				$id,
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
	 *  说明页包上传
	 */
	public function actionUploadPage(){
		header('Content-Type: text/html;charset=utf-8');
		$id = intval($_REQUEST['id']);
		$zipPath = ''; //说明页包的位置 
		//上传
	    $_path = $this->upgraderoot.'/';
	    $subDir = date('Y-m').'/'.$id.'/';
		$localPath = $this->root.$_path.$subDir; //包解压目录
		 
		//创建目录 
		if(!is_dir($localPath)){
			parent::RecursiveMkdir($localPath,0777);
		}
		
		$obj  = UploadFile::getInstanceByName('zip');
		$zipPath = $localPath.$obj->getName();
		$uploaded = $obj->saveAs($zipPath);
		if($uploaded){
			//解压到path
			$zip = new \ZipArchive;
			if ($zip->open($zipPath) === TRUE) {
				$zip->extractTo($localPath);
				$zip->close();
				unlink($zipPath);
			} else {
				$arr =  array(
						'msg'=>'Zip extract failed!',
						'status'=>0
				);
				
				exit(json_encode($arr));
				
			}
			//FTP上传path目录
			$uploadList = array(
					$localPath=>$this->rs_root.$this->upgraderoot.'/'.$id.'/',
			);
			
			try {
				$ftped = parent::uploadFtp($uploadList);
			} catch (Exception $e) {
				$arr =  array(
						'msg'=>$e->getMessage(),
						'status'=>0
				);
				exit(json_encode($arr));
			}
			//ftp上传
			if($ftped)
			{
				//parent::delete_folder($localPath); //执行这一句报错，因为目录里文件rmdir只能删除空目录 
				//返回成功上传的url说明包页地址 
				$arr = array(
						'msg'=>'http://'.RS_HostName.'/'.$this->upgraderoot.'/'.$id.'/',
						'status'=>1
				);
				exit(json_encode($arr));
				
			}
		}
		 $arr = array(
						'msg'=>'上传失败',
						'status'=>0
				);
		
		exit(json_encode($arr));
	}
	
	/*
	 *  更新说明页包地址 
	 */
	public function actionUpdateIncludePageUrl(){
		$id = $_REQUEST['id'];
		$introduce_page = $_REQUEST['url'];
	    return UpgradeModel::updateSysUpgradeIntroduce($id, $introduce_page);
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
						'icon'=>$icon,
						'bag_name'=>$bag_name,
						'module_version'=>$module_version,
						'url'=>$url
				);
					//parent::delete_folder($localPath); //执行这一句报错，因为目录里文件rmdir只能删除空目录 
					exit(json_encode($arr));
			}
			return 0;
		}
		
		return 0;
	}
	
 
	public function  actionTest(){
		//ftp上传
		$ftp = Sky::$app->ftp;
		$ftp->mkrdir("/data/www/rs/onlineupgrade/a/b/c");
		/*
		echo time();
		$uploadList = array(
				"/data/cloudservice/rs/onlineupgrade/11110010_V013.005.090.zip"=>"/data/www/rs/zip/test/12.zip"
		);
		parent::uploadFtp($uploadList);
		echo '<br />';
		echo time(); 
		*/
	}
	
 
 
	 
}