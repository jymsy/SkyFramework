<?php
namespace tvos\autorun;

use tvos\autorun\models\DBFileModel;

use tvos\autorun;


class resourceSqlite {
	const AlternativeStoreDir="wsresource/resource";
	/**
	 * 文件存储路径
	 */
	private static function storeDir(){
		return ROOT.'/'."rs/db/resource/";
	}
	
	function run($type,$isDel=FALSE) {
		$this->generateDB($type,$isDel);
		
	}
	

	/**
	 * 生成文件
	 * @param string $type 生成文件类型：db  del
	 */
	function generateDB($type,$isDel=false){
		$flag_zip = true;
		switch($type){
		case 'media':
			//require_once ROOT_WS.'resource2/resource_sqlite_media.php';
			//$res = new Resource_Sqlite_Media(self::storeDir(),$isDel);
			return NULL;
			break;
		case 'music':
			//require_once ROOT_WS.'resource2/resource_sqlite_music.php';
			//$res = new Resource_Sqlite_Music(self::storeDir(),$isDel);
			return null;
			break;
		case 'websiteNavigation':
			$res = new websitenavigation(self::storeDir(),$isDel);
			$flag_zip = false;
			break;
		}
		$filename = "";
		if($isDel)
			$filename = $res->createDelDB();
		else
			$filename = $res->createDB();
		if ($flag_zip){
			$isZip = '1';
			$zippath = $this->zipDBFile($filename);
			var_dump("zippath==".$zippath);
		}else {
			$isZip = '0';
			$zippath = self::storeDir().$filename;
		}
		
		if($zippath){
			$url = $this->createDownloadUrl($zippath);
			var_dump("url==".$url);
			if($url=="") break;
			$name = explode("_", $filename);
			$this->saveDBInfo($type,$isDel?'del':'all',$name[0],$url,$isZip);
			//推送消息---media、url
			//$com = new Command();
			//$com->commandPush("SKY_MODULE_MEDIA_SERVICE","SKY_DB_REFRESH",$url,$type,$name[0]);
		}
	}
	/**
	 * 压缩文件
	 */
	private function zipDBFile($filename){
		//拼接zip全文件名
		$zipFile=self::storeDir().$filename.".zip";//yyyymmddmm_media.db.zip
		//若文件存在则删除
		if (file_exists($zipFile))
			unlink($zipFile);
		//执行zip命令
		$cmd = "cd ".self::storeDir()." && zip $zipFile ".$filename;
		exec($cmd);
		if (file_exists($zipFile)){
			//删除源文件
			unlink(self::storeDir().$filename);
			return $zipFile;
		}else return null;
	}
	/**
	 * 创建下载url
	 * http://42.121.119.71/webservices/resource/sqlitefile/2013022610_media.db.zip
	 * @param string $file 全路径
	 */
	private function createDownloadUrl($file){
		if (substr(realpath($file),0,strlen(ROOT))==ROOT){
			return "http://".HostName.substr(realpath($file),strlen(ROOT));
		}else {
			$path=ROOT;
			foreach (explode("/", self::AlternativeStoreDir) AS $dir){
				if ($dir=="") break;
				$path.="$dir/";
				if (!file_exists($path)){
					mkdir($path,0644);
				}
			}
			$fileName=self::AlternativeStoreDir.DIRECTORY_SEPARATOR.basename($file);
			if (copy($file, ROOT.$fileName)){
				unlink($file);
				return "http://".HostName."/".$fileName;
			}else return "";
		}
	}
	/**
	 * 存储更新DB文件信息
	 * @param string $res_type 资源类型：media、music、app
	 * @param string $update_type 更新类型：all、del
	 * @param string $version 版本信息
	 * @param string $url 下载链接
	 */
	private function saveDBInfo($res_type,$update_type,$version,$url,$isZip){
		/*
		$sql = sprintf("insert into `statistics`.`dbfile`(res_type,update_type,version,download_url) values('%s','%s','%s','%s');",
			addslashes($res_type),addslashes($update_type),addslashes($version),addslashes($url));
			var_dump($sql);
		return pdo_exec_sql($sql,ResourceDb::conn());
		*/
		return DBFileModel::insertDbFile($version, $url, $isZip, $res_type);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}