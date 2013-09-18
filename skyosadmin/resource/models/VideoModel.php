<?php
namespace resource\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/** table skyg_res.res_video
 * @property  int          v_id             自增id                         
 * @property  string       title            名称                           
 * @property  string       actor            演员                           
 * @property  string       director         导演                           
 * @property  string       area             地区                           
 * @property  string       category         分类                           
 * @property  string       category_name    分类名称                     
 * @property  string       publish_company  发布公司                     
 * @property  string       product_company  制造公司                     
 * @property  string       thumb            缩图                           
 * @property  string       tv               综艺播放电视台            
 * @property  string       scriptwriter     编剧                           
 * @property  string       producer         监制                           
 * @property  string       alias            别名                           
 * @property  string       year             年份                           
 * @property  string       release_date     发布日期                     
 * @property  string       classfication    分类                           
 * @property  string       score            评分                           
 * @property  string       praise           顶                              
 * @property  string       step             踩                              
 * @property  string       comment_count    评论数                        
 * @property  string       browse_count     查看数                        
 * @property  string       description      简介                           
 * @property  string       created_date                                      
 * @property  int          expired          是否失效（1为失效）     
 * @property  string       firstchars       首字母                        
 * @property  int          category_id      分类id，对应category表     
 * @property  int          vip              VIP                              
 * @property  int          price            价格                           
 * @property  int          resmark          爬虫数据id                   
 * @property  int          total_segment    总集数                        
 *
 * @author Zhengyun
 */
class VideoModel extends \Sky\db\ActiveRecord{
	/**
	 *@return VideoModel
	 */
public static function model($className=__CLASS__){
		return parent::model($className);
	}	

	protected static $sourceName=array(
			'cntv'=>'央视网',
			'funshion'=>'风行',
			'iqiyi'=>'爱奇艺',
			'letv'=>'乐视',
			'qiyi'=>'奇艺',
			'qq'=>'腾讯',
			'sina'=>'新浪',
			'sohu'=>'搜狐',
			'tudou'=>'土豆',
			'youku'=>'优酷',
			'm1703'=>'电影网'
	);
	
	//删除($array)
	public static function deleteVideo($vid){
		return parent::createSQL(
				"DELETE
				FROM    
				 	  `skyg_res`.`res_video`				
				WHERE `v_id` = :vid ",
				array(
						"vid"=>(int)$vid
				)
		)->exec();
		
	}
	
	//编辑($array)
	public static function updateVideo($arr){
		extract($arr);
		return parent::createSQL(
		"UPDATE skyg_res.`res_video` 
		 SET
			`title`=:title,
			`actor`=:actor,			
			`category`=:category,	
			`category_name`=:category_name,
			`classfication`=:classfication,		
			`total_segment`=:total_segment 
		WHERE `v_id`=:v_id",
			array(
			'title'=>$title,
			'actor'=>$actor,			
			'category'=>$category,
			'category_name'=>$category_name,
			'classfication'=>$classfication,
			'total_segment'=>(int)$total_segment,
			'v_id'=>(int)$v_id					
			)
		)->exec();				
	}

	
	//下架
	public static function videoOffSale($vs_ids){	
		$sql = sprintf("update `skyg_res`.`res_video_site` set `expired`=1 where `vs_id` in (%s)",$vs_ids);
		$result=parent::createSQL($sql)->exec();
		return $result;	
	}
	
	//上架
	public static function videoOnSale($vs_ids){
		$sql = sprintf("update `skyg_res`.`res_video_site` set `expired`=0 where `vs_id` in (%s)",$vs_ids);
		$result=parent::createSQL($sql)->exec();
		return $result;
	}
	
	
	//取一条通过id
	public static function getOnevideobyid($vid){		
		return parent::createSQL(
				"SELECT
					  rv.`v_id`,
					  rv.`title`,
					  rv.`actor`,
					  rv.`category`,
					  rv.`category_name`,
				      rv.`classfication`,
					  rv.`total_segment`
				 FROM
					  `skyg_res`.`res_video` AS rv
				WHERE rv.`v_id` = :vid ",
				array(
						"vid"=>(int)$vid
				)
		)->toList();
			
	}
	
	//正常列表统计
	public static function getVideoCount(){
		$result=parent::createSQL(
				"SELECT 
				  COUNT(DISTINCT rv.v_id) 
				FROM
				  `skyg_res`.`res_video` AS rv 
				JOIN `skyg_res`.`res_video_site` AS rvs 
				    ON rv.`v_id` = rvs.`v_id`"
				)->toValue();
		return $result;
		
	}
	// 正常列表
	public static function getVideoList($start,$limit,$orderCondition=array('v_id'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);		
		$sql=sprintf(
				"SELECT
				  rv.`v_id`,
				  rv.`title`,
				  rv.`actor`,
				  rv.`category`,
				  rv.`category_name`,
				  rv.`classfication`,
				  rv.`total_segment`,
				  BIT_AND(rvs.expired) AS expired,
				  GROUP_CONCAT(rvs.`source`) as `source`
				FROM
				  `skyg_res`.`res_video` AS rv
				JOIN `skyg_res`.`res_video_site` AS rvs
				    ON rv.`v_id` = rvs.`v_id`
				group by rv.`v_id`
				ORDER BY %s
				LIMIT %d,%d",$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;		
		
	}      
	
	//搜索列表统计 ()
	public static function searchVideoCount($searchCondition) {
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$sql=sprintf(
				"SELECT
				  COUNT(DISTINCT rv.v_id)
				FROM
				  `skyg_res`.`res_video` AS rv
				JOIN `skyg_res`.`res_video_site` AS rvs
				    ON rv.`v_id` = rvs.`v_id`
				%s 	",
				$searchString);
		$result=parent::createSQL($sql)->toValue();
		return $result;
		
	}
	//搜索列表公式 ()
	public static function searchVideoList($searchCondition,$start,$limit,$orderCondition=array('v_id'=>'DESC')) {
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$orderString=PublicModel::controlArray($orderCondition);		
		$searchString=str_replace("title", "rv`.`title", $searchString);
		$searchString=str_replace("v_id", "rv`.`v_id", $searchString);
		$sql=sprintf(
				"SELECT
				  rv.`v_id`,
				  rv.`title`,
				  rv.`actor`,
				  rv.`category`,
				  rv.`category_name`,
				  rv.`classfication`,
				  rv.`total_segment`,
				  BIT_AND(rvs.expired) AS expired,
				  GROUP_CONCAT(rvs.`source`) as `source`
				FROM
				  `skyg_res`.`res_video` AS rv
				JOIN `skyg_res`.`res_video_site` AS rvs
				    ON rv.`v_id` = rvs.`v_id`		
				%s
				GROUP BY rv.`v_id`
				ORDER BY %s 
				LIMIT %d,%d",
				$searchString,$orderString,$start,$limit
		);
		$result=parent::createSQL($sql)->toList();
		return $result;		
	} 
	
	//分类显示统计 ()
	public static function getVideoByConditionCount($videoCondition){
		$searchString=PublicModel::controlExactSearch($videoCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$sql=sprintf("SELECT
				  COUNT(DISTINCT rv.v_id)
				FROM
				  `skyg_res`.`res_video` AS rv
				JOIN `skyg_res`.`res_video_site` AS rvs
				    ON rv.`v_id` = rvs.`v_id`
				%s",$searchString);
		$result=parent::createSQL($sql)->toValue();
		return $result;	
	}
	
	// 分类显示列表
	public static function getVideoByConditionList($videoCondition,$start,$limit,$orderCondition=array('v_id'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$searchString=PublicModel::controlExactSearch($videoCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$sql=sprintf(
				"SELECT
				  rv.`v_id`,
				  rv.`title`,
				  rv.`actor`,
				  rv.`category`,
				  rv.`category_name`,
				  rv.`classfication`,
				  rv.`total_segment`,
				  BIT_AND(rvs.expired) AS expired,
				  GROUP_CONCAT(rvs.`source`) as `source`
				FROM
				  `skyg_res`.`res_video` AS rv
				JOIN `skyg_res`.`res_video_site` AS rvs
				    ON rv.`v_id` = rvs.`v_id`
				%s
				group by rv.`v_id`
				ORDER BY %s
				LIMIT %d,%d",$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	
	}
	
	//获取影片来源
	public static function getVideoSiteList($v_id,$start,$limit,$orderCondition=array('vs_id'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);		
		$sql=sprintf(
				"SELECT
				  `vs_id`,
				  `time`,
				  `source`,
				  `expired`
				FROM
				  `skyg_res`.`res_video_site` 
				WHERE v_id=%d
				ORDER BY %s
				LIMIT %d,%d
				",$v_id,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	
	}
	
	//获取影片来源
	public static function getVideoSiteCount($v_id){
		$sql=sprintf(
				"SELECT
				  count(*)
				FROM
				  `skyg_res`.`res_video_site`
				WHERE v_id=%d",$v_id);
		$result=parent::createSQL($sql)->toValue();
		return $result;
	
	}
	
	
	
	/*
	 * 分类值
	*/
	public static  function getCategroy(){
		/*
		 dm        动漫
		dsj       41集电视剧
		dy        电影
		gx        搞笑
		jy        教育
		lyjlp     旅游.纪录片
		ph        片花
		sssh      时尚.生活
		ty        体育
		yl        娱乐
		yy        音乐
		zy        综艺
		*/
		$sql='SELECT				  
				  `category`,				  
				  CASE WHEN `category`="dsj"
				  THEN "电视剧"
				  ELSE `category_name`
				  END AS `category_name`				  
				FROM
				  `skyg_res`.`res_video` 
				GROUP BY `category`';
		$result=parent::createSQL($sql)->toList();
		return $result;		
	}
	
	/* 
	 *  来源值 
	 */
	public static function getSource(){	
		$arr_name="";
		foreach(self::$sourceName as $a=>$b){			
			$arr_name.=sprintf("WHEN `source`='%s' THEN '%s' ",$a,$b);			
		}
		
		if($arr_name=="")
			$arr_name="`source` AS `source_name` ";
		else 
			$arr_name=" CASE ".$arr_name." ELSE `source` 
			  END AS `source_name`";		
		
		$sql='SELECT 
			  `source`,'.$arr_name.' 
			FROM
			  `skyg_res`.`res_video_site` 
			GROUP BY `source` ';
		
		$result=parent::createSQL($sql)->toList();
		
		return $result;
	}
	
	public static function getSourceName(){
		return self::$sourceName;
	}
	
	public static function getSourceList($start,$limit,$orderCondition=array('v_id'=>'DESC'),$source="iqiyi"){
		$orderString=PublicModel::controlArray($orderCondition);		
		$sql=sprintf(
				"SELECT 
				  rv.`v_id`,
				  rv.`title`,
				  rv.`actor`,
				  rv.`category`,
				  rv.`category_name`,
				  rv.`classfication`,
				  rv.`total_segment`,
				  rv.`thumb`,
				  rvs.`source`,
				  rvs.`playurl`,
				  rvu.`url`
				FROM
				  `skyg_res`.`res_video` AS rv 
				JOIN `skyg_res`.`res_video_site` AS rvs 
				    ON rv.`v_id` = rvs.`v_id` 
				JOIN skyg_res.res_video_url AS rvu 
				   ON rvs.vs_id=rvu.`vs_id` 
				WHERE rvs.source = '%s' 
				GROUP BY rvs.`vs_id`
				ORDER BY %s 
				LIMIT %d,%d",$source,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
		
	}
	
	public static function getSourceListCount($source="iqiyi"){
		$sql=sprintf(
				"SELECT 
				  count(*)
				FROM
				  `skyg_res`.`res_video` AS rv 
				JOIN `skyg_res`.`res_video_site` AS rvs 
				    ON rv.`v_id` = rvs.`v_id` 
				JOIN skyg_res.res_video_url AS rvu 
				   ON rvs.vs_id=rvu.`vs_id` 
				WHERE rvs.source = '%s'",$source);
		$result=parent::createSQL($sql)->toValue();
		return $result;
	
	}
	
	public static function searchSourceList($searchCondition,$start,$limit,$orderCondition=array('v_id'=>'DESC'),$source="iqiyi"){
		$orderString=PublicModel::controlArray($orderCondition);
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' AND  '.$searchString;
		$searchString=str_replace("title", "rv`.`title", $searchString);
		$searchString=str_replace("v_id", "rv`.`v_id", $searchString);
		$sql=sprintf(
				"SELECT
				  rv.`v_id`,
				  rv.`title`,
				  rv.`actor`,
				  rv.`category`,
				  rv.`category_name`,
				  rv.`classfication`,
				  rv.`total_segment`,
				  rv.`thumb`,
				  rvs.`source`,
				  rvs.`playurl`,
				  rvu.`url`
				FROM
				  `skyg_res`.`res_video` AS rv
				JOIN `skyg_res`.`res_video_site` AS rvs
				    ON rv.`v_id` = rvs.`v_id`
				JOIN skyg_res.res_video_url AS rvu
				   ON rvs.vs_id=rvu.`vs_id`
				WHERE rvs.source = '%s'
				%s 
				GROUP BY rvs.`vs_id`
				ORDER BY %s
				LIMIT %d,%d",$source,$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	
	}
	
	public static function searchSourceListCount($searchCondition,$source="iqiyi"){
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' AND  '.$searchString;
		$searchString=str_replace("title", "rv`.`title", $searchString);
		$searchString=str_replace("v_id", "rv`.`v_id", $searchString);		
		$sql=sprintf(
				"SELECT
				  count(*)
				FROM
				  `skyg_res`.`res_video` AS rv
				JOIN `skyg_res`.`res_video_site` AS rvs
				    ON rv.`v_id` = rvs.`v_id`
				JOIN skyg_res.res_video_url AS rvu
				   ON rvs.vs_id=rvu.`vs_id`
				WHERE rvs.source = '%s'
				 %s",$source,$searchString);
		$result=parent::createSQL($sql)->toValue();
		return $result;
	
	}
	
	
	
	
}