<?php
namespace sns\controllers;

use Sky\base\Controller;
use sns\models\SnsCollectModel;
use sns\models\SnsPraiseModel;
use sns\models\SnsShareModel;
use sns\models\SnsCategoryListModel;
use sns\models\SnsSelfPublishModel;
use res\models\VideoModel;
use sns\models\SnsCommentModel;
use base\user\models\UserModel;
use sns\models\SnsUserRecommendTopModel;
use res\controllers\ResController;
use sns\models\SnsPlaySourceModel;
use res\models\ResourceQueryForSnsModel;
use base\components\SkySession;
use base\models\UnlawfulWordsModel;
use Sky\utils\VarDump;

defined('SHOW_TYPE_DEFAULT') or define('SHOW_TYPE_DEFAULT', 1);
defined('SHOW_TYPE_WATCHING_NOW') or define('SHOW_TYPE_WATCHING_NOW', "");
defined('SHOW_TYPE_PRIVATE') or define('SHOW_TYPE_PRIVATE', 3);

defined('SHOW_FLGA_DEFAULT') or define('SHOW_FLGA_DEFAULT', 2);
defined('SHOW_FLGA_PASS') or define('SHOW_FLGA_PASS', 0);

//天赐OS Id
defined('TIANCI_OS_ADMIN_ID') or define('TIANCI_OS_ADMIN_ID', 111183188);
defined('REPORT_SERVICE_URI') or define('REPORT_SERVICE_URI', 'http://121.199.33.20:40025/ReportService/UserActivity');

class SnsActionController extends ResController {

	public function actions(){
		return array(
				"Wsdl"=>array(
						"class"=>"Sky\\base\\WebServiceAction"
				),
		);
	}

	/**
	 * 获取广场分类列表
	 * @return Ambigous <\sns\models\multitype:, multitype:>
	 */
	public function actionGetListType(){
		$list = SnsCategoryListModel::showCategoryList();
		$result = array();
		foreach ($list as $key => $value) {
			$listType = new \stdClass();
			$listType->id = $value['category_id'];
			$listType->name = $value['category_name'];
			$listType->classify = $value['category_type'];
			$result[] = $listType;
		}
		return $result;
	}

	/**
	 * 重新装载数据
	 * @param unknown $list
	 * @return multitype:\stdClass
	 */
	private function installData($list, $classify){

		$result = array();
		$finalResult = array();
		$nameArray = array();

		foreach ($list as $key => $value) {
			$sns = new \stdClass();
			$sns->id = $value['publish_id'];
			$sns->sourceId = $value['source_id'];
			$sns->sourceType =  $value['source_type'];
			$sns->url = $value['url'];
			$sns->title = $value['title'];
			$sns->content = $value['content'];
			$sns->logo = $value['logo_s'];
			$sns->flag = $value['publish_flag'];
			$sns->shareCount = $value['sharecount'];
			$sns->collectCount = $value['collectcount'];
			$sns->commentCount = $value['commentcount'];
			$sns->praiseCount = $value['praisecount'];
			$sns->stepCount = $value['stepcount'];

			//评论查询
			$commentModel = SnsCommentModel::queryComment($sns->id, "");
			$commentUsername = "天赐用户";
			$commentStr = "影片不错赞一个！";
				
			if(!empty($commentModel)){
				$commentDetail = SnsCommentModel::queryCommentDetailByCid($commentModel[0]['comment_id'], "");
				if(count($commentDetail) > 0){
					$commentUsername = $commentDetail[0]['user_name'];
					$commentStr = $commentDetail[0]['comment_content'];
				}
			}

			if($classify == 'tianciOS'){
				$commentUsername = "天赐OS";
				$commentStr = "一起来感受吧！！！";
			}
				
			if($classify == 'watchingNow'){
				$commentUsername = "同时在看";
				$commentStr = "一起来感受吧！！！";
			}
				
			$sns->firstComment = $commentStr;
			$sns->userName = $commentUsername;
			$result[] = $sns;
			$nameArray[] = $value['publish_id']."_".$value['title'];
		}

		if(count($result) > 0){
			$startTime = time() - 86400*10;
			$endTime = time();
			$playNowArray = self::getPlayCount($nameArray, $startTime,$endTime);
			$playReadArray = self::getPlayCount($nameArray);

			if(count($playNowArray) > 0){
				foreach ($playNowArray as $key => $value) {
					foreach ($result as $snsDomains => $snsDomain){
						if($value->id == $snsDomain->id){
							$snsDomain->playNowCount = $value->count;
							$result[$snsDomains] = $snsDomain;
						}else if(!isset($snsDomain->playNowCount)){
							$snsDomain->playNowCount = 0;
						}
					}
				}
			}

			if(count($playReadArray) > 0){
				foreach ($playReadArray as $key => $value) {
					foreach ($result as $snsDomains => $snsDomain){
						if($value->id == $snsDomain->id){
							$snsDomain->playReadCount = $value->count;
							$result[$snsDomains] = $snsDomain;
						}else if(!isset($snsDomain->playReadCount)){
							$snsDomain->playReadCount = 0;
						}
					}
				}
			}
		}

		return $result;
	}

	/**
	 * 获取播放数量
	 * @param unknown $nameArray
	 * @param string $startTime
	 * @param string $endTime
	 * @return mixed
	 */
	private function getPlayCount($nameArray, $startTime='', $endTime=''){

		$url = urlencode(REPORT_SERVICE_URI).urlencode("?func=ResourceCount&params=media:shenzhen:$startTime:$endTime:");

		$name = join("-", $nameArray);
		$url = $url.urlencode($name);
		$callBackStr = self::curl_fetch(urldecode($url));
		$callBackArray = json_decode($callBackStr);

		return $callBackArray;
	}

	/**
	 * 根据类型获取列表
	 * @param unknown $classify
	 * @param unknown $pageSize
	 * @param unknown $pageNo
	 * @param string $condition
	 * @return Ambigous <\sns\controllers\multitype:\stdClass, multitype:\stdClass >
	 */
	public function actionGetSnsListByClassify($classify, $pageSize, $pageNo, $condition=""){

		$list = array();
		$start = $pageSize * $pageNo;
		$playtype = self::getTVPalyType();
		$userId = $condition;

		if($classify == 'hot'){
			$list =	SnsSelfPublishModel::listPublishByHotShare($start, $pageSize, SHOW_TYPE_DEFAULT, $playtype);
		}else if($classify == 'new'){
			$list =	SnsSelfPublishModel::listPublishByDate($start, $pageSize, SHOW_TYPE_DEFAULT, $playtype);
		}else if($classify == 'tianciOS'){
			$list = SnsSelfPublishModel::listPublishByShareUid(TIANCI_OS_ADMIN_ID, $start, $pageSize, SHOW_TYPE_DEFAULT);
		}else if($classify == 'watchingNow'){
			$list = self::actionGetSnsListByWatchingNow(20);
		}else if($classify == 'findUser' || $classify == 'userShare'){
			$list = SnsSelfPublishModel::listPublishByShareUid($userId, $start, $pageSize, SHOW_TYPE_DEFAULT);
		}else if($classify == 'userCollect'){
			$list = SnsSelfPublishModel::listPublishByCollectUid($userId, $start, $pageSize, SHOW_TYPE_DEFAULT);
		}

		$list = self::installData($list, $classify);

		return $list;
	}

	/**
	 * 根据用户收藏或者分享获取列表
	 * @param unknown $userId
	 * @param unknown $type
	 * @param unknown $pageSize
	 * @param unknown $pageNo
	 * @return Ambigous <\sns\controllers\multitype:\stdClass, \stdClass>
	 */
	public function actionGetSnsListByUser($userId, $type, $pageSize, $pageNo){

		$list = array();
		if($type == "share"){
			$list = SnsSelfPublishModel::listPublishByShareUid($userId, SHOW_TYPE_DEFAULT, $pageNo, $pageSize);
		}else if($type == "collect"){
			$list = SnsSelfPublishModel::listPublishByCollectUid($userId, SHOW_TYPE_PRIVATE, $pageNo, $pageSize);
		}

		$result = self::installData($list);
		return $result;
	}

	/**
	 * 获取正在观看的列表
	 * @param unknown $total 列表个数
	 * @return multitype:
	 */
	public function actionGetSnsListByWatchingNow($total){
			
		$videoType = '0001';
		$startTime = time() - 86400*60;
		$endTime = time();
		$url = urlencode(REPORT_SERVICE_URI).urlencode("?func=ResourceOnDemandStat&params=media:$startTime:$endTime:shenzhen:$total");
		$returnData = self::curl_fetch(urldecode($url));
		$returnData = json_decode($returnData);
		$snsArray = array();
		$queryVideoNames = array();
		$sqlArray = array();

		if(count($returnData) > 0){
			foreach ($returnData as $key => $value) {
				$videoName = $value->rs_name;
				$queryVideoNames[] = "'".$videoName."'";
			}
		}

		$queryVideoNames = join(",", $queryVideoNames);

		$videoIdArray = SnsSelfPublishModel::getListVideoIdByName($queryVideoNames);

		$queryVideoIds = array();
		$result = array();

		if(count($videoIdArray) > 1){
			foreach ($videoIdArray as $key => $value) {
				$queryVideoIds[] = $value['v_id'];
				$videoSource = parent::showSource($videoType, $value['v_id']);
				if(count($videoSource) > 0){
					$sqlArray[$value['v_id']] = $videoSource[0]['url'];
				}
			}

			$queryVideoIds = join(",", $queryVideoIds);
			$flag = SnsSelfPublishModel::InsertPublishByVideoId($videoType, SHOW_TYPE_WATCHING_NOW, $queryVideoIds);
			$result = SnsSelfPublishModel::getPublishListByVid(0, $total, $videoType, SHOW_TYPE_WATCHING_NOW, $queryVideoIds);
		}

		/**
		 $sql = "";

		 foreach ($result as $kk=>$vv){
			foreach($sqlArray as $k=>$v){
			if($vv['source_id'] == $k){
			$snsId = $vv['publish_id'];
			if ($sql){
			$sql .= ",($snsId,'$v',0)";
			}else {
			$sql .= "($snsId,'$v',0)";
			}
			}
			}
			}
			SnsPlaySourceModel::insertPlaySourceByArray($sql);
		 */
		return $result;
	}

	/**
	 * 远程调用方法
	 * @param unknown $url
	 * @param number $timeout
	 * @return Ambigous <boolean, mixed>
	 */
	private function curl_fetch($url, $timeout=3){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		$errno = curl_errno($ch);
		if ($errno>0) {
			$data = false;
		}
		curl_close($ch);
		return $data;
	}

	/**
	 * 添加一个用户收藏
	 * @param unknown $snsId
	 * @param unknown $userId
	 * @return Ambigous <number, string>
	 */

	public function actionAddSnsCollect($snsId, $userId){

		$collectModel = SnsCollectModel::queryCollect($snsId, "");
		if(empty($collectModel)){
			$collectId = SnsCollectModel::insertCollect($snsId);
		}else{
			$collectId = $collectModel[0]["collect_id"];
			SnsCollectModel::updateCollect($collectId);
		}

		$allUserCollectModel = SnsCollectModel::queryCollectDetailByUidNoFlag($userId, "");

		if(count($allUserCollectModel) > 0){
			foreach ($allUserCollectModel as $k => $v){
				if($collectId == $v['collect_id']){
					//flag 改为1 视为删除
					return SnsCollectModel::updateCollectDetail($v['collect_detail_id'], 0);
				}
			}
		}
		
		return SnsCollectModel::insertCollectDetail($collectId, $userId);
	}

	/**
	 * 添加用户分享
	 * @param unknown $snsId
	 * @param unknown $userId
	 * @param string $comment
	 * @return Ambigous <number, string>
	 */
	private function actionAddSnsShare($snsId, $userId, $comment=""){

		$shareModel = SnsShareModel::queryShare($snsId, "");

		if(empty($shareModel)){
			$shareId = SnsShareModel::insertShare($snsId);
		}else{
			$shareId = $shareModel[0]["share_id"];
			SnsShareModel::updateshare($shareId);
			SnsSelfPublishModel::updatePublish($snsId, SHOW_TYPE_DEFAULT);
		}

		return SnsShareModel::insertShareDetail($shareId, $userId, $comment);
	}

	/**
	 * 添加用户评论
	 * @param unknown $snsId
	 * @param unknown $userId
	 * @param string $comment
	 * @return Ambigous <number, string>
	 */
	private function actionAddSnsComment($snsId, $userId, $userName, $comment=""){

		$commentModel = SnsCommentModel::queryComment($snsId, "");

		if(empty($commentModel)){
			$commentId = SnsCommentModel::insertComment($snsId);
		}else{
			$commentId = $commentModel[0]["comment_id"];
			SnsCommentModel::updateComment($commentId);
		}

		return SnsCommentModel::insertCommentDetail($commentId, $userId, $userName, $comment);
	}

	/**
	 * 用户自己分享资源
	 * @param unknown $snsJsonStr
	 * @param unknown $userId
	 * @return number|Ambigous <\sns\controllers\Ambigous, number, string>
	 */
	public function actionAddSnsShareByUserSelf($snsJsonStr, $userId){

		$userSendSns = json_decode($snsJsonStr);

		if(isset($userSendSns->sourceType) && $userSendSns->sourceType == 0){
			return -2;
		}

		$snsId = 0;

		if(isset($userSendSns->sourceId) && $userSendSns->sourceId != 0){
			$snsModel = SnsSelfPublishModel::getPublishCountBySid($userSendSns->sourceId, $userSendSns->sourceType);
		}else{
			$snsModel = SnsSelfPublishModel::getPublishCountByUrl($userSendSns->url, $userSendSns->sourceType);
		}

		if(empty($snsModel)){
			$content = "";
			if(isset($userSendSns->content)){
				$content = $userSendSns->content;
			}

			$sourceId = "NULL";
			if(isset($userSendSns->sourceId) && $userSendSns->sourceId != 0){
				$sourceId = $userSendSns->sourceId;
			}

			$snsId = SnsSelfPublishModel::insertPublishByUser($sourceId, $userSendSns->sourceType,
					$userId, $userSendSns->url, $userSendSns->title, $content,
					"", $userSendSns->logo, SHOW_FLGA_PASS, SHOW_TYPE_DEFAULT);
		}else{
			$snsId = $snsModel[0]['publish_id'];
		}

		if($snsId > 0){
			if(isset($userSendSns->action) && strlen($userSendSns->action) > 1){
				$psList = SnsPlaySourceModel::getPlaySourceByPublishId($snsId, $userSendSns->actionType);
				if(count($psList) < 1){
					$spsResult = SnsPlaySourceModel::insertPlaySource($snsId, $userSendSns->action, $userSendSns->actionType);
				}
			}

			$userName = "";
			if(isset($userSendSns->userName)){
				$userName = $userSendSns->userName;
			}

			$commentStr = "影片不错赞一个！";
			if(isset($userSendSns->firstComment) && strlen($userSendSns->firstComment) > 0){
				$commentStr = $userSendSns->firstComment;
			}
				
			self::actionAddSnsComment($snsId, $userId, $userName, $commentStr);
			return self::actionAddSnsShare($snsId, $userId, $commentStr);
		}else{
			return -1;
		}
	}

	/**
	 * 踩顶资源更新数量插入Log
	 * @param unknown $snsId
	 * @param unknown $userId
	 * @param unknown $detailType
	 * @return number
	 */
	public function actionAddSnsPraise($snsId, $userId, $detailType){

		$praiseModel = SnsPraiseModel::queryPraise($snsId, "");
		if(empty($praiseModel)){
			$pariseId = SnsPraiseModel::insertPraise($snsId, $detailType);
		}else{
			$pariseId = $praiseModel[0]["praise_id"];
		}

		$pariseDetailModel = SnsPraiseModel::queryPraiseDetailByUid($userId, "");
		if(empty($pariseDetailModel)){
			$praiseDetailId = SnsPraiseModel::insertPraiseDetail($pariseId, $userId);
		}else{
			$praiseDetailId = $pariseDetailModel[0]["praise_detail_id"];
		}

		if($detailType == 1){
			SnsPraiseModel::updatepraise($pariseId,0,1);
		}else{
			SnsPraiseModel::updatepraise($pariseId,1,0);
		}

		return	SnsPraiseModel::updatePraiseDetail($praiseDetailId, $detailType);
	}

	/**
	 * 获取查询用户的列表
	 * @param unknown $userName
	 * @return multitype:\stdClass
	 */
	public function actionQuerySnsUserByName($userName){

		$userArray = UserModel::getUserIdByNickName($userName);

		if(is_numeric($userName)){
			$userArray = UserModel::getUserInfo($userName);
		}
		
		$result = array();
		$snsUserArray = array();
		$userIds = array();
	
		if(count($userArray) > 0){
			foreach ($userArray as $key => $value) {
				$userIds[] = $value['userId'];
			}

			$userIds = join(',',$userIds);
			$result = self::getUserDomainData($userArray, $userIds);
		}
		
		return $result;
	}

	/**
	 * 获取分享最多的用户列表
	 * @return Ambigous <multitype:, multitype:\stdClass >
	 */
	public function actionGetTopShareUserList(){
		//缓存
		$userIdList = SnsUserRecommendTopModel::getUserIdByType(1);
		$userIds = array();
		$result = array();
		$userArray = array();

		foreach ($userIdList as $key => $userId){
			$userIds[] = $userId['user_id'];
			$userModel = UserModel::getUserInfo($userId['user_id']);
			if(count($userModel) > 0){
				$userArray[] = $userModel[0];
			}
		}

		$userIds = join(',',$userIds);

		if(count($userArray) > 0){
			$result = self::getUserDomainData($userArray, $userIds);
		}

		return $result;
	}

	/**
	 * 拼装用户对象
	 * @param unknown $userArray
	 * @param unknown $userIds
	 * @return multitype:\stdClass
	 */
	private function getUserDomainData($userArray, $userIds){

		$result = array();
		$userCollects = SnsCollectModel::getCollectTotalByUid($userIds);
		$userShares = SnsShareModel::getShareTotalByUid($userIds);

		foreach ($userArray as $key => $value) {
			foreach ($userShares as $userShareKey => $userShareValue){
				if($value['userId'] == $userShareValue['user_id']){
					$snsUser = new \stdClass();
					$snsUser->userId = $value['userId'];
					$snsUser->userName = $value['userNickName'];
					$snsUser->userPicUrl = $value['userIcon'];

					$snsUser->userShareCount = $userShareValue['sharetotal'];
					foreach ($userCollects as $userCollectKey => $userCollectValue){
						if($value['userId'] == $userCollectValue['user_id']){
							$snsUser->userCollectCount = $userCollectValue['collecttotal'];
						}
					}
					$result[] = $snsUser;
				}
			}
		}
		return $result;
	}

	/**
	 *
	 * @param unknown $snsId
	 * @param unknown $userId
	 * @return number
	 */
	public function actionDelUserShare($snsId, $userId){

		$shareModel = SnsShareModel::queryshare($snsId, "");
		$allUserShareModel = SnsShareModel::queryShareDetailByUid($userId, "");

		foreach ($allUserShareModel as $k => $v){
			if(count($shareModel) > 0){
				if($shareModel[0]['share_id'] == $v['share_id']){
					//flag 改为1 视为删除
					return SnsShareModel::updateShareDetail($v['share_detail_id'], 1);
				}
			}
		}

		return -1;
	}

	/**
	 *
	 * @param unknown $snsId
	 * @param unknown $userId
	 * @return number
	 */
	public function actionDelUserCollect($snsId, $userId){

		$collectModel = SnsCollectModel::querycollect($snsId, "");
		$allUserCollectModel = SnsCollectModel::queryCollectDetailByUid($userId, "");

		foreach ($allUserCollectModel as $k => $v){
			if(count($collectModel) > 0){
				if($collectModel[0]['collect_id'] == $v['collect_id']){
					//flag 改为1 视为删除
					return SnsCollectModel::updateCollectDetail($v['collect_detail_id'], 1);
				}
			}else{
				return 0;
			}
		}

		return -1;
	}

	/**
	 *
	 * @return number
	 */
	private function getTVPalyType(){

		$playtype = 0;
		$sysCondition = $this->getPolicyValue('0001');
		if($sysCondition == 'qiyi'){
			$playtype = 1;
		}else if($sysCondition == 'youpeng'){
			$playtype = 2;
		}

		return $playtype;
	}

	/**
	 *
	 * @param unknown $snsId
	 * @return \stdClass
	 */
	public function actionGetSnsPlayUrl($snsId){

		$playtype = self::getTVPalyType();
		$playArray = SnsPlaySourceModel::getPlaySourceByPublishId($snsId, $playtype);
		$result = new \stdClass();
		foreach ($playArray as $k => $v){
			$result->actionStr = $v['play_action'];
		}
		return $result;
	}

	/**
	 * 公安校验字符串
	 * @param unknown $str
	 */
	public function actionCheckStrFromPolice($str){

		$count = UnlawfulWordsModel::getUnlawfulWordsCount($str);
		if($count > 0){
			return 0;
		}else{
			return 1;
		}
	}
}

