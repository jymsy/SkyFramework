<?php
namespace resource\controllers;

use resource\models\PolicyDeviceParameterModel;

use resource\models\PolicyManageModel;

use Sky\Sky;
use Sky\base\Controller;
use skyosadmin\components\PolicyController; 


class PolicyManageController extends PolicyController {
private $page;
	private $limit;
	private $sidx;
	private $sord;
	private $count;
	private $total_pages;
	private $start;
	private $_search;
	private $oper; 
    private $searchOn;
   
	
	public function actions() {
		return array(
				"Wsdl"=>array(
						"class"=>"Sky\\base\\WebServiceAction"
				),
		);
	}
	
	public function actionTest(){
		echo 'aa';
	}
	
	public function __construct(){
		
		// get the requested page
		$this->page = $_REQUEST['page'];
		// get how many rows we want to have into the grid
		// rowNum parameter in the grid
		$this->limit = $_REQUEST['rows'];
		// get index row - i.e. user click to sort
		// at first time sortname parameter - after that the index from colModel
		$sidx = $_REQUEST['sidx']; //字段名 
		// sorting order - at first time sortorder
		$this->sord = $_REQUEST['sord']; //asc or desc
		  
		!isset($sidx) ? $this->sidx = 1:$this->sidx = $sidx; 
		$this->sidx==''?$this->sidx=1:'';
		$this->searchOn = parent::Strip($_REQUEST['_search']);
	}
	
	public function actiongetList(){
		if ($this->searchOn == 'true'){
			return $this->getSearchList();
		}else{
			return $this->getList();
		}
	}
	
	/*
	 *  @添加、删除，修改方法
	 */
	
	function actionDoOper(){
	     
		$oper = $_REQUEST['oper']; 
		 
		if($oper=='edit'){
			
			return $this->edit( $_REQUEST );
			
		}elseif ($oper=='del'){
			
			return $this->del( $_REQUEST );
			
		}elseif ($oper == 'add'){
			
			return $this->add($_REQUEST);
			
		}elseif ($oper == 'onsale'){
			$flag = 0;
			$policyid = $_REQUEST['policy_id'];
			return PolicyManageModel::UpdatePolicyFlag($policyid, $flag);
		}elseif ($oper == 'offsale'){
			$flag = 1;
			$policyid = $_REQUEST['policy_id'];
			return PolicyManageModel::UpdatePolicyFlag($policyid, $flag);
		}
		
	}
	
	public function actiongetPolicyModel(){
		return PolicyDeviceParameterModel::GetPolicyModel();
	}
	
	public function actiongetPolicyChip(){
		$model = $_REQUEST['model'];
		return PolicyDeviceParameterModel::GetPolicyChip($model);
	}
	
	public function actionPolicyPlatform(){
		$chip = $_REQUEST['chip'];
		$model = $_REQUEST['model'];
		return PolicyDeviceParameterModel::GetPolicyPlatform($chip, $model);
	}
	
	//处理分页参数预先存入对像属性字段中
	public function prePage(){
		 
		if( $this->count >0 ) {
			$this->total_pages = ceil($this->count/$this->limit);
		} else {
			$this->total_pages = 0;
		}
		
		if ($this->page > $this->total_pages) 
			$this->page = $this->total_pages;
		
		// do not put $limit*($page - 1)
		$this->start = $this->limit*$this->page - $this->limit; 
 
		if($this->start <0) $this->start = 0; 
	}
	
	public function getList(){
		$this->count = PolicyManageModel::GetPolicyCount();
		$this->prePage();
		$order = array($this->sidx=>$this->sord);
		$arr = array(
				"records"=>$this->count, //总条数
				"rows"=>PolicyManageModel::GetPolicyList($this->start, $this->limit,$order),
				"total"=>$this->total_pages, //总页数
				"page"=>$this->page
		);
		return $arr;
	}
	
	public function getSearchList(){
		$searchField = $_REQUEST['searchField'];
		$searchString  = parent::Strip($_REQUEST['searchString']); 
		 
		$searchArr = array($searchField=>$searchString);
		
		$this->count = PolicyManageModel::GetPolicyInfoCount($searchArr);
		$this->prePage();
		$order = array($this->sidx=>$this->sord);
		$arr = array(
				"records"=>$this->count, //总条数
				"rows"=>PolicyManageModel::GetPolicyInfo($searchArr, $this->start, $this->limit,$order),
				"total"=>$this->total_pages, //总页数
				"page"=>$this->page
		);
		return $arr;
	}
	
	public function add($array){
		$v_policyname = $array['policy_name'];
		$v_fname = $array['function_name'];
		$v_chip = $array['chip'];
		$v_model = $array['model'];
		$v_platform = $array['platform'];
		$v_size = $array['screen_size'];
		$v_macstart = $array['mac_start'];
		$v_macend = $array['mac_end'];
		$v_flag = $array['flag'];
		//$v_value = $array['policy_value'];
		$v_remark = $array['remark'];
		$v_priority = $array['priority'];
		$policy = $array['policy'];
		if ($policy == 'defined'){
			$v_value = $array['defined'];
		}else{
			$mid_id = substr($policy, 2);
			$type = $array['type'.$mid_id];
			$short_db_name = $array['db_table'.$mid_id];
			$col = $array['col'.$mid_id];
			$col_array = $array[$col.$mid_id];
			foreach ($col_array as &$mid){
				$mid = " $short_db_name.`$col`='$mid' ";
			}
			$v_value = implode($type, $col_array);
		}
		$v_version = $array['version'];
		$policy_count = PolicyManageModel::searchPolicy($v_policyname, $v_fname, $v_chip, $v_model, $v_platform,
												 $v_size, $v_macstart, $v_macend, $v_value, $v_remark, $v_version);
		if ($policy_count>0){
			return 'FALSE';
		}else {
			return PolicyManageModel::InsertPolicy($v_policyname,$v_fname,$v_chip, $v_model, $v_platform, $v_size,
					$v_macstart,$v_macend, $v_flag, $v_value, $v_remark, $v_priority,$v_version);
		}
		
	}
	
	public function edit($array){
		$policyid = $array['policy_id'];
		$policyvalue = $array['policy_value'];
		$vpriority = $array['priority'];
		$vremark = $array['remark'];
		$vflag = $array['flag'];
		$v_macstart = $array['mac_start'];
		$v_macend = $array['mac_end'];
		return PolicyManageModel::UpdatePolicy($policyid, $policyvalue, $vpriority, $vremark,$vflag,$v_macstart,$v_macend);
	}
	
	public function del($array){
		$policyid = $array['policy_id'];
		return PolicyManageModel::DeletePolicyById($policyid);
	}
	
	
	
	
	
}