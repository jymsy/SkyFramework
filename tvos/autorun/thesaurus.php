<?php
namespace tvos\autorun;


use tvos\autorun\models\DBFileModel;

use tvos\autorun\models\ThesaurusModel;

use tvos\autorun\models\UpdateFirstCharsModel;


class thesaurus {
	const AlternativeStoreDir = "rs/thesaurus/";
	const OVERWRITE = 1;
	
	
	
	function run() {
		return $this->make_db_file();
		
	}
	
	private function make_db_file(){
		try {
			$thesaurus = self::UpdateCharacters('ver0.0.0');
			$thesaurus = json_decode($thesaurus);
			$max_th_version = $thesaurus->version;
			$filename = date('YmdHi').'.db';
			
			$file = ROOT.'/'.self::AlternativeStoreDir.$filename;
			self::create_db_file_table($file);
			$db=new \SQLite3($file);
			$db->exec("Begin transaction");
			$insertCgStmt = $db->prepare("insert into TABLE_WORDS(firstchars,title,platform) values(?,?,?);");
			foreach ($thesaurus->add as $value) {
				$insertCgStmt->bindValue(1, $value->first_chars,SQLITE3_TEXT);
				$insertCgStmt->bindValue(2, $value->source_title,SQLITE3_TEXT);
				$insertCgStmt->bindValue(3, $value->platform,SQLITE3_TEXT);
				$insertCgStmt->execute();
				//$db->exec("INSERT INTO TABLE_WORDS (firstchars,title) VALUES ('".$value->first_chars."','".$value->source_title."')");
			}
			$insertCgStmt->close();
			$db->exec("END transaction");
			$db->close();
			$file = self::zipDBFile($file,$filename);
			$downloadUrl = str_replace(ROOT, HostName."/", "http://".$file);
			return self::insertdbfile(date('YmdHi'), $downloadUrl);

		} catch (Exception $e) {
			return null;
		}
	}
	
	//flag: 1表示新添词库 ；0表示新新修改词库 ；-1表示新删除词库
	//version_id: 表示修改该记录时的对应版本号
	function UpdateCharacters($version){//ver0.0.0 更新.删除.添加
		try{
			$version = substr($version, 3);
			$ids = explode('.',$version);
			if(isset($ids[0])){
				$update = ThesaurusModel::getInfoByIdAndFlag($ids[0], 0);
				$update_version = ThesaurusModel::getMaxVersionByIdAndFlag($ids[0], 0);
				if($update_version==""){
					$update_version=0;
				}
			}
			if(isset($ids[1])){
				$del = ThesaurusModel::getInfoByIdAndFlag($ids[1], 1);
				$del_version = ThesaurusModel::getMaxVersionByIdAndFlag($ids[1], 1);
				if($del_version==""){
					$del_version=0;
				}
			}
			if(isset($ids[2])){
				$add = ThesaurusModel::getInfoByIdAndFlag($ids[2], 2);
				$add_version = ThesaurusModel::getMaxVersionByIdAndFlag($ids[2], 2);
				if($add_version==""){
					$add_version=0;
				}
			}
			if($add_version == ""&&$del_version==""&&$update_version==""){
				$version = "No new version";
			}else{
				$version = "ver".$update_version.".".$del_version.".".$add_version;
			}

			$list = new \stdClass();
			$list->version = $version;
			$list->add = $add;
			$list->del = $del;
			$list->update = $update;

			return json_encode($list);
		}catch (Exception $e){
			$list = new \stdClass();
			$list->version = "error version";
			$list->add = "";
			$list->del = "";
			$list->update = "";
			return json_encode($list);
		}
	}
	
	private function create_db_file_table($filePath) {
		/*
		 if ($db = new SQLiteDatabase($database)) {
			$q = @$db->query('CREATE TABLE IF NOT EXISTS `theasurus` ( `id` int(11) NOT NULL AUTO_INCREMENT, `source_title` varchar(45) DEFAULT NULL,`first_chars` varchar(45) DEFAULT NULL,`create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)),UNIQUE KEY `index1` (`source_title`,`flag`)');
			}
			$db->close();
			*/
		$db=new \SQLite3($filePath);
		//$db->exec("DROP TABLE IF EXISTS TABLE_WORDS;DROP TABLE IF EXISTS android_metadata;CREATE TABLE TABLE_WORDS (firstchars TEXT, title TEXT PRIMARY KEY,platform TEXT);CREATE TABLE android_metadata (locale TEXT)");
		$db->exec("DROP TABLE IF EXISTS TABLE_WORDS;");
		$db->exec("DROP TABLE IF EXISTS android_metadata;");
		$db->exec("CREATE TABLE TABLE_WORDS (firstchars TEXT, title TEXT,platform TEXT);");
		$db->exec("CREATE TABLE android_metadata (locale TEXT)");
		
		$db->exec("CREATE unique INDEX cg_index ON TABLE_WORDS(title,platform);");
		$db->exec("INSERT INTO android_metadata (locale) VALUES ('zh_CN')");
		$db->close();
	}
	
	private function zipDBFile($file,$filename){
		$zipFile=substr($file,0,-3).".zip";
		if (file_exists($zipFile) && self::OVERWRITE){
			unlink($zipFile);
		}
		$cmd = "cd ".ROOT.'/'.self::AlternativeStoreDir."&& zip $zipFile ".$filename;
		exec($cmd);
		if (file_exists($zipFile)){
			unlink($file);
			return $zipFile;
		}else return null;
	}
	
	function insertdbfile($version,$downloadUrl) {
		try {
			$extend =explode(".", $downloadUrl);
			$va=count($extend)-1;
			if($va>=0&&$extend[$va]=='db'){
				$isZip = 0;
			}else{
				$isZip = 1;
			}
				
			$result = DBFileModel::insertDbFile($version, $downloadUrl, $isZip, '1');
			return $result;
		} catch (Exception $e) {
			return null;
		}
	}
	
	//$type = 1 搜索title $type = 0搜索first_chars
	function search_thesaurus($type,$key,$page_index,$page_size) {
		try{
			return ThesaurusModel::search_thesaurus($type,$key,$page_index,$page_size);
		}catch (Exception $e){
			return json_encode("");
		}
	}
	
	function get_db_file($currentVersion) {
		try {
			return DBFileModel::getDbFile($currentVersion, 'thesaurus');
		} catch (Exception $e) {
			return null;
		}
	}
	
	
	
	
	
	
	
}