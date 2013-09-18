<?php
namespace skyosadmin\components;
use skyosadmin\components\ApkInfo;
/*
 *  处理apk包公共类
 */
class ApkPackage{
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
	 * 初始化上传目录配置
	 */
	public function __construct(){
		$this->apkroot = UPLOADROOT.APKROOT; //结果 = /rs/apk
		$this->iconroot = UPLOADROOT.ICONROOT; //结果 = /rs/icon
		$this->root = ROOT; //结果= /data/cloudservice
		$this->ziproot = UPLOADROOT.ZIPROOT; //结果 = /rs/zip
	}

/**
 * 解析上传的文件包
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
						$newname = time(); 
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

}