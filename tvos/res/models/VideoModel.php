<?php
namespace res\models;
/**
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
 * @author xiaokeming
 */

class VideoModel extends \Sky\db\ActiveRecord{
	/**
	 *@return VideoModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_res.res_video";
	protected static $primeKey=array("v_id");

	/**
	 * 
	 * @param int $id  影视ID
	 * @param string $syscondition  策略控制条件
	 */
	public static function loadsite($id,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
			
		return parent::createSQL(
				"select
					  rvs.`vs_id` AS id,
				      rvs.`resolution`,
					  rvs.`source` AS `from`,
					  rvs.`time` AS timelong,
					  rvs.`definition` AS edition,
					  rvs.`segment`,
					  rvs.`current_segment` as `maxSegment`,
					  rvs.`playurl`,
					  rvu.`startPlayTime`,
					  rvu.`endPlayTime`,
					  rvu.`url`,
				      rvs.`play_action`,
				      rvs.`price`
				 from
					  `skyg_res`.`res_video_site` as rvs
					  left join `skyg_res`.`res_video_url` as rvu
					    on rvu.`vs_id` = rvs.`vs_id`
					    and (rvu.`collection` = 1
					      or rvu.`collection` = 0)
				        ".$v_sql."
					where rvs.`v_id` = :id",
				array(
						"id"=>(int)$id
				)
		)->toList();
			
	}
    
	/**
	 * 
	 * @param int $vid  影视ID
	 */
	public static function queryvideobyid($vid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT 
					  rv.`v_id` AS id,
					  rv.`title`,
					  rv.`actor`,
					  rv.`director`,
					  rv.`area`,
					  rv.`category`,
					  rv.`thumb`,
					  rv.`tv`,
					  rv.`year`,
					  rv.`classfication` AS subtype,
					  rv.`description`,
				      rv.`is_new` 
				 FROM
					  `skyg_res`.`res_video` AS rv
				WHERE rv.`v_id` = :vid ".$v_sql,
				array(
						"vid"=>(int)$vid
				)
		)->toList();
		 
	}
	
	/**
	 * 
	 * @param string $v_title  名称
	 * @param string $syscondition  策略控制条件
	 */
	public static function queryvideobytitle($v_title,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
					  rv.`v_id` as id
				 FROM
					  `skyg_res`.`res_video` AS rv
				WHERE rv.`title` = :v_title ".$v_sql,
				array(
						"v_title"=>$v_title
				)
		)->toList();
			
	}
    
	/**
	 * 
	 * @param int $vid  影视ID
	 */
	public static function queryvideocomment($vid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT 
					  rvc.`title`,
					  rvc.`content`,
					  rvc.`date` 
				 FROM
					  `skyg_res`.`res_video_comment` AS rvc
				 WHERE rvc.`v_id` = :vid ".$v_sql."
					   ORDER BY rvc.`date` DESC ",
				 array(
						"vid"=>(int)$vid
				)
		)->toList();
		 
	}
	
	/**
	 *
	 * @param int $vid  影视ID
	 */
	public static function queryplaybill($vid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT 
				       rpb.`picture_url` 
				 FROM
				       `skyg_res`.`res_playbill` AS rpb
				 WHERE rpb.`relation_id` = :vid 
				   AND rpb.`type` = 'sokuvideo' ".$v_sql,
				array(
						"vid"=>(int)$vid
				)
		)->toList();
	
	}
	
	/**
	 * 
	 * @param int $vid      video表ID
	 * @param string $syscondition  策略控制条件
	 * @return multitype:
	 */
	public static function queryvideosite($vid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select rvs.`vs_id` AS id,
										 rvs.`source` AS site,
										 rv.`thumb`,
				                         rvs.`play_action`,
				                         rvs.`price`,
				                         rv.`is_new`  
								    from `skyg_res`.`res_video_site` as rvs 
									left join `skyg_res`.`res_video` as rv 
										 on rv.`v_id`=rvs.`v_id`
				                         ".$v_sql."
		                           where rvs.`v_id`=:vid",
		                           array(
		                           		 "vid"=>$vid))->toList();
	}
	/**
	 * 
	 * @param int $vid      videosite表ID
	 * @param int $page
	 * @param int $pagesize
	 */
	public static function queryvideourl($vsid,$syscondition,$page,$pagesize){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		
		$start = $page*$pagesize;
		return parent::createSQL(
				"SELECT
					  rvu.`vu_id` AS id,
					  rvu.`vs_id`,
					  rvu.`title`,
					  rvu.`url`,
					  rvu.`list_title`,
					  rvu.`collection` AS `index`,
					  rvu.`created_date`,
					  rvu.`resmark`,
					  rvu.`startPlayTime`,
					  rvu.`endPlayTime`
				from `skyg_res`.`res_video_url` AS rvu
			   where rvu.`vs_id`=:vsid ".$v_sql."
				order by rvu.`collection` limit :start,:pagesize",
				array(
						"vsid"=>(int)$vsid,
						"start"=>(int)$start,
						"pagesize"=>(int)$pagesize
				)
		)->toList();
		 
	}
	
	/**
	 * 
	 * @param array $v_source  来源
	 * @param int $page
	 * @param int $pagesize
	 * @param string $syscondition  策略控制条件
	 * AND rvs.`source` IN (:v_source)已经用策略控制
	 */
	public static function queryvideolist($syscondition,$page,$pagesize){
		
		$start = $page*$pagesize;
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
					  rv.`v_id` AS id,
					  rv.`title`,
					  rv.`actor`,
					  rv.`director`,
					  rv.`area`,
					  rv.`category`,
					  rv.`category_name`,
					  rv.`publish_company`,
					  rv.`product_company`,
					  rv.`thumb`,
					  rv.`tv`,
					  rv.`scriptwriter` AS bianju,
					  rv.`producer` AS jianzhi,
					  rv.`alias` AS second_title,
					  rv.`year`,
					  rv.`release_date`,
					  rv.`classfication` AS subtype,
					  rv.`score`,
					  rv.`praise` AS ding,
					  rv.`step` AS cai,
					  rv.`comment_count`,
					  rv.`browse_count` AS view_count,
					  rv.`description`,
					  rv.`created_date`,
					  rv.`expired`,
					  rv.`firstchars`,
					  rv.`category_id`,
					  rv.`vip` AS `level`,
					  rv.`price`,
					  rv.`resmark`,
					  rv.`total_segment` AS `endSegment`,
				      rv.`is_new`,
				      rvs.`play_action`,
				      rvs.`price`
				 FROM
					  `skyg_res`.`res_video_site` AS rvs,
				      `skyg_res`.`res_video` AS rv
				WHERE rv.`v_id` = rvs.`v_id`
				  AND rv.`expired` = 0
				  AND rv.`category` IN ('dy', 'dsj')
                  ".$v_sql."
				  ORDER BY rv.`v_id` DESC
				  LIMIT :start, :pagesize ",
			    array(
						
						"start"=>(int)$start,
						"pagesize"=>(int)$pagesize
				)
		)->toList();
		 
	}
	
	/**
	 * 
	 * @param string $v_type 推荐类型标识
	 * @param string $syscondition  策略控制条件
	 */
	public static function querytopcount($v_type,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
					   COUNT(1)
				   FROM
					   `skyg_res`.`res_top` AS rt,
				       `skyg_res`.`res_video` AS rv
				  WHERE rv.`v_id` = rt.`source_id`
				    AND rv.`expired` = 0
				    AND rt.`recommend_type` = :v_type".$v_sql,
				array(
						"v_type"=>$v_type
				)
		)->toValue();
		 
	}
	
	/**
	 * @param string $v_type  推荐类型标识
	 * @param int $page
	 * @param int $pagesize
	 * @param string $syscondition  策略控制条件
	 */
	public static function querytopvideolist($v_type,$syscondition,$page,$pagesize){                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
	         
	        $start = $page*$pagesize;
	        if ($syscondition!=''){
	        	$v_sql=' and '.$syscondition;
	        }else{
	        	$v_sql='';
	        }
		    return parent::createSQL(                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
	                " SELECT                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
						  rv.`v_id` AS id,
						  rv.`title`,
						  rv.`actor`,
						  rv.`director`,
						  rv.`area`,
						  rv.`category`,
						  rv.`category_name`,
						  rv.`publish_company`,
						  rv.`product_company`,
						  rv.`thumb`,
						  rv.`tv`,
						  rv.`scriptwriter` AS bianju,
						  rv.`producer` AS jianzhi,
						  rv.`alias` AS second_title,
						  rv.`year`,
						  rv.`release_date`,
						  rv.`classfication` AS subtype,
						  rv.`score`,
						  rv.`praise` AS ding,
						  rv.`step` AS cai,
						  rv.`comment_count`,
						  rv.`browse_count` AS view_count,
						  rv.`description`,
						  rv.`created_date`,
						  rv.`expired`,
						  rv.`firstchars`,
						  rv.`category_id`,
						  rv.`vip` AS `level`,
						  rv.`price`,
						  rv.`resmark`,
						  rv.`total_segment` AS `endSegment`,
		    		      rv.`is_new` 						                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
						   FROM                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
						        `skyg_res`.`res_top` as rt                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
						   LEFT JOIN `skyg_res`.`res_video` as rv                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
						        on rv.`v_id` = rt.`source_id`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
						    and rv.`expired` = 0                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
						  where rt.`recommend_type` = :v_type  
		    		      ".$v_sql."                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
						  order by rt.`sequence` desc,rv.`release_date` desc                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
						  limit :start, :pagesize ",                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
			        array(                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
			                "v_type"=>$v_type,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
			                "start"=>(int)$start,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
			                "pagesize"=>(int)$pagesize                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
	        )                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
	        )->toList();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
			                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
	}     
    
	
	
	

	
	/**
	 * 
	 * @param array  $v_source    来源
	 * @param array  $condition   筛选的条件
	 * @param int    $page        
	 * @param int    $pagesize      
	 * @param string $Union       是AND/OR
	 * AND s.`source` IN (:v_source)已经做成策略控制
	 * @param string $syscondition  策略控制条件
	 * 
	 * 
	 */
	public static function querysitecount($syscondition,$condition,$page,$pagesize,$Union=1){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		
		$con = '';
		$start = $page*$pagesize;
		$attach = $Union==1?"and":"or";
		foreach ($condition as $k=>$v) {
			$k = strtolower($k);
			if ($k == 'sys_sort'){
				continue;
			}
			if ($v != '') {
				if ($con != '') $con .= " ".$attach." " ;
				if (in_array($k, array('title','subtype','actor'))) {
					if ($k=='subtype'){
						$k='classfication';
					}
					$con .= "rv.`$k` like '%".addslashes($v)."%'";
					continue;
				}
				if ($k == 'category') {
					//if (isset($Media[$v])) {
						$con .= "rv.`category`='".addslashes($v)."'";
					//}
					continue;
				}
				if ($k == 'area') {
					$con .= "rv.`$k` like '%".addslashes($v)."%'";
					continue;
				}
				// 进行非匹配
				if(substr($k, $start,1) == '_'){
					$nk = substr($k, 1);
					$con .= "rv.`$nk`<>$v";
					continue;
				}
				// 绕开特殊处理
				if(substr($k, $start,1) == '*'){
					$k = substr($k, 1);
				}
				if (is_numeric($v)) $con .= "rv.`$k`=$v";
				else $con .= "rv.`$k`='".addslashes($v)."'";
			}
		}
	    if ($con!=''){
	    	$con = " and ".$con;
	    }
		return parent::createSQL(
				"SELECT
				      COUNT(distinct rv.`v_id`)
				 FROM
				     `skyg_res`.`res_video_site` AS rvs,
				     `skyg_res`.`res_video` AS rv
				WHERE rv.`v_id` = rvs.`v_id`
				  ".$v_sql."
				  AND rv.`expired` = 0".$con
				
		)->toValue();
			
	}
	
    /**
     * 
     * @param array  $v_source
     * @param string $v_sql
     * @param array  $v_orderby
     * @param int    $page
     * @param int    $pagesize
     * rvs.`source` IN (:v_source)已经做成策略控制
     * @param string $syscondition  策略控制条件
     * 
     */
	
	public static function queryvideodetail($syscondition,$condition,$v_orderby,$page,$pagesize,$Union=1){
		
		$start=$page*$pagesize;
		
		if ($syscondition!=''){
			$v_policy=' and '.$syscondition;
		}else{
			$v_policy='';
		}

		if($v_orderby!=""){
			$v_points="`";
			$v_index=" DESC ";
			$v_orderby=$v_points.implode("` DESC,`",$v_orderby).$v_points.$v_index;
		}else{
			$v_orderby="`level` desc,`rindex` desc,rv.`release_date` desc,rvs.`run_time` desc";
		}
		
		
		
		
		$attach = $Union==1?"and":"or";
		$v_sql = '';
		foreach ($condition as $k=>$v) {
			$k = strtolower($k);
			if ($k == 'sys_sort'){
				continue;
			}
			if ($v != '') {
				if ($v_sql != '') $v_sql .= " ".$attach." " ;
				if (in_array($k, array('title','subtype','actor'))) {
					if ($k=='subtype'){
						$k='classfication';
					}
					$v_sql .= "rv.`$k` like '%".addslashes($v)."%'";
					continue;
				}
				if ($k == 'category') {
					//if (isset($Media[$v])) {
						$v_sql .= "rv.`category`='".addslashes($v)."'";
					//}
					continue;
				}
				if ($k == 'area') {
					$v_sql .= "rv.`$k` like '%".addslashes($v)."%'";
					continue;
				}
				// 进行非匹配
				if(substr($k, $start,1) == '_'){
					$nk = substr($k, 1);
					$v_sql .= "rv.`$nk`<>$v";
					continue;
				}
				// 绕开特殊处理
				if(substr($k, $start,1) == '*'){
					$k = substr($k, 1);
				}
				if (is_numeric($v)) $v_sql .= "rv.`$k`=$v";
				else $v_sql .= "rv.`$k`='".addslashes($v)."'";
			}
		}
		
		if($v_sql!=''){
			$v_sql = ' and '.$v_sql;
		}
		
		return parent::createSQL(
				"SELECT
					  rv.`v_id` AS id,
					  rv.`title`,
					  rv.`actor`,
					  rv.`director`,
					  rv.`area`,
					  rv.`category`,
					  rv.`category_name`,
					  rv.`publish_company`,
					  rv.`product_company`,
					  rv.`thumb`,
					  rv.`tv`,
					  rv.`scriptwriter` AS bianju,
					  rv.`producer` AS jianzhi,
					  rv.`alias` AS second_title,
					  rv.`year`,
					  rv.`release_date`,
					  rv.`classfication` AS subtype,
					  rv.`score`,
					  rv.`praise` AS ding,
					  rv.`step` AS cai,
					  rv.`comment_count`,
					  rv.`browse_count` AS view_count,
					  rv.`description`,
					  rv.`created_date`,
					  rv.`expired`,
					  rv.`firstchars`,
					  rv.`category_id`,
					  rv.`vip` AS `level`,
					  rv.`price`,
					  rv.`resmark`,
					  rv.`total_segment` AS `endSegment`,
				      rv.`is_new`,
					  IFNULL(r.`sequence`, 0) AS `rindex`,
				      rvs.`play_action`,
				      rvs.`price`
				FROM
				      `skyg_res`.`res_video_site` AS rvs
				  left join `skyg_res`.`res_video` AS rv
				       on rv.`v_id` = rvs.`v_id`
				  left join ( select * from `skyg_res`.`res_top` AS rt 
				                where rt.`source_type`=1 
				                 group by rt.`source_id`) r
				       on r.`source_id` = rv.`v_id`
				WHERE  rv.`expired` = 0".$v_sql.$v_policy."
				  GROUP BY rvs.`v_id`
				  ORDER BY ".$v_orderby."
	              limit :start,:pagesize",
				array(
						"start"=>(int)$start,
						"pagesize"=>(int)$pagesize
				)
		)->toList();
	
	}
	
	/**
	 * 
	 * @param int $v_start LIMIT的起始值
	 * @param string $syscondition  策略控制条件
	 * 
	 */
	public static function queryvideobylatest($v_start,$syscondition){
		$v_end=(int)$v_start + 1;
		if ($syscondition!=''){
			$v_policy=' and '.$syscondition;
		}else{
			$v_policy='';
		}
		
		return parent::createSQL(
				"SELECT
					  rv.`v_id` AS id,
					  rv.`title`,
					  rv.`actor`,
					  rv.`director`,
					  rv.`area`,
					  rv.`category`,
					  rv.`category_name`,
					  rv.`publish_company`,
					  rv.`product_company`,
					  rv.`thumb`,
					  rv.`tv`,
					  rv.`scriptwriter` AS bianju,
					  rv.`producer` AS jianzhi,
					  rv.`alias` AS second_title,
					  rv.`year`,
					  rv.`release_date`,
					  rv.`classfication` AS subtype,
					  rv.`score`,
					  rv.`praise` AS ding,
					  rv.`step` AS cai,
					  rv.`comment_count`,
					  rv.`browse_count` AS view_count,
					  rv.`description`,
					  rv.`created_date`,
					  rv.`expired`,
					  rv.`firstchars`,
					  rv.`category_id`,
					  rv.`vip` AS `level`,
					  rv.`price`,
					  rv.`resmark`,
					  rv.`total_segment`,
				      rv.`is_new`
					FROM
					  `skyg_res`.`res_top` AS rt,
					  `skyg_res`.`res_video` AS rv
					WHERE rt.`source_id` = rv.`v_id`
					  AND rt.`recommend_type` = 'latest'
				      ".$v_policy."
					ORDER BY rt.`sequence`,rt.`created_date` DESC
					LIMIT :v_start,:v_end",
				array(
						"v_start"=>(int)$v_start,
						"v_end"=>(int)$v_end
				)
		)->toList();
	}
	
	/**
	 * 
	 * @param int $sid  影视表主键ID
	 * @param string $syscondition  策略控制条件
	 * 
	 */
	public static function showvideoforrelation($sid,$syscondition){
		if ($syscondition!=''){
			$v_policy=' and '.$syscondition;
		}else{
			$v_policy='';
		}
		return parent::createSQL(
				"SELECT
					  rv.`v_id` AS id,
					  rv.`title`,
					  rv.`actor`,
					  rv.`director`,
					  rv.`area`,
					  rv.`category`,
					  rv.`category_name`,
					  rv.`publish_company`,
					  rv.`product_company`,
					  rv.`thumb`,
					  rv.`tv`,
					  rv.`scriptwriter` AS bianju,
					  rv.`producer` AS jianzhi,
					  rv.`alias` AS second_title,
					  rv.`year`,
					  rv.`release_date`,
					  rv.`classfication` AS subtype,
					  rv.`score`,
					  rv.`praise` AS ding,
					  rv.`step` AS cai,
					  rv.`comment_count`,
					  rv.`browse_count` AS view_count,
					  rv.`description`,
					  rv.`created_date`,
					  rv.`expired`,
					  rv.`firstchars`,
					  rv.`category_id`,
					  rv.`vip` AS `level`,
					  rv.`price`,
					  rv.`resmark`,
					  rv.`total_segment`,
				      rv.`is_new`
					FROM
					  `skyg_res`.`res_video` AS rv
					WHERE rv.`category` =
					  (SELECT
					    `category`
					  FROM
					    `skyg_res`.`res_video`
					  WHERE `v_id` = :sid)
					  AND rv.`v_id` IN
					  (SELECT
					    `relation_id`
					  FROM
					    `skyg_res`.`res_cross_relation`
					  WHERE `target_id` = :sid
					    AND `target_id` != `relation_id`)".$v_policy,
				array(
						"sid"=>(int)$sid
				)
		)->toList();
	}
	
	/**
	 * 
	 * @param int $sid              video表主键ID
	 * @param string $syscondition  策略控制条件
	 * @return multitype:
	 */
	public static function showvideofortop($sid,$syscondition){
		if ($syscondition!=''){
			$v_policy=' and '.$syscondition;
		}else{
			$v_policy='';
		}
		return parent::createSQL(
				"SELECT
					  rv.`v_id` AS id,
					  rv.`title`,
					  rv.`actor`,
					  rv.`director`,
					  rv.`area`,
					  rv.`category`,
					  rv.`category_name`,
					  rv.`publish_company`,
					  rv.`product_company`,
					  rv.`thumb`,
					  rv.`tv`,
					  rv.`scriptwriter` AS bianju,
					  rv.`producer` AS jianzhi,
					  rv.`alias` AS second_title,
					  rv.`year`,
					  rv.`release_date`,
					  rv.`classfication` AS subtype,
					  rv.`score`,
					  rv.`praise` AS ding,
					  rv.`step` AS cai,
					  rv.`comment_count`,
					  rv.`browse_count` AS view_count,
					  rv.`description`,
					  rv.`created_date`,
					  rv.`expired`,
					  rv.`firstchars`,
					  rv.`category_id`,
					  rv.`vip` AS `level`,
					  rv.`price`,
					  rv.`resmark`,
					  rv.`total_segment`,
				      rv.`is_new`
					FROM
					  `skyg_res`.`res_video` AS rv
					WHERE rv.`expired` = 0
					  AND rv.`category` =
					  (SELECT
					    `category`
					  FROM
					    `skyg_res`.`res_video`
					  WHERE `v_id` = :sid)
					  AND EXISTS
					  (SELECT
					    1
					  FROM
					    `skyg_res`.`res_top` AS top
					  WHERE rv.`v_id` = top.`source_id`)
					  AND rv.`v_id` != :sid ".$v_policy,
				array(
						"sid"=>(int)$sid
				)
		)->toList();
	}
	
    /**
     * 
     * @param int $sid    VIDEO表主键 ID
     * @return multitype:
     */
	public static function showvideolistbyid($sid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		
		return parent::createSQL(
				"SELECT
					  rv.`v_id` AS id,
					  rv.`title`,
					  rv.`actor`,
					  rv.`director`,
					  rv.`area`,
					  rv.`category`,
					  rv.`category_name`,
					  rv.`publish_company`,
					  rv.`product_company`,
					  rv.`thumb`,
					  rv.`tv`,
					  rv.`scriptwriter` AS bianju,
					  rv.`producer` AS jianzhi,
					  rv.`alias` AS second_title,
					  rv.`year`,
					  rv.`release_date`,
					  rv.`classfication` AS subtype,
					  rv.`score`,
					  rv.`praise` AS ding,
					  rv.`step` AS cai,
					  rv.`comment_count`,
					  rv.`browse_count` AS view_count,
					  rv.`description`,
					  rv.`created_date`,
					  rv.`expired`,
					  rv.`firstchars`,
					  rv.`category_id`,
					  rv.`vip` AS `level`,
					  rv.`price`,
					  rv.`resmark`,
					  rv.`total_segment` AS `endSegment`,
				      rv.`is_new`
					FROM
					  `skyg_res`.`res_video` AS rv
					WHERE rv.`v_id` = :sid".$v_sql,
				array(
						"sid"=>(int)$sid
				)
		)->toList();
	}
	
	/**
	 *
	 * @return multitype:   返回VIDEO表ID倒序的前10行
	 * @param string $syscondition  策略控制条件
	 */
	public static function listtopvideo($syscondition,$start=0,$pagesize=10){
		if ($syscondition!=''){
			$v_policy=' and '.$syscondition;
		}else{
			$v_policy='';
		}
		return parent::createSQL(" select rv.`v_id` AS id,
											  rv.`title`,
											  rv.`actor`,
											  rv.`director`,
											  rv.`area`,
											  rv.`category`,
											  rv.`category_name`,
											  rv.`publish_company`,
											  rv.`product_company`,
											  rv.`thumb`,
											  rv.`tv`,
											  rv.`scriptwriter` AS bianju,
											  rv.`producer` AS jianzhi,
											  rv.`alias` AS second_title,
											  rv.`year`,
											  rv.`release_date`,
											  rv.`classfication` AS subtype,
											  rv.`score`,
											  rv.`praise` AS ding,
											  rv.`step` AS cai,
											  rv.`comment_count`,
											  rv.`browse_count` AS view_count,
											  rv.`description`,
											  rv.`created_date`,
											  rv.`expired`,
											  rv.`firstchars`,
											  rv.`category_id`,
											  rv.`vip` AS `level`,
											  rv.`price`,
											  rv.`resmark`,
											  rv.`total_segment` AS `endSegment`,
				                              rv.`is_new`
	        		                     from `skyg_res`.`res_video` AS rv
	        		                    where rv.`expired`=0
				                         ".$v_policy."
	        		                    order by rv.`v_id` desc
	        		                    limit :start,:pagesize",
				                   array("start"=>(int)$start,
				                   		 "pagesize"=>(int)$pagesize
				                   		))->toList();
	}
	
	/**
	 *
	 * @param string $key       推荐类型标识
	 * @param int    $page
	 * @param int    $pagesize
	 * @param string $syscondition  策略控制条件
	 * @return multitype:
	 */
	public static function listtopvideobykey($key,$syscondition,$page,$pagesize){
		if ($syscondition!=''){
			$v_policy=' and '.$syscondition;
		}else{
			$v_policy='';
		}
		$start = $page*$pagesize;
		return parent::createSQL(" select rv.`v_id` AS id,
										  rv.`title`,
										  rv.`actor`,
										  rv.`director`,
										  rv.`area`,
										  rv.`category`,
										  rv.`category_name`,
										  rv.`publish_company`,
										  rv.`product_company`,
										  rv.`thumb`,
										  rv.`tv`,
										  rv.`scriptwriter` AS bianju,
										  rv.`producer` AS jianzhi,
										  rv.`alias` AS second_title,
										  rv.`year`,
										  rv.`release_date`,
										  rv.`classfication` AS subtype,
										  rv.`score`,
										  rv.`praise` AS ding,
										  rv.`step` AS cai,
										  rv.`comment_count`,
										  rv.`browse_count` AS view_count,
										  rv.`description`,
										  rv.`created_date`,
										  rv.`expired`,
										  rv.`firstchars`,
										  rv.`category_id`,
										  rv.`vip` AS `level`,
										  rv.`price`,
										  rv.`resmark`,
										  rv.`total_segment` AS `endSegment`,
				                          rv.`is_new`
				                    from `skyg_res`.`res_top` as rt
				                    LEFT JOIN `skyg_res`.`res_video` as rv
				                      on rt.`source_id` = rv.`v_id`
				                      ".$v_policy."
	                               where rt.`source_type` = 1
				                     and rt.`recommend_type` = :v_key
				                   ORDER BY rt.`sequence` desc
				                   limit :start,:pagesize",
				array(  "v_key"=>$key,
						"start"=>(int)$start,
						"pagesize"=>(int)$pagesize))->toList();
	
	}
	
	/**
	 *
	 * @param int $sourcetype 推荐资源类型
	 * @param string $syscondition  策略控制条件
	 * @return multitype:
	 */
	public static function topkeys($sourcetype,$syscondition){
		if ($syscondition!=''){
			$v_policy=' and '.$syscondition;
		}else{
			$v_policy='';
		}
		return parent::createSQL("SELECT rt.`recommend_type` AS `key`,
									     rt.`recommend_name` AS `value`
									FROM `skyg_res`.`res_top` AS rt
								   WHERE rt.`source_type`=:v_source_type
				                    ".$v_policy."
								   GROUP BY rt.`recommend_type`",
				array("v_source_type"=>(int)$sourcetype)
		)->toList();
	}
	
	/**
	 *
	 * @param int $pagesize
	 * @param string $syscondition  策略控制条件
	 * @return multitype:
	 */
	public static function listcasuals($pagesize,$syscondition){
		if ($syscondition!=''){
			$v_policy=' where '.$syscondition;
		}else{
			$v_policy='';
		}
		return parent::createSQL(" select rv.`v_id` AS id,
											  rv.`title`,
											  rv.`actor`,
											  rv.`director`,
											  rv.`area`,
											  rv.`category`,
											  rv.`category_name`,
											  rv.`publish_company`,
											  rv.`product_company`,
											  rv.`thumb`,
											  rv.`tv`,
											  rv.`scriptwriter` AS bianju,
											  rv.`producer` AS jianzhi,
											  rv.`alias` AS second_title,
											  rv.`year`,
											  rv.`release_date`,
											  rv.`classfication` AS subtype,
											  rv.`score`,
											  rv.`praise` AS ding,
											  rv.`step` AS cai,
											  rv.`comment_count`,
											  rv.`browse_count` AS view_count,
											  rv.`description`,
											  rv.`created_date`,
											  rv.`expired`,
											  rv.`firstchars`,
											  rv.`category_id`,
											  rv.`vip` AS `level`,
											  rv.`price`,
											  rv.`resmark`,
											  rv.`total_segment` AS `endSegment`,
				                              rv.`is_new`
	        		                     from `skyg_res`.`res_video` AS rv
				                          ".$v_policy."
	        		                    ORDER BY RAND() LIMIT :pagesize",
				array("pagesize"=>(int)$pagesize))->toList();
	}
	
	/**
	 * 
	 * @param string $v_name           影视名
	 * @param string $syscondition     策略控制条件
	 * 此接口用于云平台做探索ID使用
	 */
	public static function queryvidbytitle($v_name,$syscondition=''){
		if ($syscondition!=''){
			$v_policy=' and '.$syscondition;
		}else{
			$v_policy='';
		}
		return parent::createSQL("select rv.`v_id`
						     from `skyg_res`.`res_video` AS rv
						     where rv.`title`='".addslashes($v_name)."'".$v_policy."
						     order by rv.`created_date` desc
						     limit 1")->toValue();
		
	
	}
	
	/**
	 * 
	 * @param array $v_categoryid           数组分类ID
	 * @param unknown_type $syscondition    策略条件
	 * @return multitype:                   返回分类ID,资源ID,缩图（查询的是旧库，新库上线后此接口作废）
	 */
	public static function getVideotThumByCid_Old(array $v_categoryid ,$syscondition){
		
        $v_categoryid=implode("','", $v_categoryid);
		
		if($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
			
		return parent::createSQL("SELECT 
									  h.categoryid,
									  h.id,
									  h.thumb  
									FROM
									  (SELECT 
									    b.categoryid,
									    a.id,
									    a.thumb 
									  FROM
									    tvos.`video` AS a,
									    (SELECT 
									      (
									        CASE
									          WHEN categoryname = '电影' 
									          THEN 'dy' 
									          WHEN categoryname = '电视剧' 
									          THEN 'dsj' 
									          WHEN categoryname = '动漫' 
									          THEN 'dm' 
									          WHEN categoryname = '纪录片' 
									          THEN 'jlp' 
									          WHEN categoryname = '综艺' 
									          THEN 'zy' 
									          WHEN categoryname = '3D影片' 
									          THEN '3D' 
									          WHEN categoryname = '体育' 
									          THEN 'ty' 
									          WHEN categoryname = '少儿' 
									          THEN 'se' 
									          WHEN categoryname = '生活' 
									          THEN 'sh' 
									          WHEN categoryname = '电视回看' 
									          THEN 'dshk' 
									          WHEN categoryname = '短视频' 
									          THEN 'dsp' 
									          WHEN categoryname = '超高清' 
									          THEN 'cgq' 
									          WHEN categoryname = '音乐MTV' 
									          THEN 'yymtv' 
									          WHEN categoryname = '预告片' 
									          THEN 'ygp' 
									        END
									      ) AS category,
									      categoryid 
									    FROM
									      res.`category` 
									    WHERE parent = 1 
									      AND categoryname NOT IN (
									        '精品推荐',
									        '高清影视',
									        '华语院线',
									        '好莱坞大片',
									        '新片速递',
									        '影视大厅'
									      )) AS b,
									    tvos.`video_site` AS s 
									  WHERE a.id = s.`v_id` 
									    AND a.`category` = b.category 
									    AND a.`expired`=0
									    AND b.categoryid IN ('".$v_categoryid."')".$v_sql." 
									    ORDER BY a.`level` DESC,a.`release_date` DESC 
									  ) AS h GROUP BY h.categoryid
									UNION
									ALL 
									SELECT 
									  f.categoryid,
									  f.id,
									  f.thumb  
									FROM
									  (SELECT 
									    d.categoryid,
									    b.id,
									    b.thumb 
									  FROM
									    res.`top` AS a,
									    tvos.`video` AS b,
									    tvos.`video_site` AS s,
									    res.`category` AS d 
									  WHERE a.source_id = b.id 
									    AND a.path_name = d.categoryname 
									    AND b.id = s.v_id ".$v_sql." 
									    AND b.`expired`=0
									    AND b.`id`>0
									    AND a.path_name IN (
									      '精品推荐',
									      '高清影视',
									      '华语院线',
									      '好莱坞大片'
									    ) 
									    AND d.categoryid IN ('".$v_categoryid."') 
									  ORDER BY a.`index` DESC) AS f 
									GROUP BY f.categoryid 
									UNION
									ALL 
									SELECT 
									  g.categoryid,
									  g.id,
									  g.thumb  
									FROM
									  (SELECT 
									    c.categoryid,
									    a.id,
									    a.thumb 
									  FROM
									    tvos.`video` AS a,
									    tvos.`video_site` AS s,
									    res.`category` AS c 
									  WHERE a.id = s.v_id 
									    AND a.category IN ('dy', 'dsj')".$v_sql." 
									    AND a.`expired`=0 
									    AND c.`categoryname` = '新片速递' 
									    AND c.`categoryid` IN ('".$v_categoryid."') 
									  ORDER BY a.`id`DESC 
									  LIMIT 100) AS g 
									GROUP BY g.`categoryid` ")->toList();
		
			
	}
	
	/**
	 * 
	 * @param array $v_categoryid           数组分类ID
	 * @param unknown_type $syscondition    策略条件
	 * @return multitype:                   返回分类ID,资源ID,缩图（查询的是新库，新库上线后此接口生效）
	 */
	public static function getVideotThumByCid_New(array $v_categoryid ,$syscondition){
	
		$v_categoryid=implode("','", $v_categoryid);
	
	
		if($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		
		return parent::createSQL("SELECT 
									  h.`category_id`,
									  h.`v_id`,
									  h.`thumb`  
									FROM
									  (SELECT 
									    rca.`category_id`,
									    rv.`v_id`,
									    rv.`thumb` 
									  FROM
									    `skyg_res`.`res_video` AS rv,
									    (SELECT 
									      (
									        CASE
									          WHEN `category_name` = '电影' 
									          THEN 'dy' 
									          WHEN `category_name` = '电视剧' 
									          THEN 'dsj' 
									          WHEN `category_name` = '动漫' 
									          THEN 'dm' 
									          WHEN `category_name` = '纪录片' 
									          THEN 'jlp' 
									          WHEN `category_name` = '综艺' 
									          THEN 'zy' 
									          WHEN `category_name` = '3D影片' 
									          THEN '3D' 
									          WHEN `category_name` = '体育' 
									          THEN 'ty' 
									          WHEN `category_name` = '少儿' 
									          THEN 'se' 
									          WHEN `category_name` = '生活' 
									          THEN 'sh' 
									          WHEN `category_name` = '电视回看' 
									          THEN 'dshk' 
									          WHEN `category_name` = '短视频' 
									          THEN 'dsp' 
									          WHEN `category_name` = '超高清' 
									          THEN 'cgq' 
									          WHEN `category_name` = '音乐MTV' 
									          THEN 'yymtv' 
									          WHEN `category_name` = '预告片' 
									          THEN 'ygp' 
									        END
									      ) AS `category`,
									      `category_id` 
									    FROM
									      `skyg_res`.`res_category` 
									    WHERE `parent` = 1 
									      AND `category_name` NOT IN (
									        '精品推荐',
									        '高清影视',
									        '华语院线',
									        '好莱坞大片',
									        '新片速递',
									        '影视大厅'
									      )) AS rca,
									    `skyg_res`.`res_video_site` AS rvs 
									  WHERE rv.`v_id` = rvs.`v_id` 
									    AND rv.`category` = rca.`category` 
				                        AND rv.`expired`=0
									    AND rca.`category_id` IN ('".$v_categoryid."')".$v_sql."
				                        ORDER BY rv.`vip` DESC,rv.`release_date` DESC ) AS h 
									GROUP BY h.`category_id` 
									UNION
									ALL 
									SELECT 
									   f.`category_id`,
									   f.`v_id`,
									   f.`thumb` 
									FROM
									  (SELECT 
									    rca.`category_id`,
									    rv.`v_id`,
									    rv.`thumb` 
									  FROM
									    `skyg_res`.`res_top` AS rt,
									    `skyg_res`.`res_video` AS rv,
									    `skyg_res`.`res_video_site` AS rvs,
									    `skyg_res`.`res_category` AS rca 
									  WHERE rt.`source_id` = rv.`v_id` 
									    AND rt.`recommend_name` = rca.`category_name` 
									    AND rv.`v_id` = rvs.`v_id`".$v_sql."
				                        AND rv.`v_id`>0
				                        AND rv.`expired`=0
									    AND rt.`recommend_name` IN (
									      '精品推荐',
									      '高清影视',
									      '华语院线',
									      '好莱坞大片'
									    ) 
									    AND rca.`category_id` IN ('".$v_categoryid."') 
									  ORDER BY rt.`sequence` DESC) AS f 
									GROUP BY f.`category_id` 
									UNION
									ALL 
									SELECT 
									  g.`category_id`,
									  g.`v_id`,
									  g.`thumb` 
									FROM
									  (SELECT 
									    rca.`category_id`,
									    rv.`v_id`,
									    rv.`thumb` 
									  FROM
									    `skyg_res`.`res_video` AS rv,
									    `skyg_res`.`res_video_site` AS rvs,
									    `skyg_res`.`res_category` AS rca 
									  WHERE rv.`v_id` = rvs.`v_id` 
									    AND rv.`category` IN ('dy', 'dsj')".$v_sql."
				                        AND rv.`expired`=0 
									    AND rca.`category_name` = '新片速递' 
									    AND rca.`category_id` IN ('".$v_categoryid."') 
									  ORDER BY rv.`v_id` DESC 
									  LIMIT 100) AS g 
									GROUP BY g.`category_id` ")->toList();
	}
	
}


