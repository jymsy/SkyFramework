<?php
namespace res\models;
/**
 * @property  int          log_playurl_id  自增id                                                       
 * @property  string       playurl         解析后地址                                                
 * @property  string       url             解析前地址                                                
 * @property  string       append          客户端存储数据                                          
 * @property  string       createtime                                                                     
 * @property  int          is_expired      资源是否失效（1为失效）                             
 * @property  int          is_delete       管理网站判断是否处理完该信息（1为已处理）                    
 *
 * @author xiaokeming
 */

class LogPlayUrlModel extends \Sky\db\ActiveRecord{
	/**
	 *@return LogPlayUrlModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_res.res_log_playurl";
	protected static $primeKey=array("log_playurl_id");
	
	/**
	 * 
	 * @param string $realPlayAddress    解析后地址
	 * @param string $url                解析前地址 
	 * @param string $append             客户端存储数据
	 * @param int    $expired            资源是否失效（1为失效）
	 * @return number                    插入成功返回值大于0，返之等于0
	 */
	public static function InsertLogPlayUrl($realPlayAddress,$url,$append,$expired){
	   return parent::createSQL("insert into `skyg_res`.`res_log_playurl`(`playurl`,`url`,`append`,`is_expired`) values
		 			 ('".addslashes($realPlayAddress)."',
	   		          '".addslashes($url)."',
	   		          '".addslashes($append)."',
	   		          ".$expired.")")->exec();
		
		
	}
}