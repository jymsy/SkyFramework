<?php
namespace res\models;
/**
 * @property  int          album_id      自增ID              
 * @property  string       title         专辑名称          
 * @property  string       url           专辑地址          
 * @property  string       thumb         缩图                
 * @property  string       singer        歌手名称          
 * @property  string       company       发行公司          
 * @property  string       publishDate   发行日期          
 * @property  string       subtype       专辑类型          
 * @property  int          songsNum      包括多少歌曲    
 * @property  int          collectNum    收藏次数          
 * @property  string       type          专辑分类          
 * @property  string       description   简介                
 * @property  string       created_date                        
 * @property  int          resmark       对应爬虫数据id           
 * 
 * @author xiaokeming
 */

class AlbumModel extends \Sky\db\ActiveRecord{
	/**
	 *@return AlbumModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName='skyg_res.res_audio_album';
	protected static $primeKey=array('album_id');
	
	/**
	 * 
	 * @param string $v_singer      歌手名称
	 * @param string $syscondition  策略控制条件
	 */
	public static function listsourcescount($v_singer,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
					  COUNT(*)
					FROM
					  `skyg_res`.`res_audio_album` AS raa
					WHERE raa.`singer` = :v_singer ".$v_sql,
				array(
						"v_singer"=>$v_singer
				)
		)->toValue();
	}
	
	/**
	 * 
	 * @param string $v_singer      歌手名称
	 * @param string $syscondition  策略控制条件
	 * @param int    $page
	 * @param int    $pagesize
	 */
	public static function listsources($v_singer,$page,$pagesize,$syscondition){
		$start = $page*$pagesize;
	    if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		
		return parent::createSQL(
				"SELECT
					  raa.`album_id` AS id,
					  raa.`title`,
					  raa.`url`,
					  raa.`thumb`,
					  raa.`singer`,
					  raa.`company`,
					  raa.`publishDate`,
					  raa.`subtype`,
					  raa.`songsNum`,
					  raa.`collectNum`,
					  raa.`type`,
					  raa.`description` AS `desc`,
					  raa.`created_date`,
					  raa.`resmark`
					FROM
					  `skyg_res`.`res_audio_album` AS raa
					WHERE raa.`singer` = :v_singer".$v_sql."
					ORDER BY raa.`publishDate` DESC,
					  raa.`collectNum` DESC
					LIMIT :start, :pagesize ",
				array(
						'v_singer'=>$v_singer,
						'start'=>(int)$start,
						'pagesize'=>(int)$pagesize
				)
		)->toList();
	}
}
