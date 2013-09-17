<?php
namespace tvos\autorun;


use resource\controllers\WebsiteNavigationController;

use tvos\autorun\models\WebSiteNavModel;

Class websitenavigation{
	private $fileName;
	private $dir;
	private $db;
	const DBNamePrefix = "_website.db";//数据库文件后缀 
	//const DelNamePrefix = "_music_del.db";//下架文件后缀 
	const CategoryID = "0010";

	private static $settings = array(
				array("haschilds"=>true,"hasrelation"=>false,"hasfilter"=>false),
				array("haschilds"=>false,"hasrelation"=>false,"hasfilter"=>false));
	/**
	 * 构造函数，生成文件
	 * @param string $type 生成文件类型：false为全量包，true为下架包
	 */
	function __construct($dir,$type=false){
		$version=date("YmdHi");//当前时间
		//组建文件名
		//$this->fileName=$version.($type?self::DelNamePrefix:self::DBNamePrefix);
		$this->fileName = $version.self::DBNamePrefix;
		$this->dir = $dir;
	}
	
	/**
	 * 创建全量包
	 */
	function createDB(){
		$flag = self::getCreatDB_flag();
		if (empty($flag)){
			return '';
		}
		
		$filePath=$this->dir.$this->fileName;
		//文件存在则删除
		if (file_exists($filePath)){
			unlink($filePath);
		}
		var_dump("bengin generate db : file path------".$filePath);
		$this->db=new \SQLite3($filePath);
		//开始事务
		$this->db->exec("Begin transaction");
		//建表
		$this->db->exec("CREATE TABLE android_metadata(locale TEXT);");
		$this->db->exec("CREATE TABLE version(version INTEGER PRIMARY KEY, resource_type TEXT, update_type TEXT);");
		$this->db->exec("CREATE TABLE websites(id INTEGER  PRIMARY KEY,url TEXT unique,name TEXT);");
		
		//$db->exec("CREATE unique INDEX cg_index ON website_navigation(url);");
		//插入版本信息
		$li = explode("_", $this->fileName);
		$this->db->exec("INSERT INTO android_metadata (locale) VALUES ('zh_CN')");
		$this->db->exec("insert into version(version,resource_type,update_type) values($li[0],'websiteNavigation','all');");
		//插入数据
		$insertUrl = $this->db->prepare("insert into websites(url,name) values(?,?);");
		
		
		$results = $this->getWebsiteNavigation();
		foreach ($results as $cat) {
			$insertUrl->bindValue(1, $cat['site_url'],SQLITE3_TEXT);
			$insertUrl->bindValue(2, $cat['site_name'],SQLITE3_TEXT);
			$insertUrl->execute();
		}
		
		
		$insertUrl->close();
		$this->db->exec("END transaction");
		$this->db->close();
		self::alterCreatDB_flag('0');
		var_dump("end generate db....");
		return $this->fileName;
	}
	
	/**
	 * 获取websiteNavigation表
	 */
	private function getWebsiteNavigation(){
		return WebSiteNavModel::getWebsiteNavigation();
	}
	
	private function getCreatDB_flag(){
		return WebSiteNavModel::getCreatDB_flag();
	}
	
	private function alterCreatDB_flag($value){
		return WebSiteNavModel::alterCreatDB_flag($value);
	}
	
	
}

//$run = new Resource_Sqlite_WebsiteNavigation(ROOT."rs/db/resource/");
//$run->createDB();

