<?php 
namespace upgrade\controllers;
 
use Sky\Sky;
use Sky\base\Controller; 
use upgrade\models\DTVManngeModel; 
use skyosadmin\components\PolicyController;  
use skyosadmin\components\Page;
use base\terminal\models\DeviceManageModel;
 
 
class DTVManngeController extends PolicyController {
	/*
	 * 用于保存排序字段
	*/
	private $order = array();
	/*
	 * 用于保存搜索的字段
	*/
	private $s = array();
	/*
	 * 用于保存所有url的参数
	*/
    private $request = array();
     
	public function beforeAction($action){
	    $this->request = parent::getActionParams();  
	 
	   //搜索原始参数处理，是否开启搜索参数  _search=false 为关闭搜索，true为开启搜索
		if(isset($this->request['_search'])){
			$this->request['searchOn'] = parent::Strip($this->request['_search']);
		}
		//单字段排序处理
		if(isset($this->request['sidx'])&&isset($this->request['sord'])){
			$this->order = array($this->request['sidx']=>$this->request['sord']);
		}
		//单字段搜索处理
		if(isset($this->request['searchField'])&& isset($this->request['searchString'])){
			 $this->s = array(
			 		$this->request['searchField']=>parent::Strip($this->request['searchString'])
			 );
		}
		return true;
	}
	
	
	
	//这是一个条件执行方法统一入口
	public function actionDTVMannge(){
		//开启搜索列表
		if($this->request['searchOn']=='true' || $this->request['searchAreaOn']=='true') {
          return $this->searchDTVManngeList();
		//正常列表
		}else{
		  return $this->DTVManngeList();
		}
	}
	
	/* 
	 *  正常列表
	 */
	public function DTVManngeList(){
		
	 
		$pager = new Page(DTVManngeModel::getDTVCount()); 
		//处理分页
		$pager->prePage();
		 
		//获取资源
		$res =  DTVManngeModel::getDTVLists($pager->start,$pager->limit,$this->order);
		
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		
		return $arr;
	}
    
 

	/*
	 *  搜索列表
	 */
	public function searchDTVManngeList(){
	    
		$pager = new Page(DTVManngeModel::searchDTVCount($this->s)); 
		//处理分页
		$pager->prePage();
		  
		$res = DTVManngeModel::searchDTV(
				$this->s,
				$pager->start,
				$pager->limit,$this->order);
		 
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		return  $arr; 
	}
	
	/*
	 *  @添加、删除，修改方法
	 */
	
	function actionDoOper($oper){
		 if($oper=='add'){
		 	return $this->add( $this->request );
		 }elseif ($oper=='edit'){
			return $this->edit( $this->request ); 
		}elseif ($oper=='del'){
			return $this->del( $this->request ); 
		}
	}
	
 
   public function add($arr){ 
   	 $Arr = array(
   			'dtv_name'=>$arr['dtv_name'], //dtv名称
   			'dtv_code'=>$arr['dtv_code'], //用户id
   			'dtv_version'=>$arr['dtv_version'], //dtv版本
   	 		'hw_version'=>$arr['hw_version'], //硬件版本
   			'download_url'=>$arr['download_url'],
   	 		'md5'=>$arr['md5'],
   	 		'filesize'=>$arr['filesize']
    );
   	return  DTVManngeModel::insertDTV($Arr);
   }
  
 
 
	public function edit($arr){
		$Arr =array(
    				'dtv_name'=>$arr['dtv_name'],
				    'dtv_code'=>$arr['dtv_code'],
				    'dtv_version'=>$arr['dtv_version'],
				    'download_url'=>$arr['download_url'],
				    'hw_version'=>$arr['hw_version'], //硬件版本
				    'md5'=>$arr['md5'],
				    'filesize'=>$arr['filesize'],
    				'upgrade_dtv_id'=>$arr['id']
    			);
		//返回1表示成功，返回0表示失败
		return DTVManngeModel::updateDTV($Arr);
		//删除 
	}
	
 
	public function del($arr){
		$id = $arr['id'];
		$rec = DTVManngeModel::deleteDTV($id); 
		return $rec;	//成功>0，失败0 
	}
 
}