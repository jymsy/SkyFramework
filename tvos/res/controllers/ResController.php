<?php
namespace res\controllers;

use Sky\base\Controller;
use base\components\PolicyController;
use res\models\VideoModel;
use res\models\AudioModel;
use res\models\CategoryModel;
use res\models\PromiseModel;
use res\models\WebModel;
use res\models\BroadCastModel;
use res\models\InfoModel;
use res\models\ChannelModel;
use res\models\EpgProgramModel;
use res\models\AlbumModel;

class ResController extends PolicyController {
	
	const InfoUrlPrefix = INFO_URL_PREFIX;
	
	public function actions() {
		return array(
				"Wsdl"=>array(
						"class"=>"Sky\\base\\WebServiceAction"
				),
		);
	}
	
	static $TopMenu = array(
			"0001"=>"影视",
			"0002"=>"音乐",
			"00021"=>"音乐专辑",
			"0003"=>"图片",
			"0004"=>"应用",
			"0005"=>"资讯",
			"0006"=>"EPG",
			"00061"=>"EPGChannel",
			"00062"=>"EPGProgram",
			"0009"=>"广播"
	);
	
	static $MediaType = array(
			"电影"=>"dy",
			"电视剧"=>"dsj",
			"动漫"=>"dm",
			"纪录片"=>"jlp",
			"生活"=>"sh",
			"综艺"=>"zy",
			"MV"=>"mv",
			"新闻"=>"xw",
			"短视频"=>"dsp",
			'体育'=>'ty',
			'娱乐'=>'yl',
			'搞笑'=>'gx',
			'教育'=>'jy',
			'旅游.纪录片'=>'lyjlp',
			'时尚.生活'=>'sssh',
			'片花'=>'ph',
			'音乐'=>'yy',
			'影视大厅'=>'ysdt'
	);
	
	static $siteNum = array(
			"funshion"=>10,
			"sina"=>10,
			"qiyi"=>10,
			"cntv"=>2,
			"tudou"=>1,
			"youku"=>1
	);
	
	public function loadMedia($Media) {
		global $sysCondition;
		for ($i=0;$i<count($Media);$i++) {
			if (!isset($Media[$i]['id'])) continue;
			$result = VideoModel::loadsite($Media[$i]['id'], $sysCondition);
			$arr = array();
			$arr['source'] = $result;
			$index = -1;
			$max = -1;
			for ($j=0;$j<count($result);$j++) {
				$resolution = $result[$j]['resolution'];
				$resolution_num = 5;
				if ($resolution != '') {
					$resolution_array = explode('*', $resolution);
					$resolution_num = intval($resolution_array[0]) * intval($resolution_array[1]);
				} else {
					if (isset($siteNum[$result[$j]['from']])) $resolution_num = $siteNum[$result[$j]['from']];
				}
				if ($resolution_num > $max) {
					$max = $resolution_num;
					$index = $j;
				}
			}
			if ($index > -1) {
				$arr['play_action'] = $result[$index]['play_action'];
				$arr['url'] = $result[$index]['url'];
				$arr['from'] = $result[$index]['from'];
				$arr['segment'] = $result[$index]['segment'];
				$arr['playurl'] = $result[$index]['playurl'];
				$arr['startPlayTime'] = $result[$index]['startPlayTime'];
				$arr['endPlayTime'] = $result[$index]['endPlayTime'];
				$arr['maxSegment'] = $result[$index]['maxSegment'];
				$arr['resolution'] = $result[$index]['resolution'];
			}
			$Media[$i] = array_merge($Media[$i], $arr);
		}
		return $Media;
	}
	
	public function getPromises($what) {
		return PromiseModel::listpromise($what, '');
	}
	
	public function insertPromises($what,$key,$value) {
		return PromiseModel::insertpromise($what, $key, $value);
	}
	
	public function listCategory($cid,$page,$pagesize) {
		global $sysCondition;
		$topc = self::$TopMenu;
		if (array_key_exists($cid, $topc)) {
			$ids = CategoryModel::showcategoryidbycname($topc[$cid], $sysCondition);
			if (isset($ids[0]) && isset($ids[0]['id'])) $id = $ids[0]['id'];
			else return array('total'=>0, 'result'=>array());
		} else {
			$id = $cid;
		}
		$count = CategoryModel::querycategorycount($id, $sysCondition);
		$result = CategoryModel::querycategorylist($id, $sysCondition, $page, $pagesize);
		return array('total'=>$count, 'result'=>$result);
	}
	
	public function showSource($topc,$sid,$fields='') {
		global $sysCondition;
		switch ($topc) {
			case '0001':
				$result = VideoModel::queryvideobyid($sid, '');
				if (isset($result[0])) {
					$result = self::loadMedia($result);
				}
				break;
			case '0002':
				$result = AudioModel::showAudio($sid,$sysCondition);
				break;
			case "0005":
				if (is_array($sid)) {
					$exist = strrpos($sid[0], "sub");
				} else {
					$exist = strrpos($sid, "sub");
				}
				if($exist === 0 || (isset($exist) && !empty($exist))){
					$sid = str_replace("sub_", "", $sid);
					$result = InfoModel::showNewsSubInfo(self::InfoUrlPrefix,$sid,$sysCondition);
				} else {
					if($fields != ""){
						$result = InfoModel::showNewsInfo(self::InfoUrlPrefix,$sid,$sysCondition);
					}else{
						$result = InfoModel::showNewsDetailInfo(self::InfoUrlPrefix,$sid,$sysCondition);
					}
				}
				break;
			case '00061':
				$result = ChannelModel::showepgchannel($sid,$sysCondition);
				break;
			case '00062':
				$result = EpgProgramModel::showepgprogram($sid, $sysCondition);
				break;
			case "0009":
				$result = BroadCastModel::showbroadcast(array($sid), '');//$bc->showBroadcast($sid);
				break;
			case "0010":
				$result = WebModel::showweb(array($sid), '');//$web->showWeb($sid);
				break;
			default:
				$result = array();
				break;
		}
		return $result;
	}
	
	public function listSource($topc,$condition,$page,$pagesize,$Union=1) {
		global $sysCondition;
		try {
			$condition = @get_object_vars(json_decode($condition));
		} catch (Exception $e) {
			$condition = array();
		}
		switch ($topc){
			case '0001':
				if (isset($condition['categoryid'])) {
					$cid = $condition['categoryid'];
					if ($cid == '10090') {
						$count = 100;
						if (intval($page)*intval($pagesize) >= 100) {
							$result = array();
						} else {
							$result = VideoModel::queryvideolist($sysCondition, $page, $pagesize);
						}
					} elseif($cid == '10091') {
						$count = VideoModel::querytopcount('latest', $sysCondition);
						$result = VideoModel::querytopvideolist('latest', $sysCondition, $page, $pagesize);
					} elseif($cid == '10092') {
						$count = VideoModel::querytopcount('superclear', $sysCondition);
						$result = VideoModel::querytopvideolist('superclear', $sysCondition, $page, $pagesize);
					} else {
						$cname = CategoryModel::queryacategoryname($cid, $sysCondition);
						$count = VideoModel::querysitecount($sysCondition, array('category'=>self::$MediaType[$cname]), $page, $pagesize);
						$result = VideoModel::queryvideodetail($sysCondition, array('category'=>self::$MediaType[$cname]), '', $page, $pagesize);
					}
				} else {
					if (isset($condition['sys_sort'])) {
						$sys_sort = $condition['sys_sort'];
					} else {
						$sys_sort = '';
					}
					$count = VideoModel::querysitecount($sysCondition, $condition, $page, $pagesize);
					$result = VideoModel::queryvideodetail($sysCondition, $condition, $sys_sort, $page, $pagesize);
				}
				$result = self::loadMedia($result);
				break;
			case '0002':
				if(!isset($condition['categoryid']) || $condition['categoryid'] =='') {
					$result = AudioModel::ListSourcesDetail($condition, $page, $pagesize,$sysCondition);
					$count = AudioModel::ListSourcesCount($condition, $page, $pagesize,$sysCondition);
				} else {
					if (isset($condition['listtype']) && $condition['listtype']=='FM')$isFM = 1;
					else $isFM=0;
					$new_condition = array();
					$new_condition['categoryid'] = $condition['categoryid'];
					$result = AudioModel::listAudioDetail($new_condition, $page, $pagesize, $isFM,$sysCondition);//$audio->listAudio($condition,$page,$pagesize);
					$count = AudioModel::listAudioCount($new_condition,$sysCondition); //$audio->listCount;
				}
				break;
			case "00021":
				$wheres = array();
				$v_singer = $condition['singer'];
				$result = AlbumModel::listsources($v_singer, $page, $pagesize, $sysCondition);//$album->ListSources($condition,$page,$pagesize,$Union);
				$count = AlbumModel::listsourcescount($v_singer ,$sysCondition);//$album->selectCount;
				break;
			case "0005":
				$count = 0;
				$result_promise = InfoModel::getPromise('info_category');
				$count = InfoModel::listInfoCount($result_promise,$condition,$sysCondition);
				$result = InfoModel::listInfoDetail(self::InfoUrlPrefix,$result_promise,$condition,$page,$pagesize,$sysCondition);
				break;
			case "00061":
				$count = 0;
				if (isset($condition['category_id'])) {
					$cid = $condition['category_id'];
					$count = ChannelModel::listepgchannelcount($sysCondition,$cid);
					$result = ChannelModel::listepgchannel($page, $pagesize,$sysCondition, $cid);
				}else{
					$count = ChannelModel::listepgchannelcount($sysCondition);
					$result = ChannelModel::listepgchannel($page, $pagesize,$sysCondition);
				}
				break;
			case "00062":
				$count = 0;
				$result = array();
				if (isset($condition['ch_id']) && isset($condition['begintime']) && isset($condition['endtime'])) {
					$count = EpgProgramModel::listepgprogramcount($condition['ch_id'],$sysCondition, $condition['begintime'], $condition['endtime']);
					$result = EpgProgramModel::listepgprogram($condition['ch_id'],$sysCondition, $condition['begintime'], $condition['endtime'], $page, $pagesize);
				}
				break;
			case "0009":
				$count = 0;
				$result = array();
				if (isset($condition['categoryid'])) {
					$cid = $condition['categoryid'];
					$count = BroadCastModel::listbroadcastcount($cid);//$bc->listBroadcastCount($cid);
					$result = BroadCastModel::listbroadcast($cid, $page, $pagesize);//$bc->listBroadcast($cid, $page, $pagesize);
				}
				break;
			case "0010":
				$v_category_id = array();
				if (count($condition)>0){
					foreach ($condition as $k=>$v){
						if ($v != ''){
							if ($k == 'category')
								array_push($v_category_id, $v);
							if ($k == 'categoryid')
								array_push($v_category_id, '10004');
						}
					}	
				}
				$count = WebModel::listwebcount($v_category_id);//$web->listWebCount($condition);
				$result = WebModel::listweb($v_category_id, $page, $pagesize);//$web->listWeb($page,$pagesize,$condition);
				break;
			default:
				$count = 0;
				$result = array();
				break;
		}
		return array('total'=>$count, 'result'=>$result);
	}
	
	/*
	 * 旧版本保留
	 */
	public function listSegments($topc,$sid,$page,$pagesize) {
		global $sysCondition;
		switch ($topc) {
			case "0001":
				$sites = VideoModel::queryvideosite($sid, $sysCondition);
				foreach ($sites as $row) {
					$vs_id = $row['id'];
					$thumb = $row['thumb'];
					break;
				}
				if (isset($vs_id)) {
					$result = VideoModel::queryvideourl($vs_id, '', $page, $pagesize);
					for ($i=0;$i<count($result);$i++) {
						$result[$i] = array_merge($result[$i],array('thumb'=>$thumb));
					}
				} else {
					$result = array();
				}
				break;
			case "0005":
				break;
			default:
				$result = array();
				break;
		}
		return $result;
	}
	
	public function listSegmentsBySource($topc,$vsid,$page,$pagesize) {
// 		global $sysCondition;
		switch ($topc) {
			case "0001":
				$result = VideoModel::queryvideourl($vsid, '', $page, $pagesize);
				break;
			case "0005":
				break;
			default:
				$result = array();
				break;
		}
		return $result;
	}
	
}