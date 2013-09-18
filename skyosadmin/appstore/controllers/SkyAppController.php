<?php
namespace appstore\controllers;
use Sky\utils\Ftp;
use Sky\Sky;
use Sky\base\Controller;
use appstore\models\SkyAppstoreModel;
use skyosadmin\components\PolicyController;
use skyosadmin\components\ApkInfo;
use Sky\utils\VarDump;
use Sky\web\UploadFile;
use skyosadmin\components\Page;

class SkyAppController extends PolicyController {
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
	 *  在加本类中所有方法前必加载此方法，方法最后必须以return true才生效
	*/
	public function beforeAction($action){
		//url中所有参数加载
		$this->request = parent::getActionParams();
		
		$this->remote_url = "http://".HostName."/Framework/skyosadmin/index.php?";
		$this->apkroot = UPLOADROOT.APKROOT; //结果 = /rs/apk
		$this->iconroot = UPLOADROOT.ICONROOT; //结果 = /rs/icon
		$this->root = ROOT; //结果= /data/cloudservice
		$this->ziproot = UPLOADROOT.ZIPROOT; //结果 = /rs/zip 
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
	public function actionSkyApp(){
		//开启搜索列表
		if($this->request['searchOn']=='true') {
			return $this->searchSkyAppList();
			//正常列表
		}else{
			return $this->SkyAppList();
		}
	}

 

	/*
	 *  正常列表
	 */
	public function skyAppList(){
		//计算总数 
		$pager = new Page(SkyAppstoreModel::getAppNumber());
		//处理分页
		$pager->prePage(); 
		//获取资源
		$res =  SkyAppstoreModel::getAppsDetailAll($pager->start,$pager->limit,$this->order);
		$temp = array();
		foreach($res as $key=>$value){
			unset($value['rep_desc']);
			$temp[]=$value;
		}
			
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$temp,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		); 
		return $arr;
	}


	/*
	 *  搜索列表
	 */
	public function searchSkyAppList(){

	 
		$searchString  = parent::Strip($this->request['searchString']);
			 
		if($this->request['searchField']=="platform_info"){
			$pager = new Page(SkyAppstoreModel::getAppNumberByPlatform($searchString));
			//处理分页
			$pager->prePage();
				
			$res = SkyAppstoreModel::getAppsDetailByPlatform(
			$searchString,
			$pager->start,
			$pager->limit,$this->order);
		}elseif ($this->request['searchField']=="product_type_id"){
			$pager = new Page(SkyAppstoreModel::getAppNumberByType($searchString));
			//处理分页
			$pager->prePage();
				
			$res = SkyAppstoreModel::getAppsDetailByType(
			$searchString,
			$pager->start,
			$pager->limit,$this->order);
		}else{
			//计算总数
			$pager = new Page(SkyAppstoreModel::getSearchNum($this->s));
			//处理分页
			$pager->prePage(); 

			$res = SkyAppstoreModel::searchAppBack(
			$this->s,
			$pager->start,
			$pager->limit,$this->order);
		}
		
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		return  $arr;
	}

	//上下架
	public function actionAppSale($oper,$id){
		if(isset($id)){
			if($oper=='offsale'){
			 return	SkyAppstoreModel::appOffSale($id);
			}elseif ($oper=='onsale'){
			 return	SkyAppstoreModel::appOnSale($id);
			}
		}
		//没有设置id
		return NULL;
	}

	public function actionGetAppCategoryInfo($Product_ID) {
		$results = SkyAppstoreModel::getAppPlatform($Product_ID);
		$lists = array();
		foreach ($results as $value) {
			$obj = new \stdClass();
			$obj->platform_info = $value['platform_info'];
			$obj->product_type_id = $value['product_type_id'];
			$obj->product_type_name = $value['product_type_name'];
			$c = SkyAppstoreModel::getTypeByPlatform($value['platform_info']);
			$obj->catogery = $c;
			array_push($lists, $obj);
		}
		return $lists;
	}

	public function actionGetAppsDetailAll($page,$rows) {
		$records = SkyAppstoreModel::getAppNumber();
		$result = SkyAppstoreModel::getAppsDetailAll($page, $rows,array("product_add_time"=>"DESC"));
		$list = new \stdClass();
		$list->records = $records;
		$list->page = $page;
		$list->total = ceil($records/$rows);
		$list->rows = $result;
		return $list;
	}

 

	/**解析上传的文件包
	 * @param string $filepath apk或者是zip的的路径
	 * @param string $type apk or zip
	 * @return multitype:ApkInfo Ambigous <ApkInfo, NULL>
	 * @test url http://dev.tvos.skysrt.com/Framework/skyosadmin/index.php?_r=skyApp/Test
	 */
	function parseApkPackage($filepath,$type){

			
		$apkinfos=array();
		$apkzip=array();
			
		if(file_exists($filepath)){
			$zip=new \ZipArchive();
			$zipret=$zip->open($filepath);
			if($zipret === TRUE ){
				if($type=="apk"){
					$iconfolder=pathinfo($filepath,PATHINFO_FILENAME);
					if($zip->extractTo($this->root.$this->iconroot."/".$iconfolder)){
						$apkinfo=new ApkInfo();
						$apkinfo->ApkInfo($filepath);
						$apkinfo->info_arr=null;
						$apkinfo->filesize=filesize($filepath);
						$apkinfo->info_str="http://".HostName.$this->apkroot."/".$iconfolder.".apk";
							
						$iconpath=$this->root.$this->iconroot."/".$iconfolder.".".pathinfo($apkinfo->appIcon,PATHINFO_EXTENSION);
							
						copy($this->root.$this->iconroot."/".$iconfolder."/".$apkinfo->appIcon,$iconpath);

						//$apkinfo->appIcon="http://".HostName."/".ICONROOT."/".$iconfolder."/".$apkinfo->appIcon;
						$apkinfo->appIcon="http://".HostName.$this->iconroot."/".$iconfolder.".".pathinfo($apkinfo->appIcon,PATHINFO_EXTENSION);
						$apkinfos[]=$apkinfo;

					}
					// 					return $apkinfos;

				}elseif($type=="zip"){
					// 					return "zip";
					if($zip->extractTo($this->root.$this->ziproot)){
						for($i=0; $i<$zip->numFiles;$i++){
							$apkzip=$zip->statIndex($i);
							// echo ApachePath.ZIPROOT."/".$apkzip['name'];
							$newname = parent::createUniqueName();

							copy($this->root.$this->ziproot."/".$apkzip['name'],$this->root.$this->apkroot."/".$newname.".apk");
							$ret=$this->unZipApk($this->root.$this->apkroot."/".$newname.".apk");
							if($ret){
								$ret->info_arr=null;
								$ret->filesize=filesize($this->root.$this->apkroot."/".$newname.".apk");
								$ret->info_str="http://".HostName."/".$this->apkroot."/".$newname.".apk";
								$iconpath=$this->root.$this->iconroot."/".$newname.".".pathinfo($ret->appIcon,PATHINFO_EXTENSION);
								copy($this->root.$this->iconroot."/".$newname."/".$ret->appIcon,$iconpath);

								$ret->appIcon="http://".HostName.$this->apkroot."/".$newname.".".pathinfo($ret->appIcon,PATHINFO_EXTENSION);
								$apkinfos[]=$ret;
							}

						}

					}

				}
				$zip->close();
			}

		}
		return $apkinfos;

	}

	function actionUpload(){
		
		Sky::$app->getSession()->getId();
		
		
		 
		 
		$POST_MAX_SIZE = ini_get('post_max_size');
		$unit = strtoupper(substr($POST_MAX_SIZE, -1));
		$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

		if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {

			return "POST exceeded maximum allowed size.";

		}
			
		// Settings

		$upload_name = "Filedata";
		$max_file_size_in_bytes = 2147483647;				// 2GB in bytes
		$extension_whitelist = array("apk", "zip");	// Allowed file extensions
		$valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';				// Characters allowed in the file name (in a Regular Expression format)

			
		// Other variables
		$MAX_FILENAME_LENGTH = 260;
		$file_name = "";
		$file_extension = "";
		$uploadErrors = array(
		0=>"There is no error, the file uploaded with success",
		1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
		2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
		3=>"The uploaded file was only partially uploaded",
		4=>"No file was uploaded",
		6=>"Missing a temporary folder"
		);


		// Validate the upload
		if (!isset($_FILES[$upload_name])) {
			return  "No upload found in \$_FILES for " . $upload_name;

		} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
			return  $uploadErrors[$_FILES[$upload_name]["error"]];

		} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
			return  "Upload failed is_uploaded_file test.";

		} else if (!isset($_FILES[$upload_name]['name'])) {
			return  "File has no name.";

		}
		// Validate the file size (Warning: the largest files supported by this code is 2GB)
		$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
		if (!$file_size || $file_size > $max_file_size_in_bytes) {
			return "File exceeds the maximum allowed size";

		}
		if ($file_size <= 0) {
			return "File size outside allowed lower bound";
		}

		$file_name = $_FILES[$upload_name]['name'];

		$path_info = pathinfo($_FILES[$upload_name]['name']);
			
		$file_extension = $path_info["extension"];

		if($file_extension=='apk'){
			$save_path = $this->root.$this->apkroot;				// The path were we will save the file (getcwd() may not be reliable and should be tested in your environment)
		}elseif ($file_extension=='zip'){
			$save_path = $this->root.$this->ziproot;				// The path were we will save the file (getcwd() may not be reliable and should be tested in your environment)
		}

			

		// Validate file extension


		$is_valid_extension = false;
		foreach ($extension_whitelist as $extension) {
			if (strcasecmp($file_extension, $extension) == 0) {
				$is_valid_extension = true;
				break;
			}
		}
		if (!$is_valid_extension) {
			return "Invalid file extension";
		}

		// Process the file
		/*
		 At this point we are ready to process the valid file. This sample code shows how to save the file. Other tasks
		 could be done such as creating an entry in a database or generating a thumbnail.

		 Depending on your server OS and needs you may need to set the Security Permissions on the file after it has
		 been saved.
		 */
			
		$name = parent::createUniqueName();
		$save_path = $save_path.'/'.$name.'.'.$file_extension;
		
		$fileobj  = UploadFile::getInstanceByName($upload_name); 
		$uploaded = $fileobj->saveAs($save_path);
		//$save_path = iconv('UTF-8','gb2312',$save_path.$file_name);
		if (!$uploaded) {
			return "File could not be saved.";
		}else{

			//请注意文件权限
			$result = $this->parseApkPackage($save_path, $file_extension);

			$localapkroot=$this->apkroot."/";
			$localpicroot=$this->iconroot."/";
			$localpicfile=$this->root.$localpicroot;
			$localapkfile=$this->root.$localapkroot;

			$apk = pathinfo($save_path);
			$icons = pathinfo($result[0]->appIcon);

			$flag = time();

			//ftp上传后的地址
			$iconUrl = "http://".RS_HostName.$localpicroot.$flag.$icons['basename'];
			$apkUrl = "http://".RS_HostName.$localapkroot.$flag.$apk['basename'];

			//重新复制对像值
			$result[0]->info_str = $apkUrl;
			$result[0]->appIcon = $iconUrl;

			//ftp上传列表
			$uploadList = array(
			$localpicfile.$icons['basename']=>RS_ROOT.$localpicroot.$flag.$icons['basename'],
			$save_path=>RS_ROOT.$localapkroot.$flag.$apk['basename']
			);

			//ftp上传
			if(parent::uploadFtp($uploadList))
			{
				//删除临时文件
				unlink($localpicfile.$icons['basename']);
				unlink($save_path);
			}
			return $result;
		}

	}





	/**解压apk
	 * @param string $filepath apk路径
	 * @return ApkInfo|NULL
	 */
	function unZipApk($filepath){
		if(file_exists($filepath)){
			$zip=new \ZipArchive();
			if($zip->open($filepath) === TRUE ){
				$iconfolder=pathinfo($filepath,PATHINFO_FILENAME);
				if($zip->extractTo($this->root.$this->iconroot."/".$iconfolder)){
					$apkinfo = new ApkInfo();
					$apkinfo->ApkInfo($filepath);
					$apkinfo->info_arr=null;
					$apkinfo->info_str="";
					$zip->close();
					return $apkinfo;
				}
			}
		}
		return NULL;
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

	/*
	 *  添加方法
	 */
	public function add( $addArr ){

		$ProductBagName = $addArr["packageName"];
		$platformTypes = $addArr['platform'];
		$typeid = $addArr['categorys'];

		$arrVersion = array(
				"product_big_show"=>$addArr["appIcon"],
				"product_small_show"=>$addArr["appIcon"],
				"product_version"=>$addArr['versionName'],
				"product_version_code"=>$addArr['versionCode'],
				"product_size"=>$addArr['filesize'],
				"product_language"=>"zh",
				"download_url"=>$addArr['info_str'],
				"developer"=>$addArr['developer'],
				"vs_note"=>$addArr['vs_note']

		);

		$arrItem = array(
				"ProductOwnerId"=>$addArr["owner"],
				"ProductOwnerName"=>$addArr["owner"],
				"ProductName"=>$addArr["appName"],
				"ProductSaleCCM"=>0,
				"ProductBagName"=>$addArr["packageName"],
				"ProductInstallationSite"=>"0",
				"ProductScore"=>$addArr["product_score"],
				"RepDesc"=>$addArr["rep_desc"],
				"ProductisAvailable"=>1,
				"ProductSalesNum"=>0,
				"productDownloadNum"=>0,
				"controllerType"=>$addArr["control_type"]
		);

		//应用入库
		//通过id,分类入库
		$productId = SkyAppstoreModel::checkAppExistByBagName($ProductBagName);
		if($productId)
		{
			$arr = $arrVersion + array("product_id"=>$productId);
			$a = SkyAppstoreModel::getAppLateVersion($productId);
			$b = $arrVersion["product_version_code"];
			//如果当前版比原有版高不添加应用
			if($a>=$b)
			{
				//表示不能添加比原有版本底的
				return 11;
			}

			$ver_id = SkyAppstoreModel::addAppVersion($arr);
			if($ver_id){
				//返回1
				return SkyAppstoreModel::updateAppVid($productId, $ver_id);
			}else{
				//添加addAppVersion失败
				return 22;
			}

		}else{
			$productId = SkyAppstoreModel::insertAppItem($arrItem);

			if($productId){
					
				$arr = $arrVersion+array("product_id"=>$productId);
					
				$ver_id = SkyAppstoreModel::addAppVersion($arr);
					
				if($ver_id){
					SkyAppstoreModel::updateAppVid($productId, $ver_id);
				}else{
					//添加addAppVersion失败
					return 22;
				}
					
				//多平台，单分类入库
				foreach ($platformTypes as $platformtype){

					SkyAppstoreModel::insertCategoryAppItem($platformtype,$typeid,$productId);
				}
				return 1;
			}else{
				//添加AppItem失败
				return 33;
			}

		}

	}

	/*
	 * 编辑
	 */
	public function edit($editArr){
		$appArr = array(
				"product_id"=>$editArr["id"],
				"product_owner_id"=>$editArr["owner"],
				"product_owner_name"=>$editArr["owner"],
				"product_name"=>$editArr["appName"],
				"product_score"=>$editArr["product_score"],
				"rep_desc"=>$editArr["rep_desc"],
				"controller_type"=>$editArr["control_type"],
				"product_big_show"=>$editArr["appIcon"],
				"product_small_show"=>$editArr["appIcon"],
				"product_version"=>$editArr['versionName'],
				"product_version_code"=>$editArr['versionCode'],
				"product_size"=>$editArr['filesize'],
				"product_language"=>"zh",
				"developer"=>$editArr['developer'],
				"vs_minsdkversion"=>$editArr["minSdkVersion"],
				"vs_note"=>$editArr['vs_note'],
				"platform_types"=>$editArr['platform'],
				"type_id"=>$editArr['categorys']
		);
		//返回1表示成功，返回0表示失败
		return SkyAppstoreModel::updateApp($appArr);
		//删除
	}

	/*
	 * 删除
	 */
	public function del($delArr){
		$id = $delArr['id'];
		$rec = SkyAppstoreModel::deleteApp($id);
		return $rec;	//成功>0，失败0
	}

	/*
	 * 根据id取一条记录
	 */
	public function actionGetAppDetailById($id){ 
		return SkyAppstoreModel::getAppDetailById($id);
	}

	public function actionGetAllPlatformAndCategory() {
		$platforms = SkyAppstoreModel::getAllPlatform();
		$categorys = SkyAppstoreModel::getAllType();
		$list = new \stdClass();
		$list->platforms = $platforms;
		$list->categorys = $categorys;
		return $list;
	}
}