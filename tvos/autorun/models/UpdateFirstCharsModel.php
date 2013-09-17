<?php
namespace tvos\autorun\models;
/**            
 * 
 * @author xiaokeming
 */

class UpdateFirstCharsModel extends \Sky\db\ActiveRecord{
	/**
	 *@return UpdateFirstCharsModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 清空res_first_chars表记录
	 * @return number truncate成功返回值大于0反之等于0
	 */
	public static function truncatetable(){
		return parent::createSQL( "truncate `skyg_res`.`res_first_chars`")->exec();
	}
	
	/**
	 *
	 * 查询video表id,title
	 * @param string $syscondition  策略控制条件
	 */
	public static function queryvideoinfo($syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL( "SELECT
									  rv.`v_id`,
									  rv.`title`
									FROM
									  `skyg_res`.`res_video` AS rv
									WHERE rv.`v_id` > 0
									  AND rv.`expired` = 0".$v_sql)->toList();
	}
	
	/**
	 *
	 * 查询audiosong表id,title
	 * @param string $syscondition  策略控制条件
	 */
	public static function queryaudiosonginfo($syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("SELECT
									  ras.`audio_song_id`,
									  ras.`title`
									FROM
									  `skyg_res`.`res_audio_song` AS ras
									WHERE ras.`audio_song_id` > 0
									  AND ras.`expired` = 0
									  AND ras.`category_id` != 0 ".$v_sql)->toList();
	}
	
	/**
	 *
	 * @param string $v_media_title      来源标题
	 * @param string $v_media_character  首字母
	 * @param int    $v_version          版本号
	 * @return number                    插入成功返回值大于0反之等于0
	 */
	public static function insertfirstchars($v_media_title,$v_media_character,$v_version,$flag,$platform){
		$v_media_title=addslashes($v_media_title);
		$v_media_character=addslashes($v_media_character);
		$v_version=addslashes($v_version);
		return parent::createSQL("INSERT IGNORE INTO `skyg_res`.`res_first_chars` (
									`source_title`,
									`first_chars`,
									`version_id`,
									`flag`,
									`platform`
							        )
									VALUES
									(:v_media_title,
									 :v_media_character,
									 :v_version,
									 :flag,
									 :platform
							        )",
				                array("v_version"=>(int)$v_version,
				                	  "v_media_title"=>$v_media_title,
				                	  "v_media_character"=>$v_media_character,
				                      "flag"=>$flag,
				                      "platform"=>$platform)
		                             )->exec();
	}
	
	/**
	*
	* @param int $v_source_type    资源类型
	* @param string $syscondition  策略控制条件
	*/
	public static function queryrecordprocess($v_source_type,$syscondition){
            if ($syscondition!=''){
			   $v_sql=' and '.$syscondition;
			}else{
			   $v_sql='';
			}
			return parent::createSQL("SELECT
											 rrp.`source_id`
										FROM
											 `skyg_res`.`res_record_process` AS rrp
									   WHERE rrp.`source_type` = :v_source_type ".$v_sql,
					                array("v_source_type"=>(int)$v_source_type))->toValue();
	
	}
	
	/**
	*
	* @param int $v_source_type   资源类型
	* @param int $v_source_id     资源ID
	* @return number              插入成功返回值大于0反之等于0
	*/
		public static function insertrecordprocess($v_source_type,$v_source_id){
			return parent::createSQL("INSERT IGNORE INTO `skyg_res`.`res_record_process` (
					                        `source_type`, 
					                        `source_id`)
										VALUES
										  (:v_source_type, 
					                       :v_source_id)",
					                   array("v_source_type"=>(int)$v_source_type,
					                   		 "v_source_id"=>(int)$v_source_id))->exec();
	}
	
	
	/**
	*
	* @param int $v_source_id      video表主键ID
	* @param string $syscondition  策略控制条件
	*/
	public static function queryvideobyid($v_source_id,$syscondition,$star,$pagesize){
		if ($syscondition!=''){
		    $v_sql=' and '.$syscondition;
		}else{
		    $v_sql='';
		}
		return parent::createSQL("SELECT
										rv.`v_id`,
										rv.`title`
									FROM
										`skyg_res`.`res_video` AS rv
								   WHERE rv.`v_id` > :v_source_id ".$v_sql."
				                   limit :star,:pagesize",
				              array("v_source_id"=>(int)$v_source_id,
				                    "star"=>(int)$star,
									"pagesize"=>(int)$pagesize))->toList();

	}
	
	/**
	 *
	 * @param string $v_firstchars   首字母
	 * @param int    $id             video主键ID
	 * @return number                修改成功返回值大于0反之等于0
	 */
    public static function modifyvideofirstchars($v_firstchars,$id){
		return parent::createSQL("UPDATE `skyg_res`.`res_video`
									 SET `firstchars` = '".addslashes($v_firstchars)."'
								   WHERE `v_id` = :id ",
				                 array("id"=>(int)$id))->exec();
	}
	
	
	/**
	 *
	 * 查询audiosong表id,title
	 * @param string $syscondition  策略控制条件
	 */
	public static function queryaudiosongbyid($v_source_id,$syscondition){
	
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("SELECT
										 ras.`audio_song_id`,
									     ras.`title`
								    FROM `skyg_res`.`res_audio_song` AS ras
								   WHERE ras.`audio_song_id` > :v_source_id".$v_sql,
				              array("v_source_id"=>(int)$v_source_id))->toList();
		
	}
	
	/**
	*
	* @param string $v_firstchars   首字母
    * @param int    $id             audiosong表主键ID
	* @return number                修改成功返回值大于0反之等于0
	*/
	public static function modifysongfirstchars($v_firstchars,$id){
		return parent::createSQL("UPDATE `skyg_res`.`res_audio_song`
									 SET `firstchars` = '".addslashes($v_firstchars)."'
								   WHERE `audio_song_id` = :id ",
				                array("id"=>(int)$id))->exec();
	}
	
	/**
	*
	* @param int $v_max_id        资源ID
	* @param int $v_source_type   资源类型
	* @return number              修改成功返回值大于0反之等于0
	*/
	public static function modifyrecordprocess($v_max_id,$v_source_type){
	   return parent::createSQL("UPDATE `skyg_res`.`res_record_process`
					                SET `source_id` = :v_max_id
						 		  WHERE `source_type` = :v_source_type ",
	   		                  array("v_max_id"=>(int)$v_max_id,
	   		                  		"v_source_type"=>(int)$v_source_type))->exec();
	}
	
	/**
	*
	* 统计source='iqiyi'的videosite记录数
	* @param string $syscondition  策略控制条件
	*/
	public static function queryvideositecount($syscondition){

	    if ($syscondition!=''){
		    $v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("SELECT
										 COUNT(*)
								    FROM
										 skyg_res`.`res_video_site` AS rvs
							       WHERE rvs.`source` = 'iqiyi' ".$v_sql)->toValue();
    }
	
	/**
	*
	* @param string $v_col_name    字段名
	* @param string $syscondition  策略控制条件
	*
	*/
    public static function showvideobycategory($v_col_name,$syscondition){
	
		if ($syscondition!=''){
		    $v_sql=' and '.$syscondition;
	    }else{
	        $v_sql='';
	    }
	    $v_points="`";
	    $v_col_name=$v_points.implode("`,`",$v_col_name).$v_points;
		return parent::createSQL("SELECT
			                             rv.`v_id`,
										 ".$v_col_name."
									FROM
										 `skyg_res`.`res_video` AS rv
								   WHERE rv.`v_id` > 0
									 AND rv.`expired` = 0
									 AND rv.`category` IN ('dm', 'dsj', 'dy')".$v_sql)->toList();
	}
	
	
	/**
	*
	* @param string $v_col_name    字段名
	* @param string $syscondition  策略控制条件
	*
	*/
	public static function showvideonocheckcategory($v_col_name,$syscondition){
	
		if ($syscondition!=''){
				$v_sql=' and '.$syscondition;
		}else{
				$v_sql='';
		}
		$v_points="`";
		$v_col_name=$v_points.implode("`,`",$v_col_name).$v_points;
		return parent::createSQL("SELECT
					                     rv.`v_id`,
										 ".$v_col_name."
									FROM
										 `skyg_res`.`res_video` AS rv
								   WHERE rv.`v_id` > 0
									 AND rv.`expired` = 0".$v_sql)->toList();
	}
	
	
	/**
	 *
	 * @param string $v_col_name    字段名
	 * @param string $syscondition  策略控制条件
	 *
	 */
	public static function showaudiosong($v_col_name,$syscondition){
	
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		$v_points="`";
		$v_col_name=$v_points.implode("`,`",$v_col_name).$v_points;
		return parent::createSQL("SELECT
										 ras.`audio_song_id`,
										 ".$v_col_name."
									FROM
										 `skyg_res`.`res_audio_song` AS ras
								   WHERE ras.`audio_song_id` > 0
					  				 AND ras.`expired` = 0".$v_sql)->toList();
	}
	
	
	
	/**
	 *
	 * @param string $v_col_name    字段名
	 * @param string $syscondition  策略控制条件
	 *
	 */
	public static function showmusictop($syscondition){
		
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		
		return parent::createSQL("SELECT
										 rmt.`music_top_id`,
										 rmt.`title`
									FROM
										 `skyg_res`.`res_music_top` AS rmt
								   WHERE rmt.`music_top_id` > 0
									 and rmt.`expired`=0
									 and rmt.`lrc`!=''
									 and rmt.`page_index`>0".$v_sql)->toList();
	}
	
	/**
	 *
	 * @param string $sql_select_cols    拼接好后的字段字符串
	 * @param string $v_database         数据库名
	 * @param string $v_table            数据表名
	 * @param string $vid                表主键自增ID字段
	 * @param int    $v_source_id        用于比较主键值
	 * @param string $syscondition       策略控制条件
	 *
	 */
	public static function querymusictopinfo($v_source_id,$star,$pagesize){
	
		return parent::createSQL("SELECT rmt.`music_top_id`,
				                         rmt.`title`
									FROM
									     `skyg_res`.`res_music_top` AS rmt
								   WHERE rmt.`music_top_id` > ".$v_source_id."
				                   limit :star,:pagesize",
				               array( "star"=>(int)$star,
				               		  "pagesize"=>(int)$pagesize
				               		  ))->toList();
	}
	
	/**
	 *
	 * @param string $v_firstchar_col  需要更改的字段名
	 * @param string $v_database       数据库名
	 * @param string $v_table          数据表名
	 * @param string $vid              更新的查询条件字段
	 * @param int    $id               用于比较主键值
	 * @param string $v_character      变更后的新字段值
	 * @return number                  修改成功返回值大于0反之等于0
	 */
	public static function modifytableinfo($id,$v_character){
		return parent::createSQL("UPDATE
										 `skyg_res`.`res_music_top`
									 SET `first_chars` = '".$v_character."'
								   WHERE `music_top_id` = ".$id )->exec();
	}
	/**
	 * 
	 * @return Ambigous <NULL, unknown> 返回最大的MUSICTOP ID
	 */
	public static function GetMusicTopMaxId(){
		return parent::createSQL("select max(rmt.`music_top_id`)
				                    from `skyg_res`.`res_music_top` AS rmt")->toValue();
	}
	
	/**
	 *
	 * @return Ambigous <NULL, unknown> 返回最大的VIDEO ID
	 */
	public static function GetVideoMaxId(){
		return parent::createSQL("select max(rv.`v_id`)
				                    from `skyg_res`.`res_video` AS rv")->toValue();
	}
	
	/**
	 * 
	 * @return multitype: 返回上架的app应用
	 */
	public static function GetAvailableAppstore(){
		return parent::createSQL("SELECT 
									  aai.`Product_Name`,
									  aatm.`platform_info` 
									FROM
									  `skyg_appstore`.`appstore_app_type_map` AS aatm
									  LEFT JOIN `skyg_appstore`.`appstore_app_item` AS aai
									    ON aai.`product_id` = aatm.`product_id` 
									WHERE aai.`product_is_available` = 1 ")->toList();
	}
}
