<?php
namespace push\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/** table skyg_res. 
 * 
 * @author Zhengyun
 */
class PushManageModel extends \Sky\db\ActiveRecord{
	/**
	 *@return PushManageModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}		
	
	/**
	 * [推送推送信息]
	 * @param unknown_type $u_id
	 * @param unknown_type $recive_ids
	 * @param unknown_type $title
	 * @param unknown_type $content
	 * @param unknown_type $res_type
	 * @param unknown_type $res_url
	 * @param unknown_type $exist_thumbnail
	 * @param unknown_type $expired_date
	 * @param unknown_type $direct_play
	 * @return boolean
	 */
	public static function pushDeliveryNews($u_id,$recive_ids,$title,$content,$res_type,$res_url,$exist_thumbnail,$expired_date,$direct_play){
		if ($expired_date == ""){
			$expired_date = date('Y-m-d H:i:s',strtotime('+7 day'));
		}
		$msgId=0;
		//插入deliver源数据表
		$sql = "INSERT INTO `skyg_base`.`base_push_delivery_news`(`dn_delivery_u_id`,`dn_recive_u_ids`,
			   `dn_res_title`,`dn_res_content`,`dn_res_type`,`dn_res_url`,`dn_exist_thumbnail`,
			   `dn_expired_date`,`dn_direct_play`)";
		$formatValue = ' VALUES(%d,"%s","%s","%s","%s","%s",%d,"%s",%d)';
		$parameterValue = sprintf($formatValue,$u_id,addslashes($recive_ids),addslashes($title),
				addslashes($content),addslashes($res_type),addslashes($res_url),
				$exist_thumbnail,addslashes($expired_date),$direct_play);
		$result=parent::createSQL($sql.$parameterValue);
		
		if($result->exec()==0){
			return false;			
		}
		$result->getPdoInstance();
		$msgId=$result->lastInsertID();
// 		var_dump($msgId);
		
// 		echo"################";
	
		$sql = "SELECT 
				  us.user_nickname AS delivery_u_nickname,
				  us.user_icon AS delivery_u_icon,
				  '' AS delivery_f_nickname,
				  dn.dn_res_title,
				  dn.dn_res_content,
				  dn.dn_res_type,
				  dn.dn_res_url,
				  dn.dn_exist_thumbnail,
				  dn.dn_direct_play 
				FROM
				  `skyg_base`.`base_push_delivery_news` AS dn,
				  `skyg_base`.`base_user` AS us 
				WHERE dn.dn_delivery_u_id = us.user_id 
				  AND dn.dn_id = %d";
		$sql = sprintf($sql,$msgId);
		$result=parent::createSQL($sql)->toList();
		$jsonContent = json_encode($result);
		//echo "【sql:".$sql."】";
		//插入信息推送表
		$arrRec = explode(',', $recive_ids);
		foreach($arrRec as $To){
			$sql = "INSERT INTO `skyg_base`.`base_push_message_list`(`pm_type`,`pm_recive_u_id`,
			`pm_delivery_u_id`,`pm_time`,`pm_content`,`pm_level`,`pm_expired_time`)";
			$formatValue = ' VALUES("delivery","%s",%d,"","%s",0,"%s")';
			$parameterValue = sprintf($formatValue,$To,$u_id,addslashes($jsonContent),addslashes($expired_date));
			//$db->execute($sql.$parameterValue);
			//$msgId = $db->getLastInsertId();
			$result=parent::createSQL($sql.$parameterValue);			
			if($result->exec()==0){
				return false;
			}
			$result->getPdoInstance();
			$msgId=$result->lastInsertID();			
			$arr[$To] = $msgId;			 
		}			
		return $arr;
	}
	
	//推送预约信息
	public static function pushOrderNews($u_id,$res_type,$res_url,$title,$content,$order_time){
		//预约时间为空 默认添加一天
		if ($order_time == ""){
			$order_time = date('Y-m-d H:i:s',strtotime('+1 day'));
		}
		$msgId=0;
		//插入源预约数据表
		$sql = "INSERT INTO `skyg_base`.`base_push_user_order`(`uo_u_id`,`uo_res_type`,`uo_res_url`,`uo_title`,`uo_content`,`uo_time`)";
		$formatValue = ' VALUES(%d,"%s","%s","%s","%s","%s")';
		$parameterValue = sprintf($formatValue,$u_id,addslashes($res_type),addslashes($res_url),
				addslashes($title),addslashes($content),addslashes($order_time));
		$result=parent::createSQL($sql.$parameterValue);
		
		if($result->exec()==0){
			return false;			
		}
		$result->getPdoInstance();
		$msgId=$result->lastInsertID();
	
		$sql = "SELECT t.uo_title,t.uo_content,t.uo_res_type AS uo_type,t.uo_res_url AS uo_url,t.uo_time,t.uo_created_date
		FROM `skyg_base`.`base_push_user_order` AS t where t.uo_id=%d";
		$sql = sprintf($sql,$msgId);
		$result=parent::createSQL($sql)->toList();
		//$result = $db->getListResult($sql);
		$jsonContent = json_encode($result);
	
		//插入信息推送表
		$pushList = array();
		$sql = "INSERT INTO `skyg_base`.`base_push_message_list`(`pm_type`,`pm_recive_u_id`,
		`pm_delivery_u_id`,`pm_time`,`pm_content`,`pm_level`,`pm_expired_time`)";
		$formatValue = ' VALUES("order",%d,%d,"%s","%s",0,"%s")';
		$parameterValue = sprintf($formatValue,$u_id,$u_id,addslashes($order_time),
				addslashes($jsonContent),addslashes($order_time));
		$result=parent::createSQL($sql.$parameterValue);
		
		if($result->exec()==0){
			return false;			
		}
		$result->getPdoInstance();
		$msgId=$result->lastInsertID();
		return $msgId;
	}
	
	//设置已读：delivery:id=dn_id;business:id=u_id;order:id=od_id
	public static function setReaded($u_id,$msg_id){
		$sql = sprintf("update `skyg_base`.`base_push_message_list` set pm_readed=1 where pm_id=%d",$msg_id);
		$result=parent::createSQL($sql)->exec();
		return $result>0?true:false;
	}
	
	/**
	 *  [信息推送且服务器保存数据]
	 * @param int $delivery_u_id：推送人u_id
	 * @param String $recive_ids：接收人u_id 多用户"，"分隔
	 * @param String $type：推送信息类型，参考上面“$pushType”数组
	 * @param String $content：推送内容
	 * @param int $level：推送优先级 0-9
	 * @param String $pushtime：推送时间，空为即时
	 * @return boolean
	 */
	public static function pushMsgAndSave($delivery_u_id,$recive_ids,$type,$content,$level,$pushtime){
		$expiredTime = date('Y-m-d H:i:s',strtotime('+7 day'));
		$arrRecId = explode(',', $recive_ids);
		foreach ($arrRecId as $To){
				$sql = "INSERT INTO `skyg_base`.`base_push_message_list`(`pm_type`,`pm_recive_u_id`,
				`pm_delivery_u_id`,`pm_time`,`pm_content`,`pm_level`,`pm_expired_time`)";
			$formatValue = ' VALUES("%s","%s",%d,"%s","%s",%d,"%s")';
			$parameterValue = sprintf($formatValue,addslashes($type),$To,$delivery_u_id,
					addslashes($pushtime),addslashes($content),$level,addslashes($expiredTime));
			$result=parent::createSQL($sql.$parameterValue);			
			if($result->exec()==0){
				return false;
			}
			$result->getPdoInstance();
			$msgId=$result->lastInsertID();
			$arr[$To]=$msgId;
		}		
		
		return $arr;
	}
	
	public static function getPushHost($u_id,$version){
		$signature = $version;
		$sql = "SELECT server_ip as connect_ip,server_conn_port as connect_port FROM `skyg_base`.`base_push_delivery_server`";
		$result = parent::createSQL($sql)->toList();
		return $result;
	}
	
	public static function getUserMessage($u_id,$type,$page_size,$page_index,$vt){
		$nowtime = time();
		$strWhere = sprintf("where `pm_recive_u_id` in ('%d','B') and pm_readed=0 and pm_expired_time>=NOW()",$u_id);
		if($vt != null){
			$vt = date("Y-m-d H:i:s",$vt);
			$strWhere .= sprintf(' and pm_creat_time>="%s" ',$vt);
		}
	
		$limitCondition = sprintf('order by `pm_id` desc limit %1$d,%2$d',$page_index*$page_size,$page_size);
		$total = parent::createSQL("select count(*) from `skyg_base`.`base_push_message_list` $strWhere;")->toValue();
	
		$sql = "select `pm_id` AS `id`,`pm_type` AS `type`,`pm_content` AS `content`,`pm_time` AS `time` from `skyg_base`.`base_push_message_list` $strWhere $limitCondition;";
	
		$result = parent::createSQL($sql)->toList();	
		return $result;
	}
	
	public static function getPushServer(){
		$sql = "SELECT t.server_ip,t.server_push_port,t.server_conn_port FROM `skyg_base`.`base_push_delivery_server` as t";
		return parent::createSQL($sql)->toList();
	}
	
	
	//获取推送消息数量
	public static function getPushMessageListCount($type){
		$s_where="";
		if($type!="")
			$s_where=sprintf(" where pm_type in(%s)",$type);
		$sql = sprintf("SELECT
				  count(*)
				FROM
				  `skyg_base`.`base_push_message_list`
				 %s ",$s_where);
		return parent::createSQL($sql)->toValue();
	}
	
	//推送消息列表
	public static function getPushMessageList($type,$start,$limit,$orderCondition=array('pm_creat_time'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$s_where="";
		if($type!="")
			$s_where=sprintf(" where pm_type in(%s)",$type);
		$sql = sprintf("SELECT 
				  `pm_id`,
				  `pm_type`,
				  `pm_delivery_u_id`,
				  `pm_recive_u_id`,
				  `pm_level`,
				  `pm_time`,
				  `pm_readed`,
				  `pm_expired_time`,
				  `pm_creat_time`,
				  `pm_content` 
				FROM
				  `skyg_base`.`base_push_message_list`
				%s
				ORDER BY %s
				LIMIT %d,%d",$s_where,$orderString,$start,$limit);
		return parent::createSQL($sql)->toList();
	}
	
	//获取推送消息数量
	public static function deletePushMessage($pm_id){
		$sql = sprintf("DELETE
				FROM
				  `skyg_base`.`base_push_message_list`
				WHERE	pm_id=%d",$pm_id);
		return parent::createSQL($sql)->exec();
	}
	
	
	//搜索推送消息数量
	public static function searchPushMessageListCount($type,$searchCondition){
		$s_where="";
		if($type!="")
			$s_where=sprintf(" where pm_type in(%s)",$type);
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
		{				
			if($s_where!='')	
				$s_where.=' AND  '.$searchString;
			else 
				$s_where.=' WHERE  '.$searchString;
		}
		$sql = sprintf("SELECT
				  count(*)
				FROM
				  `skyg_base`.`base_push_message_list`
				 %s ",$s_where);
		return parent::createSQL($sql)->toValue();
	}
	
	//搜索推送消息列表
	public static function searchPushMessageList($type,$searchCondition,$start,$limit,$orderCondition=array('pm_creat_time'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$s_where="";
		if($type!="")
			$s_where=sprintf(" where pm_type in(%s)",$type);
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
		{				
			if($s_where!='')	
				$s_where.=' AND  '.$searchString;
			else 
				$s_where.=' WHERE  '.$searchString;
		}
		$sql = sprintf("SELECT
				  `pm_id`,
				  `pm_type`,
				  `pm_delivery_u_id`,
				  `pm_recive_u_id`,
				  `pm_level`,
				  `pm_time`,
				  `pm_readed`,
				  `pm_expired_time`,
				  `pm_creat_time`,
				  `pm_content`
				FROM
				  `skyg_base`.`base_push_message_list`
				%s
				ORDER BY %s
				LIMIT %d,%d",$s_where,$orderString,$start,$limit);
		return parent::createSQL($sql)->toList();
	}
	
}