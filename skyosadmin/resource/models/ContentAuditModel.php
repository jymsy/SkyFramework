<?php
namespace resource\models;

use skyosadmin\components\PublicModel;

use Sky\db\DBCommand;

/**table  res_log_playurl
 * @property  int          log_playurl_id  自增id                                                       
 * @property  string       playurl         解析后地址                                                
 * @property  string       url             解析前地址                                                
 * @property  string       append          客户端存储数据                                          
 * @property  int          is_expired      资源是否失效（1为失效）                             
 * @property  int          is_delete       管理网站判断是否处理完该信息（1为已处理）  
 * @property  string       createtime      创建时间
 * 
 * @author Zhengyun
 */
class ContentAuditModel extends  \Sky\db\ActiveRecord{
	/**
	 *@return ContentAuditModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	
		
	
	/**获取失效资源的数量
	 * 
	 * @return Ambigous <NULL, unknown>
	 */
	public static function getInvalidSourceCount() {
		$sql = "select count(distinct(`url`)) from `skyg_res`.`res_log_playurl` where (`playurl`='' or `is_expired`=1) AND  `is_expired`  NOT IN (2,3) AND append LIKE '%#SkyVideoPlayer'";
		return parent::createSQL($sql)->toValue();
	}
	
	/**获取失效资源列表
	 * 
	 * @param Int $page
	 * @param Int $pagesize
	 * @param array $orderCondition
	 * @return multitype:
	 */
	public static function getInvalidSource($start, $limit,$orderCondition=array('createtime'=>'DESC') ){
		$orderString=PublicModel::controlArray($orderCondition);		
		$sql = "SELECT 
				  `log_playurl_id`,
				  `playurl`,
				  `url`,
				  `append`,
				  `is_expired`,
				  `is_delete`,
				  `createtime` 
				FROM
				  `skyg_res`.`res_log_playurl` 
				WHERE (`playurl` = '' 
				    OR `is_expired` = 1)
				AND  `is_expired`  NOT IN (2,3) 
				AND append LIKE '%#SkyVideoPlayer' 
				GROUP BY `url` 
				ORDER BY $orderString 
				LIMIT $start, $limit ";
		return parent::createSQL($sql)->toList();
	}
	
	/**获取video来源列表
	 *
	* @param Int $v_id
	* @return multitype:
	*/
	public static function getVideoSite($v_id){
		$sql ="SELECT 
				  rv.`title`,
				  rvs.`source`,
				  rvs.`vs_id`,
				  rvu.`url` 
				FROM
				  `skyg_res`.`res_video` AS rv 
				  JOIN `skyg_res`.`res_video_site` AS rvs 
				    ON rv.v_id = rvs.v_id 
				  JOIN skyg_res.`res_video_url` AS rvu 
				    ON rvs.`vs_id` = rvu.`vs_id` 
				WHERE rvs.`v_id` =$v_id
		        AND rvs.`expired`!=1";
		return parent::createSQL($sql)->toList();
	}
	
	/**通过id查询video信息
	 * 
	 * @param unknown_type $vid
	 * @return multitype:
	 */
	public static function getVideoById($vid) {
		$sql = "select * from `skyg_res`.`res_video` where `v_id`=$vid";
		$result = parent::createSQL($sql)->toList();
		return isset($result[0])?$result[0]:array();
	}
	
	/**通过id获取客户端存储数据
	 * 
	 * @param Int $sid
	 * @return Ambigous <NULL, unknown>
	 */
	public static function getAppend($sid) {
		$sql = "select `append` from `skyg_res`.`res_log_playurl` where append LIKE '%#SkyVideoPlayer' AND `log_playurl_id`=$sid";
		$rs =  parent::createSQL($sql)->toValue();
		return $rs;
	}
	
	/**将video设置为失效
	 * 
	 * @param String $rs
	 * @return number
	 */
	public static function expiredVideo ($rs) {
		$vids = explode('#', $rs);
		if ($vids[0] != '') {
			$sql = "update `skyg_res`.`res_video` set `expired`=1 where `v_id`=".$vids[0];
			$result=parent::createSQL($sql)->exec();
			return $result;
		}
		return 0;
	}
	
	/**将video来源设置为失效
	 *
	* @param String $rs
	* @return number
	*/
	public static function expiredVideoSite ($vs_id,$log_playurl_id) {
		$sql = "update `skyg_res`.`res_video_site` set `expired`=1 where `vs_id`=".$vs_id;
		$result1=parent::createSQL($sql)->exec();
			
		$sql = "UPDATE 
				  skyg_res.`res_log_playurl` 
				SET
				  is_expired = 3 
				WHERE `log_playurl_id`=".$log_playurl_id;
		$result2=parent::createSQL($sql)->exec();
		
		if($result1==0&&$result2==0)
			return 0;
		else
			return 1;		
	}
	
	/**将video设置为忽略
	 *
	* @param String $rs
	* @return number
	*/
	public static function ignoredVideo($log_playurl_id) {
		$sql = "UPDATE 
				  skyg_res.`res_log_playurl` 
				SET
				  is_expired = 2 
				WHERE `log_playurl_id`=".$log_playurl_id;
		$result=parent::createSQL($sql)->exec();
		return $result;
	}
	
	/**删除失效的资源
	 * 
	 * @param Int $sid
	 * @return number
	 */
	public static function deleteInvalidSource($sid) {		
		return parent::createSQL(
				"DELETE 
				  skyg_res.`res_log_playurl` AS a 
				FROM
				  skyg_res.`res_log_playurl` AS a,
				  skyg_res.`res_log_playurl` AS b 
				WHERE a.`url` = b.`url` 
				  AND a.`playurl` = '' 
				  AND a.`log_playurl_id` > 0 
				  AND b.`log_playurl_id` =:sid",
				array('sid'=>$sid)
		)->exec();
	}
	
}