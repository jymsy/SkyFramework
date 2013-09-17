<?php
namespace tvos\autorun\models;
/**            
 * 
 * @author xiaokeming
 */

class ResourceStatisticsModel extends \Sky\db\ActiveRecord{
	/**
	 *@return ResourceStatisticsModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	/**
	 *
	 * @param string $starttime     开始时间字符串
	 * @param string $endtime       结束时间字符串
	 *
	 */
	public static function querymedia($starttime,$endtime){
		
		return parent::createSQL("SELECT
									  t.total,
									  IFNULL(e.expired, 0) AS `expired`,
									  IFNULL(n.`new`, 0) AS `new`,
									  e.category,
									  e.source AS `from`
									FROM
									  (SELECT
									    COUNT(*) AS `total`,
									    a.category,
									    b.source
									  FROM
									    `skyg_res`.`res_video` a
									    JOIN `skyg_res`.`res_video_site` b
									      ON a.v_id = b.v_id
									  WHERE a.expired = 0
									  GROUP BY a.category,
									    b.source) AS t
									  JOIN
									    (SELECT
									      COUNT(*) AS `expired`,
									      a.category,
									      b.source
									    FROM
									      `skyg_res`.`res_video` a
									      JOIN `skyg_res`.`res_video_site` b
									        ON a.v_id = b.v_id
									    WHERE a.expired = 1
									    GROUP BY a.category,
									      b.source) AS e
									    ON t.category = e.category
									    AND t.source = e.source
									  LEFT JOIN
									    (SELECT
									      COUNT(*) AS `new`,
									      a.category,
									      b.source
									    FROM
									      `skyg_res`.`res_video` a
									      JOIN `skyg_res`.`res_video_site` b
									        ON a.v_id = b.v_id
									    WHERE a.expired = 0
									      AND a.created_date BETWEEN '".$starttime."'
									      AND '".$endtime."'
									    GROUP BY a.category,
									      b.source) AS n
									    ON t.category = n.category
									    AND t.source = n.source")->toList();
	}
	
	/**
	 *
	 * @param string $starttime     开始时间字符串
	 * @param string $endtime       结束时间字符串
	 * @param string $syscondition  策略控制条件
	 *
	 */
	public static function querynews($starttime,$endtime){
	
		return parent::createSQL("SELECT
									  t.`total`,
									  IFNULL(n.`new`, 0) AS `new`,
									  t.`category_name`,
									  t.`from`
									FROM
									  (SELECT
									    COUNT(*) AS `total`,
									    n.`from`,
									    c.`category_name`
									  FROM
									    `skyg_res`.`res_news` AS n
									    JOIN `skyg_res`.`res_category` AS c
									  WHERE n.`category_id` = c.`category_id`
									  GROUP BY n.`from`,
									    c.`category_id`) AS t
									  LEFT JOIN
									    (SELECT
									      COUNT(*) AS `new`,
									      n.`from`,
									      c.`category_name`
									    FROM
									      `skyg_res`.`res_news` AS n
									      JOIN `skyg_res`.`res_category` AS c
									    WHERE n.`category_id` = c.`category_id`
									      AND n.`create_time` BETWEEN '".$starttime."'
									      AND '".$endtime."'
									    GROUP BY n.`from`,
									      c.`category_id`) AS n
									    ON t.`category_name` = n.`category_name`
									    AND t.`from` = n.`from` ")->toList();
	}
	
	/**
	 *
	 * @param string $starttime     开始时间字符串
	 * @param string $endtime       结束时间字符串
	 * @param string $syscondition  策略控制条件
	 *
	 */
	public static function querymusic($starttime,$endtime){
		
        return parent::createSQL( "SELECT
										  t.`total`,
										  IFNULL(e.expired, 0) AS `expired`,
										  IFNULL(n.`new`, 0) AS `new`,
										  t.`category_name`,
										  t.`source`
										FROM
										  (SELECT
										    COUNT(*) AS `total`,
										    m.`source`,
										    c.`category_name`
										  FROM
										    `skyg_res`.`res_music_top` AS m
										    JOIN `skyg_res`.`res_category` AS c
										  WHERE m.`category_id` = c.`category_id`
										  GROUP BY m.`source`,
										    c.`category_id`) AS t
										  LEFT JOIN
										    (SELECT
										      COUNT(*) AS `expired`,
										      m.`source`,
										      c.`category_name`
										    FROM
										      `skyg_res`.`res_music_top` AS m
										      JOIN `skyg_res`.`res_category` AS c
										    WHERE m.`category_id` = c.`category_id`
										      AND m.`expired` = 1
										    GROUP BY m.`source`,
										      c.`category_id`) AS e
										    ON t.`category_name` = e.`category_name`
										    AND t.`source` = e.`source`
										  LEFT JOIN
										    (SELECT
										      COUNT(*) AS `new`,
										      m.`source`,
										      c.`category_name`
										    FROM
										      `skyg_res`.`res_music_top` AS m
										      JOIN `skyg_res`.`res_category` AS c
										    WHERE m.`category_id` = c.`category_id`
										      AND m.`created_date` BETWEEN '".$starttime."'
										      AND '".$endtime."'
										    GROUP BY m.`source`,
										      c.`category_id`) AS n
										    ON t.`category_name` = n.`category_name`
										    AND t.`source` = n.`source`")->toList();
	}
}