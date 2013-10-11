<?php
namespace resource\controllers;
use Sky\Sky;
use Sky\base\Controller;
use resource\models\RecommendModel;
use skyosadmin\components\PolicyController;
use skyosadmin\components\Page;

class RecommendController extends PolicyController {

	/*
	 * 用于保存所有url的参数
	 */
	private $request = array();
	/*
	 * 用于保存排序字段
	 */
	private $order = array();
	/*
	 * 用于保存搜索的字段
	 */
	private $s = array();

	//推荐类型id
	private $recommend_type;

	//资源分类表中推荐的id
	private $topc_id;

	//推荐的资源id
	private $source_id;

	//推荐的资源类型
	private $source_type;

	//排序
	private $sequence;

	//推荐表id
	private $id;

	/*
	 *  在加本类中所有方法前必加载此方法，方法最后必须以return true才生效
	 */
	public function beforeAction($action){
		//url中所有参数加载
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

		if(isset($this->request['recommend_type'])){
			$this->recommend_type = $this->request['recommend_type'];
		}

		if(isset($this->request['topc_id'])){
			$this->topc_id = $this->request['topc_id'];
		}

		if(isset($this->request['source_id'])){
			$this->source_id = $this->request['source_id'];
		}

		if(isset($this->request['source_type'])){
			$this->source_type = $this->request['source_type'];
		}

		if(isset($this->request['id'])){
			$this->id = $this->request['id'];
		}

		if(isset($this->request['sequence'])){
			$this->sequence = $this->request['sequence'];
		}

		return true;
	}



	//这是一个条件执行方法统一入口
	public function actionRecommend(){
		//开启搜索列表
		if($this->request['searchOn']=='true') {
			return $this->searchRecommendList();
			//正常列表
		}else{
			return $this->RecommendList();
		}
	}
	
	//这是一个条件执行方法统一入口
	public function actionUnrecommend(){
		//开启搜索列表
		if($this->request['searchOn']=='true') {
			return $this->searchUnrecommendVideoList();
			//正常列表
		}else{
			return $this->UnrecommendVideoList();
		}
	}

	/*
	 *  正常列表
	 */
	public function RecommendList(){

		$pager = new Page(RecommendModel::getTopCount($this->recommend_type));
		//处理分页
		$pager->prePage();

		//获取资源
		$res =  RecommendModel::getTopList($this->recommend_type,$pager->start,$pager->limit,$this->order);


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
	public function searchRecommendList(){
		//计算总数
		$pager = new Page(RecommendModel::searchTopCount($this->recommend_type,$this->s));
		//处理分页
		$pager->prePage();

		$res = RecommendModel::searchTopList(
		$this->recommend_type,
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
	 *  正常列表
	 */
	public function UnrecommendVideoList(){

		$pager = new Page(RecommendModel::getUnrecommendVideoCount());
		//处理分页
		$pager->prePage();

		//获取资源
		$res =  RecommendModel::getUnrecommendVideoList($pager->start,$pager->limit,$this->order);


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
	public function searchUnrecommendVideoList(){
		//计算总数
		$pager = new Page(RecommendModel::searchUnrecommendVideoCount($this->s));
		//处理分页
		$pager->prePage();

		$res = RecommendModel::searchUnrecommendVideoList(
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
			return $this->add($this->request );
		}elseif ($oper=='edit'){
			return null;
		}elseif ($oper=='del'){
			return $this->del( $this->request );
		}
	}

	/*
	 * 添加
	 */
	public function add($addArr){
		$arr = array(
				"recommend_type"=>$addArr["recommend_type"],
				"source_id"=>$addArr["source_id"],
				"source_type"=>$addArr['source_type'],

		);
		return RecommendModel::addTop($arr);
	}

	/*
	 * 删除
	 */
	public function del($delArr){
		return RecommendModel::deleteTop($delArr['id']);
	}

	//排序 +1
	public function actionSetSequenceRise() {
		return RecommendModel::SetSequenceRise($this->source_type,$this->recommend_type,$this->sequence,$this->id);
	}

	//排序 -1
	public function actionsetSequenceDecline() {
		return RecommendModel::setSequenceDecline($this->source_type,$this->recommend_type,$this->sequence,$this->id);
	}

	/*
	 * 取分类
	 */
	public function actionGetCategory(){
		$cat = RecommendModel::getAllTopCategory($this->topc_id);
		$arr = array();
		foreach( $cat as $key=>$value){
			$arr[$value['category_id']] = $value['category_name'];
		}
		return array_filter($arr);
	}
}