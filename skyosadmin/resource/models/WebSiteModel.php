<?php
namespace resource\models;

use skyosadmin\components\PublicModel;

use Sky\db\DBCommand;

/**table skyg_res.res_website
 * @property  int          website_id     自增id                 
 * @property  string       site_name      网站名称     
 * @property  string       site_url       网站地址     
 * @property  string       site_logo      网站ICO        
 * @property  string       site_big_logo  网站大图标  
 * @property  string       category_id    网站分类ID  
 * 
 * @author Zhengyun
 */
class WebSiteModel extends \Sky\db\ActiveRecord{
	/**
	 *@return WebSiteModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	
	 
		/*
        *   获取导航列表数据
        */
       public static function getWebSiteList($start,$limit,$category_id='',$orderCondition=array('website_id'=>'DESC')){
               $where = "";
               $orderString=PublicModel::controlArray($orderCondition);
               $orderString=str_replace("category_id", "rw`.`category_id", $orderString);
               if($category_id!=''){
                   $where =" where rca.`category_id`='".addslashes($category_id)."'";
               }
                
                $sqls = "SELECT 
						  rw.`website_id`,
						  rw.`site_name`,
						  rw.`site_url`,
						  rw.`site_logo`,
						  rw.`site_big_logo`,
						  rw.`category_id`,
						  rca.`category_name` 
						FROM
						  `skyg_res`.`res_website` AS rw 
						  LEFT JOIN `skyg_res`.`res_category` AS rca 
						    ON rw.`category_id` = rca.`category_id`    
						 %s 
						ORDER BY %s 
						LIMIT %d, %d"; 
               
                $sqls = sprintf($sqls,$where,$orderString,$start,$limit);
                $temp = parent::createSQL($sqls)->toList(); 
               return $temp;
       }
       
       /*
        *   获取导航列表数目
       */
       public static function getWebSiteCount($category_id=''){
       	$where = "";
       	if($category_id!=''){
       		$where =" where `category_id`='".addslashes($category_id)."'";
       	}
       	$sqlc = "select count(1) as c from `skyg_res`.`res_website` $where";
       	$totalNum =parent::createSQL($sqlc)->toValue();
       	return $totalNum;
       }
       
       /*
        *   搜索导航列表数据
       */
       public static function searchWebSiteList($searchCondition,$start,$limit,$orderCondition=array('website_id'=>'DESC')){
	        $orderString=PublicModel::controlArray($orderCondition);
	       	$searchString=PublicModel::controlsearch($searchCondition);
	       	$orderString=str_replace("category_id", "rw`.`category_id", $orderString);
	       	$searchString=str_replace("category_id", "rw`.`category_id", $searchString);
	       	if($searchString!='')
	       		$searchString=' where  '.$searchString;	       	
	       
	       	$sqls = "SELECT 
					  rw.`website_id`,
					  rw.`site_name`,
					  rw.`site_url`,
					  rw.`site_logo`,
					  rw.`site_big_logo`,
					  rw.`category_id`,
					  rca.`category_name` 
					FROM
					  `skyg_res`.`res_website` AS rw 
					  LEFT JOIN `skyg_res`.`res_category` AS rca 
					    ON rw.`category_id` = rca.`category_id`    
						 %s 
					ORDER BY %s 
					LIMIT %d, %d"; 	       	 
	       	$sqls = sprintf($sqls,$searchString,$orderString,$start,$limit);
	       	$temp = parent::createSQL($sqls)->toList();
	       	return $temp;
       }
        
       /*
        *   搜索导航列表数目
       */
       public static function searchWebSiteCount($searchCondition){
	       $searchString='';
	       $searchString=PublicModel::controlsearch($searchCondition);
		   if($searchString!='')
		       		$searchString=' where  '.$searchString;
	       	$sqlc = "select count(1) as c from `skyg_res`.`res_website` $searchString";
	       	$totalNum =parent::createSQL($sqlc)->toValue();
	       	return $totalNum;
       }
       
       
       /*
        *   获取导航 单条数据 
        */
       public static function getOneWebSiteById($id){
               $id = intval($id);
               $sqls = "select `website_id`,`site_name`,`site_url`,`site_logo`,`site_big_logo`,`category_id` from `skyg_res`.`res_website` where website_id='$id'";  
               $result = parent::createSQL($sqls)->toList();
               return $result[0];
       }
       
       
 
 
       
       /*
        *  res .website 导航入库
        */
       public static function insertWebSite( $arr ){ 
           extract($arr);
           $sql = "INSERT INTO `skyg_res`.`res_website` (
               `site_name`, `site_url`, `site_logo`,`site_big_logo`,`category_id`
               ) VALUES ('%s', '%s', '%s', '%s','%s');";
           $sql = sprintf($sql,addslashes($site_name),
                   addslashes($site_url),addslashes($site_logo),
           		addslashes($site_big_logo),
                   addslashes($category_id));
          $result =  parent::createSQL($sql)->exec();
          return $result;
       }
       
      
       /*
        *  修改导航
        */
       public static function updateWebSite($arr){
           extract($arr);
           $sql = "UPDATE  `skyg_res`.`res_website` SET
                    `site_name` =  '%s',
                    `site_url` =  '%s',
                    `site_logo` =  '%s',
           		    `site_big_logo` =  '%s',
                    `category_id` =  '%s'
                     WHERE  `website_id` =%d";
           $sql = sprintf($sql,addslashes($site_name),
                   addslashes($site_url),addslashes($site_logo),
           		addslashes($site_logo),
                   addslashes($category_id),$website_id);
            $result =  parent::createSQL($sql)->exec();
            return $result;
       }
       
       /*
        *  删除导航
        */
       public static function deleteWebSite( $id ){
           $result=parent::createSQL(
           		"DELETE FROM `skyg_res`.`res_website` WHERE website_id = :id ",
          		 array('id'=>$id)
           		)->exec();
           return $result;
       }    
      
       
	
	
}