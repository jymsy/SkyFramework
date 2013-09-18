<?php
namespace resource\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/** table skyg_res.res_news                   
 * @property  int          news_id      咨询id                                                               
 * @property  string       title        标题                                                                 
 * @property  string       brief        简介                                                                 
 * @property  int          create_time  创建时间                                                           
 * @property  string       logo         文章缩图                                                           
 * @property  int          outreach_id  外链ID                                                               
 * @property  int          category_id  分类id                                                               
 * @property  int          ispic        是否有图，0：无，1：有                                       
 * @property  string       from         来源                                                                 
 * @property  string       link         外部链接                                                           
 * @property  string       isempty                                                                             
 * @property  int          level        资源等级，0：外网免费，1：外网收费，2：内网免费  
 * @property  int          resmark      资源更新标识    
 * 
 * 
 * `title`,`brief`,`category_id`,`level`
 *detail
 * 
 * @author Zhengyun
 */
class InfoManageModel extends \Sky\db\ActiveRecord{
	/**
	 *@return InfoManageModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	

	//删除
	public static function deleteNews($news_id){
		
		$sql=sprintf("DELETE
				 `skyg_res`.`res_news`,
				 `skyg_res`.`res_news_detail` 
				FROM    
				 	  `skyg_res`.`res_news`	 ,
					  `skyg_res`.`res_news_detail`	   
				WHERE `skyg_res`.`res_news`.`news_id`=`skyg_res`.`res_news_detail`.`news_id`
				      AND `skyg_res`.`res_news`.`news_id` =%d",$news_id);
		return parent::createSQL($sql)->exec();
		
	}

	//编辑($array)
	public static function updateNews($arr){
		extract($arr);
		return parent::createSQL(
			"UPDATE skyg_res.`res_news` 
			 SET
				`category_id`=:category_id,
				`title`=:title,							
				`brief`=:brief,	
				`level`=:level  
			WHERE `news_id` = :news_id",
			array(
				'category_id'=>$category_id,
				'title'=>$title,							
				'brief'=>$brief,
				'level'=>$level,
			    "news_id"=>(int)$news_id					
			)
		)->exec();				
	}

	//news_detail编辑($array)
	public static function updateNewsDetail($detail,$news_id){
		return parent::createSQL(
				"UPDATE skyg_res.`res_news_detail`
			 SET
				`detail`=:detail 
			WHERE `news_id` = :news_id",
				array(
						'detail'=>$detail,
						"news_id"=>(int)$news_id
				)
		)->exec();
	}
	
	
	
	//正常列表统计
	public static function getNewsCount(){
		$result=parent::createSQL(
				"SELECT 
				  COUNT(*) 
				FROM
				  `skyg_res`.`res_news`"
				)->toValue();
		return $result;
		
	}
	//正常列表
	public static function getNewsList($start,$limit,$orderCondition=array('create_time'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);			
		$orderString=str_replace("category_id", "rn`.`category_id", $orderString);
		$sql=sprintf(
				"SELECT 
				  rn.`news_id`,
				  rn.`title`,
				  rn.`brief`,
				  rn.`create_time`,
				  rn.`logo`,
				  rn.`outreach_id`,
				  rn.`category_id`,
				  rn.`ispic`,
				  rn.`from`,
				  rn.`link`,
				  rn.`isempty`,
				  rn.`level`,
				  rn.`resmark`,
				  rc.`category_name`
				FROM
				  `skyg_res`.`res_news` AS rn 
				    LEFT JOIN `skyg_res`.`res_category` AS rc
				    ON rc.`category_id`=rn.`category_id` 				
				ORDER BY %s
				LIMIT %d,%d",$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;		
		
	}      
	
	//搜索列表统计 ()
	public static function searchNewsCount($searchCondition) {
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$searchString=str_replace("category_id", "rn`.`category_id", $searchString);
		
		$sql=sprintf(
				"SELECT 
				  COUNT(*) 
				FROM
				  `skyg_res`.`res_news` AS rn 
				LEFT JOIN `skyg_res`.`res_category` AS rc
				    ON rc.`category_id`=rn.`category_id`  
				%s 	",
				$searchString);
		$result=parent::createSQL($sql)->toValue();
		return $result;
		
	}
	//搜索列表公式 ()
	public static function searchNewsList($searchCondition,$start,$limit,$orderCondition=array('create_time'=>'DESC')) {
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$orderString=PublicModel::controlArray($orderCondition);		
		
		$searchString=str_replace("category_id", "rn`.`category_id", $searchString);
		$orderString=str_replace("category_id", "rn`.`category_id", $orderString);
		
		$sql=sprintf(
				"SELECT 
				  rn.`news_id`,
				  rn.`title`,
				  rn.`brief`,
				  rn.`create_time`,
				  rn.`logo`,
				  rn.`outreach_id`,
				  rn.`category_id`,
				  rn.`ispic`,
				  rn.`from`,
				  rn.`link`,
				  rn.`isempty`,
				  rn.`level`,
				  rn.`resmark`,
				  rc.`category_name`
				FROM
				  `skyg_res`.`res_news` AS rn  
				LEFT JOIN `skyg_res`.`res_category` AS rc
				    ON rc.`category_id`=rn.`category_id`					
				%s
				ORDER BY %s 
				LIMIT %d,%d",
				$searchString,$orderString,$start,$limit
		);
		$result=parent::createSQL($sql)->toList();
		return $result;		
	} 	
	
}