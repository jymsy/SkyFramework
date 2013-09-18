<?php
namespace advert\advertising\controllers;

use Sky\Sky;
use Sky\base\Controller;
use advert\advertising\models\AdvertModel; 
use skyosadmin\components\PolicyController;
use Sky\utils\Ftp;
use Sky\web\UploadFile;
use skyosadmin\components\Page;

class AdvertisingController extends PolicyController {
	/*
	 *  远程接口基本访问地址
	*/
	private $remote_url = "";
	/*
	 *  apk包本地目录
	*/
	private $apkroot = "";
	/*
	 *  apk 图标本地目录
	*/
	private $iconroot = "";
	
	/*
	 *  上传主目录
	*/
	private $uploadroot = "";
	
	/*
	 *  网站根目录,php脚本主目录
	*/
	private $root = "";
	
	/*
	 * 上传压缩包目录
	*/
	private $ziproot = "";
	
	/*
	 * 上传网站目录 
	*/
	private $websiteDir = "/website";
	
	/*
	 * 远程ftp 主目录
	*/
	private $rs_root = "";
	
	
	
	private $request = array();
	 
	public function beforeAction($action){
		$this->request = parent::getActionParams(); 
		$this->root = ROOT; //结果= /data/cloudservice
		$this->ziproot = UPLOADROOT.ZIPROOT; //结果 = /rs/zip
		$this->websiteDir = UPLOADROOT.$this->websiteDir;  // 结果 = /rs/website
		$this->rs_root = RS_ROOT; // 结果 = /data/www
	
		$sidx = $request['sidx']; //字段名
		!isset($this->request['sidx']) ? $this->request['sidx'] = 1:$this->request['sidx'] = $this->request['sidx'];
		return true;
	}
	
	
	/**
	 * 列表、查询 入口
	 * @return multitype:NULL multitype:
	 */
	public function actionADList($table){
		$name = isset($this->request['name'])?$this->request['name']:''; 
		$type = isset($this->request['type'])?$this->request['type']:''; 
		$scene = isset($this->request['scene'])?$this->request['scene']:''; 
		$position = isset($this->request['position'])?$this->request['position']:'';
		$order = array(
				$this->request['sidx']=>$this->request['sord']
		);
	    if($table=='advert'){
			$pager = new Page(AdvertModel::searchAdsCount($name,$type,$scene,$position,null));
			$pager->prePage();
			$res = AdvertModel::searchAds($name,$type,$scene,$position,$pager->start,$pager->limit,null,$order); 
		}elseif($table=='advert_pos'){
			$pager = new Page(AdvertModel::getSearchAdsPositionCount($scene,$position));
			$pager->prePage();
			$res = AdvertModel::getSearchAdsPosition($scene,$position,$pager->start,$pager->limit,$order); 
		}
		
		$arr = array(
				"records"=>$pager->count, //总条数
				"rows"=>$res,
				"total"=>$pager->total_pages, //总页数
				"page"=>$pager->page
		); 
		return  $arr;
	}
	
	/*
	 *  获取 场景下拉列表
	 */
	public function actionGetScene(){
		$arr =  AdvertModel::getAdsPositionScene();
		$data = array();
		foreach($arr as $key=>$value){
			$data[$value['scene']] = $value['scene'];
		}
		return $data;
	}
	/*
	 *  获取关联广告
	 *  
	 */
    public function actionGetAds($id){
    	$arr =  AdvertModel::getAds($id);
    	return $arr[0];
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
	
   //增加记录
   public function add($arr){
   	    if($arr['table']=='advert'){
			$rec = AdvertModel::addAds(
					$arr['name'],
					$arr['type'],
					$arr['url'],
					$arr['flag']
			);
		}elseif($arr['table']=='advert_pos'){
			$rec =  AdvertModel::addAdsPosition(
					$arr['scene'],
					$arr['position'],
					$arr['ad_id']
			);
		}
		return $rec;
   }
  
     //编辑表 
	public function edit($arr){
		if($arr['table']=='advert'){
			$rec = AdvertModel::alterAds(
					$arr['id'],
					$arr['name'],
					$arr['type'],
					$arr['url'],
					null //空表示不更新
			);
		}elseif($arr['table']=='advert_pos'){
			$rec =  AdvertModel::alterAdsPosition(
					$arr['id'],
					$arr['scene'], 
					$arr['position'],
					$arr['ad_id']
			);
		}
		return $rec; 
	}
	
 
	public function del($arr){
		if($arr['table']=='advert'){
			 //删除广告
			$rec = AdvertModel::deleteAds($arr['id']);
		}else if($arr['table']=='advert_pos'){
			 //删除广告位
			$rec =  AdvertModel::deleteAdsPosition($arr['id']);
		} 
		return $rec;	//成功>0，失败0 
	}
	
	
	//上下架
	public function actionADSale($oper,$id){
		if(isset($id)){
			if($oper=='offsale'){
				return	AdvertModel::alterAds($id,"","","","stop");
			}elseif ($oper=='onsale'){
				return	AdvertModel::alterAds($id,"","","","using");
			}
		}
		//没有设置id
		return NULL;
	}
	
 
	/*
	 *   上传方法
	 */
	public function actionUpload($file){
		header('Content-Type: text/html;charset=utf-8');
		$obj  = UploadFile::getInstanceByName($file); 
		$extName = $obj->getExtensionName();
		if($extName=='zip'){
			$this->uploadHtml($obj); //因为有退出
		}elseif($extName=='jpg' || $extName=='jpeg' || $extName=='png'){
			$this->uploadPic($obj); ////因为有退出
		}else{
			Sky::$app->end(json_encode(array(
					'msg'=>'文件不支持！',
					'status'=>0
			)));
		}
	}
	
	/*
	 *  上传html页包
	*/
	public function uploadHtml($fileobj){
		$id = time();
		$zipPath = ''; //说明页包的位置
		//上传
		$_path = $this->websiteDir.'/';
		$subDir = date('Y-m').'/'.$id.'/';
		$localPath = $this->root.$_path.$subDir; //包解压目录
			
		//创建目录
		if(!is_dir($localPath)){
			parent::RecursiveMkdir($localPath,0777);
		}
		
		$zipPath = $localPath.$fileobj->getName();
		$uploaded = $fileobj->saveAs($zipPath);
		if($uploaded){
			//解压到path
			$zip = new \ZipArchive;
			if ($zip->open($zipPath) === TRUE) {
				$zip->extractTo($localPath);
				$zip->close();
				unlink($zipPath);
			} else {
				
				Sky::$app->end(json_encode(array(
						'msg'=>'Zip extract failed!',
						'status'=>0
				)));
	
			}
			//FTP上传path目录
			$uploadList = array(
					$localPath=>$this->rs_root.$this->websiteDir.'/'.$id.'/',
			);
				
		 
			$ftped = parent::uploadFtp($uploadList);
		 
			//ftp上传
			if($ftped)
			{
				//返回成功上传的url广告页地址 
				Sky::$app->end(json_encode(array(
						'msg'=>'http://'.RS_HostName.'/'.$this->websiteDir.'/'.$id.'/',
						'status'=>1
				))); 
			}
		}
	 
	
		Sky::$app->end(json_encode(array(
				'msg'=>'上传失败',
				'status'=>0
		)));
	}
	
	/*
	 *  上传图片pic
	 */
	public function  uploadPic($fileobj){
		
			$_path = $this->websiteDir.'/'; //  /rs/website
			$path = $this->root.$_path; //  /data/cloudservice/rs
			
		 
			$flag = time();
		  
			$localPath = $path.$flag.$fileobj->getName();
		
			$uploaded = $fileobj->saveAs($localPath);
		
			if($uploaded){
					
				$uploadList = array(
						//img是否已创建
						$localPath=>$this->rs_root.$this->websiteDir.'/'.$flag.$fileobj->getName()
				);
				 
				//ftp上传
				if(parent::uploadFtp($uploadList))
				{
					Sky::$app->end(json_encode(array(
							'msg'=> 'http://'.RS_HostName.$_path.$flag.$fileobj->getName(),
							'status'=>1
					)));
				}
				 
	
		          Sky::$app->end(json_encode(array(
				      'msg'=>'上传失败',
				       'status'=>0
		          )));
			}
			
			 Sky::$app->end(json_encode(array(
				      'msg'=>'上传失败',
				       'status'=>0
		     )));
		 
	}
	 
}