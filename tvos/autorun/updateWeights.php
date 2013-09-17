<?php
namespace tvos\autorun;

use tvos\autorun\models\ResUpdateModel;

class updateWeights {
	
	/*
	 * 单位:分钟
	 */
	static $categoryTimelong = array(
			'dy'=>'45',
			'dsj'=>'25',
			'dm'=>'15'
	);
	
	function run() {
		$pagesize = 1000;
		$count = self::getNoSequenceCount();
		$page = intval(ceil($count/$pagesize));
		
// 		echo "count:$count, page:$page \n";
// 		$page = 1;
		
		for ($i=0;$i<$page;$i++) {
			$rs = self::getNoSequence($i, $pagesize);
			foreach ($rs as $r) {
				$sequence = 1;
				/*
				 * 单位:秒
				*/
				$timelong = $r['run_time'];
				$category = $r['category'];
				if (isset(self::$categoryTimelong[$category])) {
					$minuts = self::$categoryTimelong[$category];
					if (intval($timelong) > intval($minuts)*60) {
						$sequence += 5;
					}
				}
				$width = intval($r['width']);
				$height = intval($r['height']);
				if ($width < 600) {
					$sequence += 0;
				} elseif ($width > 1000) {
					$sequence += 2;
				} else {
					$sequence += 1;
				}
				
// 				echo "category: $category, sequence: $sequence, timelong: $timelong, width: $width \n";
				self::updateSequence($sequence, $r['vs_id']);
			}
		}
	}
	
	private function updateSequence($sequence, $vs_id) {
		ResUpdateModel::updateSiteSequenceByVsid($vs_id, $sequence);
	}
	
	private function getNoSequenceCount() {
		return ResUpdateModel::getSiteWeightCount();
	}
	
	private function getNoSequence($page,$pagesize) {
		$start = $page*$pagesize;
		return ResUpdateModel::getSiteWeightList($start, $pagesize);
	}
	
}