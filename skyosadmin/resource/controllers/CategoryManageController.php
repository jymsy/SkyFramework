<?php 
namespace resource\controllers;
use Sky\utils\Ftp;
use Sky\Sky;
use Sky\base\Controller;
use resource\models\CategoryManageModel; 
use skyosadmin\components\PolicyController;
use skyosadmin\components\Page;
use Sky\web\UploadFile;
 
class CategoryManageController extends PolicyController {
 
    /*
     * 存入记录
     */
    public $rows = array();
	/*
	 *  上传主目录
	*/
	private $uploadroot = "";

	/*
	 *  网站根目录,php脚本主目录 
	*/
	private $root = "";
	/*
	 * 远程ftp 主目录 
	 */
	private $rs_root = "";
	
	/*
	 * logo本地目录 
	 */
	private $logoroot = "/icon";
	
	
	
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
	
	/*
	 *  在加本类中所有方法前必加载此方法，方法最后必须以return true才生效
	*/
	public function beforeAction($action){
		$this->root = ROOT; //结果= /data/cloudservice
		$this->rs_root = RS_ROOT; // 结果 = /data/www
		
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
		return true;
	}
	
	
	
 
	 
	
	//这是一个条件执行方法统一入口
	public function actionCategory(){
		//开启搜索列表
		if($this->request['searchOn']=='true') {
          return $this->searchCategoryList();
		//正常列表
		}else{
		  return $this->CategoryList();
		}
	}
	
 
	 
	
	/*
	 *  正常列表
	 */
	public function CategoryList(){
		
		$pager = new Page(CategoryManageModel::getAllCategoryCount());
		//处理分页
		$pager->prePage();
		 
		//获取资源
		$res =  CategoryManageModel::getAllCategory(0,1000,$this->order);
		
		$tempArr = array();
		// level:"1", parent:"1", isLeaf:true, expanded:false, loaded:true
		foreach($res as $key=>$value){
			//去掉尾部"/" 并统计有多少个/ 最后减去1即可得到level值
			$level = preg_match_all ("/\//",  rtrim($value['path'], "/"), $out);
			$value['level'] = $level;
			$value['expanded'] = 'false';
			$value['loaded']= 'true';
			$value['isLeaf'] = 'true'; //没有子级的分类就是叶子分类了，默认设为ture,后面可计算得 到
			$tempArr[] = $value;
		
		}
		//print_r($tempArr);
		
		$this->rows = $tempArr;
		$tree=$this->build_tree(0);
		 
		$tempArr = $this->imp($tree);
		 
		
		
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$tempArr,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
		
		return $arr;
	}
    
	
	/*
	 *  非树型正常列表
	*/
	public function actionNoTreeCategoryList(){
	
		$pager = new Page(CategoryManageModel::getAllCategoryCount());
		//处理分页
		$pager->prePage();
	
		 
	
		//获取资源
		$res =  CategoryManageModel::getAllCategory(
				$pager->start,
				$pager->limit,$this->order);
	
		$tempArr = array();
		// level:"1", parent:"1", isLeaf:true, expanded:false, loaded:true
		foreach($res as $key=>$value){
			$value['level'] = 1;  //搜索后都 是顶级菜单
			$value['parent'] = 0; //搜索后都 是顶级菜单
			$value['expanded'] = 'false';
			$value['loaded']= 'true';
			$value['isLeaf'] = 'true'; //没有子级的分类就是叶子分类了，默认设为ture,后面可计算得 到
			$tempArr[] = $value;
		}
		//print_r($tempArr);
	  
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$tempArr,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		);
	
		return $arr;
	}
	
	
 

	/*
	 *  搜索列表
	 */
	public function searchCategoryList(){
	   
		 
		$pager = new Page(CategoryManageModel::searchCategoryCount($this->s)); 
		//处理分页
		$pager->prePage();
		
	 
		
		$res = CategoryManageModel::searchCategory(
				$this->s,
				0,
				1000,$this->order);
		 
		
		$tempArr = array();
		// level:"1", parent:"1", isLeaf:true, expanded:false, loaded:true
		foreach($res as $key=>$value){
			$value['level'] = 1;  //搜索后都 是顶级菜单
			$value['parent'] = 0; //搜索后都 是顶级菜单
			$value['expanded'] = 'false';
			$value['loaded']= 'true';
			$value['isLeaf'] = 'true'; //没有子级的分类就是叶子分类了，默认设为ture,后面可计算得 到
			$tempArr[] = $value; 
		}
		
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$tempArr,
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
			return $this->del( $this->request['id'] ); 
		}
	}
	
	/*
	 * 添加
	 */
	public function add($arr){
		 
			$data = array(
					"category_name" =>$arr['category_name'], 
					"parent"=>$arr['parent']!=null?$arr['parent']:0,
					"small_logo"=>$arr['small_logo'],
					"big_logo"=>$arr['big_logo'],
					"action"=>$arr['action'],
					"sequence"=>$arr['sequence'],
					"valid"=>1,
			);
			$id = CategoryManageModel::insertCategory($data);
			/*
			$path = CategoryManageModel::getCategoryByID($id); 
			empty($path) ? $path='' : $path = '/'.$path["path"]; 
			$data["category_id"] = $id;
			$data["parent"] = $path.'/'.$id;
			CategoryManageModel::updateCategory($data);//更新顶级分类的path 
			*/
		return array(
				"id"=>$id
		);
		
	}

	/*
	 * 编辑
	*/
	public function edit($arr){
		$data = array(
				"category_name" =>$arr['category_name'],
				"small_logo" =>$arr['small_logo'],
				"big_logo" =>$arr['big_logo'],
				"valid" =>1,
				"sequence"=>$arr["sequence"],
				"action" =>$arr['action'],
				"category_id"=>$arr['id']
		); 
		return CategoryManageModel::updateCategory($data);
	}

	/*
	 * 删除
	*/
	public function del($id){ 
		return CategoryManageModel::deleteCategory($id);
	}
	  
	 
	//本地上传封面
	public function _upload($name){
		if($name=='') return null;
		header('Content-Type: text/html;charset=utf-8'); 
	 
		$_path = UPLOADROOT.$this->logoroot.'/'; //  /rs/website
		$path = $this->root.$_path; 
		$flag = time();
		$fileobj  = UploadFile::getInstanceByName($name); 
		
		$localPath = $path.$flag.$fileobj->getName();
		
		$uploaded = $fileobj->saveAs($localPath);
		
		if($uploaded){
			
			$uploadList = array(
					 //img是否已创建
					$localPath=>$this->rs_root.UPLOADROOT.$this->logoroot.'/'.$flag.$fileobj->getName()
			);
			
			//ftp上传
			if(parent::uploadFtp($uploadList))
			{
					$arr = array(
							'url'=> 'http://'.RS_HostName.$_path.$flag.$fileobj->getName()
					);
					//parent::delete_folder($localPath); //执行这一句报错，因为目录里文件rmdir只能删除空目录 
					exit(json_encode($arr));
			}
			return 0;
		}
		
		return 0;
	}
	
	
	/*
	 * 上传入口
	 */
	public function actionUpload($name){
		$this->_upload($name);
	}
	
	/*
	 *  遍历数组2
	 */
	public function imp($tree, $children='childs') {
		$imparr = array();
		foreach($tree as $w) {
			if(isset($w[$children])) {
				$t = $w[$children];
				unset($w[$children]);
				$w['isLeaf'] = 'false';
				$imparr[] = $w;
				if(is_array($t)) $imparr = array_merge($imparr, $this->imp($t, $children));
			} else {
				$imparr[] = $w;
			}
		}
		return $imparr;
	}
	
	
	/*
	 * 遍历树数组1
	*/
	public function build_tree($root_id){
		$rows = $this->rows;
		$childs=$this->findChild($rows,$root_id);
		if(empty($childs)){
			return null;
		}
		foreach ($childs as $k => $v){
			$rescurTree=$this->build_tree($v['category_id']);
			if( null !=   $rescurTree){
				$childs[$k]['childs']=$rescurTree;
			}
		}
		return $childs;
	}
	
	
	/*
	 * 查找子数组
	*/
	public function findChild(&$arr,$id){
		$childs=array();
		foreach ($arr as $k => $v){
			if($v['parent']== $id){
				$childs[]=$v;
			}
		}
		return $childs;
	}
	
	
	
	/*
	 * 二次遍历3
	*/
	public function build_tree2($array){
		$b = array();
		array_walk($array, $f = function($entry) use (&$b, &$f) {
			if (is_array($entry) && !empty($entry)) {
				if (isset($entry['category_id'])) {
					$tmp = $entry;
					unset($tmp["childs"]);
					$b[] = $tmp;
					array_walk($entry, $f);
				}
			}
		});
		return $b;
	}
	
}