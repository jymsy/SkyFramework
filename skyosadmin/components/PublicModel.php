<?php
namespace skyosadmin\components;

use Sky\db\DBCommand;

/**table 
 */
class PublicModel extends \Sky\db\ActiveRecord{
	/**
	 *@return PublicModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	
	
	/**
	 *
	 * @param array $arrayCondition
	 * @param string $split
	 * @param string $point
	 * @return string
	 */
	public static function controlArray($arrayCondition,$split=' ',$point=',')
	{
		$con='';
		foreach($arrayCondition as $a=>$b){
			//if($a=='product_name'||$a=='product_owner_name'||$a=='product_type_name')
			//	$a='convert('.$a.' using gbk) ';
			if (intval($a) == 0)$a = "`$a`";
			if($con=='')
				$con=$a.$split.$b;
			else
				$con.=$point.$a.$split.$b;
		}
		return $con;
	}
	
	public static function controlsearch($arrayCondition)
	{
		$con='';
		foreach($arrayCondition as $a=>$b){
			if($con=='')
				$con=sprintf("`%s` LIKE '%s'",$a,addslashes("%".$b."%"));
			else
			{
				$con.=' AND ';
				$con.=sprintf("`%s` LIKE '%s'",$a,addslashes("%".$b."%"));
			}
		}
		return $con;
	}
	
	
	public static function controlExactSearch($arrayCondition)
	{
		$con='';
		foreach($arrayCondition as $a=>$b){
			if($con=='')
				$con=sprintf("`%s` LIKE '%s'",$a,addslashes($b));
			else
			{
				$con.=' AND ';
				$con.=sprintf("`%s` LIKE '%s'",$a,addslashes($b));
			}
		}
		return $con;
	}
}