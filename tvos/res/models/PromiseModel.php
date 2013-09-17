<?php
namespace res\models;
/**
 * @property  int          promise_id     自增主键ID   
 * @property  string       promise_type   约定类型     
 * @property  string       promise_key    约定key值     
 * @property  string       promise_value  约定内容值                  
 * 
 * @author xiaokeming
 */

class PromiseModel extends \Sky\db\ActiveRecord{
	/**
	 *@return PromiseModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_base.base_promise";
	protected static $primeKey=array("promise_id");
	
	/**
	 * 
	 * @param string $v_promisetype 约定类型
	 * @param string $syscondition  策略控制条件
	 * 
	 */
	public static function listpromise($v_promisetype,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"select bp.`promise_id` AS `id`,
				        bp.`promise_type` AS `what`,
				        bp.`promise_key` AS `key`,
				        bp.`promise_value` AS `value`
				   from `skyg_base`.`base_promise` AS bp
				  where bp.`promise_type` = :v_promisetype".$v_sql,
				array(
						"v_promisetype"=>$v_promisetype
						)
		)->toList();

	}
	
	/**
	 * 
	 * @param string $v_promisetype       约定类型
	 * @param string $v_promisekey        约定KEY值
	 * @param string $v_promisevalue      约定内容值
	 * @return number                     插入成功返回当前SESSION插入后生成的自增id，否则返回0
	 */
	
	public static function insertpromise($v_promisetype,$v_promisekey,$v_promisevalue){
		 $par=parent::createSQL(
				"insert into `skyg_base`.`base_promise` (
				                                   `promise_type`,
				                                   `promise_key`,
				                                   `promise_value`) 
				                          values (:v_promisetype,
				                                  :v_promisekey,
				                                  :v_promisevalue)",
				array(
						"v_promisetype"=>$v_promisetype,
						"v_promisekey"=>$v_promisekey,
						"v_promisevalue"=>$v_promisevalue
				)
		);
		 if($par->exec()){
		 	$par->getPdoInstance();
		 	return $par->lastInsertID();
		 }else{
		 	return 0;
		 }
	   
		
		 	
	}
}