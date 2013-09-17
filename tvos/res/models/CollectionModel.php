<?php

namespace res\models;


class CollectionModel extends \Sky\db\ActiveRecord{
	/**
	 *@return CollectionModel
	 */
		
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	//protected static $tableName="skyg_res.res_collection";
	//protected static $primeKey=array("collection_id");
	protected static $arrType = Array(
			"MEDIA_VIDEO"=>1,
			"MEDIA_AUDIO"=>2,
			"MEDIA_PICTURE"=>3,
			"MEDIA_ONLINE_MOVIE"=>4,
			"MEDIA_ONLINE_MUSIC"=>5,
			"MEDIA_ONLINE_NEWS"=>6,
			"MEDIA_CUSTOM"=>7,
			"MEDIA_TEXT"=>8,
			"MEDIA_ERROR"=>9,
			"MEDIA_ONLINE_PICTURE"=>10,
			"MEDIA_DTV"=>11,
				
			"test"=>9999
	);
	
	
	
	/**添加收藏
	 * 
	 * @param Int $user_id
	 * @param String $jsonConStr like [{"content":"AAA=1","sign":"","type":"MEDIA_ONLINE_MUSIC","url":"http://aac4058670.aac"},
		 		                       {"content":"H5s777","sign":"","type":"MEDIA_ONLINE_MUSIC","url":"http://43f682b5318c4c"},
		 		                       {"content":"tets777","sign":"","type":"MEDIA_ONLINE_MUSIC","url":"http://43f682b5318c4c"}]
	 * @return boolean
	 */
	public static function addMemberCollect($user_id,$jsonConStr){		
		$sql="";				
		$arrContent = json_decode($jsonConStr,true);		
		if(count($arrContent) <=0)
			return false;
					
		foreach($arrContent as $value){	
			$type = "";
			$url = "";
			if(isset($value['type']) && isset($value['url'])){
				$type = $value['type'];
				$url =$value['url'];					
			}else{		
					//echo 111;
				return false;
			}
			if(array_key_exists($type, self::$arrType)){
				$cont = json_encode($value);
				$result =parent::createSQL(
						"SELECT
							COUNT(*)
						FROM
							`skyg_res`.`res_collection`
						WHERE `collection_content` =:collection_content
							 AND `user_id` =:user_id",
						array(
								"collection_content"=>$cont,
								"user_id"=>(int)$user_id
						)
				)->toValue();
				if ($result > 0){
					//echo("double!!");
					continue;
				}
					
				if ($sql == ""){
					$sql = "INSERT INTO `skyg_res`.`res_collection` (
								`user_id`,
								`collection_type`,
								`collection_content`,
								 `collection_url`
							) 
							VALUES
						('$user_id',".self::$arrType[$type].",'".addslashes($cont)."','".addslashes($url)."')";
					}else{
						$sql .= ",('$user_id',".self::$arrType[$type].",'".addslashes($cont)."','".addslashes($url)."')";
					}
				}else{
					//echo("2");
					return false;
				}
			}
		//echo($sql);
		if($sql=="")
			return true;
			
		$result =parent::createSQL($sql)->exec();
		if((int)$result > 0){
			return true;
		}else{
			return false;
		}			
}
		

	
	/**通过url和user_id删除收藏
	 * 
	 * @param Int $user_id
	 * @param String $url
	 * @return >0删除成功 0-删除失败
	 */
	public static function deleteMemberCollect($user_id,$url){	
		$result =parent::createSQL(
				"DELETE 
				FROM
				  `skyg_res`.`res_collection` 
				WHERE user_id = :user_id
				  AND collection_url = :url ",
				array(
						'user_id'=>(int)$user_id,
						'url'=>$url
						)
				)->exec();
		return $result;
	}
	
	/**通过user_id和type来删除收藏
	 * 
	 * @param Int $user_id
	 * @param String $type
	 * @return >0删除成功 0-删除失败
	 */
	public static function deleteMemberCollectAll($user_id,$type){		
		$sql = "DELETE 
				FROM
				  `skyg_res`.`res_collection` 
				WHERE user_id = '$user_id'";
		if($type != ""){
			$sql = $sql." and collection_type=".self::$arrType['$type'];
		}
		return parent::createSQL($sql)->exec();		
	}
	
	/**查询收藏
	 * 
	 * @param Int $user_id
	 * @param String $type
	 * @param Int $page_size
	 * @param Int $page_index
	 * @return string
	 */
	public static function getMemberCollect($user_id,$type,$page_size,$page_index){
		$startIndex = $page_size*$page_index;
		$sql = "SELECT 
				  CASE `collection_type` 
				  WHEN 1 THEN 'MEDIA_VIDEO'
				  WHEN 2 THEN 'MEDIA_AUDIO'
				  WHEN 3 THEN 'MEDIA_PICTURE'
				  WHEN 4 THEN 'MEDIA_ONLINE_MOVIE'
				  WHEN 5 THEN 'MEDIA_ONLINE_MUSIC'
				  WHEN 6 THEN 'MEDIA_ONLINE_NEWS'
				  WHEN 7 THEN 'MEDIA_CUSTOM'
				  WHEN 8 THEN 'MEDIA_TEXT'
				  WHEN 9 THEN 'MEDIA_ERROR'
				  WHEN 10 THEN 'MEDIA_ONLINE_PICTURE'
				  WHEN 11 THEN 'MEDIA_DTV'
				  WHEN 9999 THEN 'test'
				  END AS TYPE,
				  `collection_url` AS url,
				  `collection_content` AS content,
				  `creat_time`
				FROM
				  `skyg_res`.`res_collection` ";
		$strWhere ="where user_id = $user_id";
		if($type != ""){
			$myTypes = explode('|', $type);
			$strType = "";
			for ($i = 0;$i<count($myTypes);$i++){
				if($strType==""){
					$strType = self::$arrType[$myTypes[$i]];
				}else{
					$strType .= ",".self::$arrType[$myTypes[$i]];
				}
			}
			$strWhere .= " and `collection_type` in ($strType)";
		}
		$strOrder = " order by `creat_time` desc limit $startIndex,$page_size";
		$result =parent::createSQL($sql.$strWhere.$strOrder)->toList();
		$total = parent::createSQL("select count(collection_id) from `skyg_res`.`res_collection` ".$strWhere)->toValue();
	
		$list = new \stdClass();
		$list->total = $total;
		$list->result = $result;
	
		return json_encode($list);
	}
	
	/**查询收藏是否存在
	 * 
	 * @param Int $user_id
	 * @param Int $msgId
	 * @return boolean
	 */
	
	public static function hadCollect($user_id,$msgId){	
		$result = parent::createSQL(
				"SELECT 
				  COUNT(collection_id) 
				FROM
				  `skyg_res`.`res_collection` 
				WHERE collection_id = :msgId
				  AND user_id =:user_id",
				array(
						'msgId'=>(int)$msgId,
						'user_id'=>(int)$user_id
						)
				)->toValue();
			
		if((int)$result > 0){
			return true;
		}else{
			return false;
		}
	}
	
	

}