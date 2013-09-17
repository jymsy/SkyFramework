<?php
namespace tvos\autorun;

use tvos\autorun\components\pinyin_table;


class pinyin extends pinyin_table{
	
/**
 * $title= GetPys($str,false,'');
 * 汉字转拼音，可以是单个汉字转换，也可整句转换
 * @param $string 需要转换的汉字
 * @param $isGetFirstChar 是否生成首字母或者是全拼,true为生成首字母;false为全拼。默认为true
 * @param $delimiter 每个拼音直接的分隔符，默认不加分隔符
 * @return array
 */	
function GetPys($string,$isGetFirstChar=true,$delimiter='')
{
	$enc = mb_detect_encoding($string);
	$string = mb_convert_encoding($string, "gb2312",$enc);
	$pinyin_num = array();
	if ($this->IsNumInString($string)){
		$string_num = $this->NumToNum($string);
		$string_num = strtoupper($string_num);
		$flow_num = $this->StringToSinglePinyinArray($string_num);
		$pinyin_num = $this->SinglePinyinArrayToPinyinArray($flow_num);
	}
	$string = $this->NumToUpper($string);
	$string = strtoupper($string);
	
	$flow = $this->StringToSinglePinyinArray($string);
	
	//print_r($flow);
	$pinyin = $this->SinglePinyinArrayToPinyinArray($flow,$isGetFirstChar,$delimiter);
	$result = array_merge($pinyin,$pinyin_num);
	return $result;
	
}

function StringToSinglePinyinArray($string){
	$pinyin_table=$this->pinyin_table;
	$flow = array();
	for ($i=0;$i<strlen($string);$i++)
	{
		if (ord($string[$i]) >= 0x81 and ord($string[$i]) <= 0xfe)
		{
			$h = ord($string[$i]);
			if (isset($string[$i+1]))
			{
				$i++;
				$l = ord($string[$i]);
				if (isset($pinyin_table[$h][$l]))
				{
					array_push($flow,$pinyin_table[$h][$l]);
				}
				//else
				//{
				//array_push($flow,$h);
				//array_push($flow,$l);
				//}
			}
			//else
			//{
			//array_push($flow,ord($string[$i]));
			//}
		}
		else
		{
			if(!$this->IsChinese($string[$i]))
			array_push($flow,ord($string[$i]));
		}
	}
	return $flow;
}

function SinglePinyinArrayToPinyinArray($flow,$isGetFirstChar=true,$delimiter=''){
	$pinyin = array();
	$pinyin[0] = '';
	for ($i=0;$i<sizeof($flow);$i++)
	{
		if (is_array($flow[$i]))
		{
			if (sizeof($flow[$i]) == 1)
			{
				foreach ($pinyin as $key => $value)
				{
					//$pinyin[$key] .= ucwords($flow[$i][0][0]);
					if($isGetFirstChar){
						$pinyin[$key] .= ucwords($flow[$i][0][0]).$delimiter;
					}else{
						$pinyin[$key] .= ucwords($flow[$i][0]).$delimiter;
					}
				}
			}
			if (sizeof($flow[$i]) > 1)
			{
				$tmp1 = $pinyin;
				foreach ($pinyin as $key => $value)
				{
					//$pinyin[$key] .= ucwords($flow[$i][0][0]);
					if($isGetFirstChar){
						$pinyin[$key] .= ucwords($flow[$i][0][0]).$delimiter;
					}else{
						$pinyin[$key] .= ucwords($flow[$i][0]).$delimiter;
					}
				}
				for ($j=1;$j<sizeof($flow[$i]);$j++)
				{
					$tmp2 = $tmp1;
					for ($k=0;$k<sizeof($tmp2);$k++)
					{
						//$tmp2[$k] .= ucwords($flow[$i][$j][0]);
						if($isGetFirstChar){
							$tmp2[$k] .= ucwords($flow[$i][$j][0]).$delimiter;
						}else{
							$tmp2[$k] .= ucwords($flow[$i][$j]).$delimiter;
						}
					}
					array_splice($pinyin,sizeof($pinyin),0,$tmp2);
				}
			}
		}
		else
		{
			foreach ($pinyin as $key => $value)
			{
				$pinyin[$key] .= chr($flow[$i]).$delimiter;
			}
		}
	}
	return $pinyin;
}

function NumToNum($source) {
	//全角数字 ０１２３４５６７８９
	$source = str_replace('０', '0', $source);
	$source = str_replace('１', '1', $source);
	$source = str_replace('２', '2', $source);
	$source = str_replace('３', '3', $source);
	$source = str_replace('４', '4', $source);
	$source = str_replace('５', '5', $source);
	$source = str_replace('６', '6', $source);
	$source = str_replace('７', '7', $source);
	$source = str_replace('８', '8', $source);
	$source = str_replace('９', '9', $source);
	return $source;
}

function NumToUpper($source) {
	//半角数字 0123456789
	$source = str_replace('0', 'L', $source);
	$source = str_replace('1', 'Y', $source);
	$source = str_replace('2', 'E', $source);
	$source = str_replace('3', 'S', $source);
	$source = str_replace('4', 'S', $source);
	$source = str_replace('5', 'W', $source);
	$source = str_replace('6', 'L', $source);
	$source = str_replace('7', 'Q', $source);
	$source = str_replace('8', 'B', $source);
	$source = str_replace('9', 'J', $source);
	//全角数字 ０１２３４５６７８９
	$source = str_replace('０', 'L', $source);
	$source = str_replace('１', 'Y', $source);
	$source = str_replace('２', 'E', $source);
	$source = str_replace('３', 'S', $source);
	$source = str_replace('４', 'S', $source);
	$source = str_replace('５', 'W', $source);
	$source = str_replace('６', 'L', $source);
	$source = str_replace('７', 'Q', $source);
	$source = str_replace('８', 'B', $source);
	$source = str_replace('９', 'J', $source);
	return $source;
}

function IsChinese($char)
{
	if(preg_match("/^[a-z\d]*$/i", $char))  //如果是字母或数字
	{
		return false;
	}
	return true;
}

function IsNumInString($string){
	return preg_match("/[\d]+/", $string);
}

}

