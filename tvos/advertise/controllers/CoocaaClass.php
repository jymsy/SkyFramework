<?php
namespace advertise\controllers;

abstract class CoocaaClass{
	protected static $coocaaCenter;
	
	protected static function getSession($coocaaId){
		if (!isset(self::$coocaaCenter)){					
			self::$coocaaCenter = new CoocaaWebservice("http://center.coocaatv.com/WebServiceEP.ashx", "http://open.coocaatv.com/FrameworkService");
		}
		$params = array("Input"=>"<data><coocaaid>$coocaaId</coocaaid></data>");
		$session = self::$coocaaCenter->callFunc("GetSessionID", $params)->toXML();
		return $session;
	}
	
	protected static function callCenter($params){
		if (!isset(self::$coocaaCenter)){
			self::$coocaaCenter = new CoocaaWebservice("http://center.coocaatv.com/WebServiceEP.ashx", "http://open.coocaatv.com/FrameworkService");
		}
		return self::$coocaaCenter->callFunc("GetRulesAndServicesXml", $params);
	}
	
	protected function callFunc($funcName,$functionSets,$header,$params){
		if (!array_key_exists($funcName, $functionSets)){
			//Turn on when debug
			//throw new Exception("$funcName doesn't exists in ".get_class($this)." functionSets, which is { ".implode(", ", array_keys($functionSets))." }. ", 11000);
			return CoocaaResponse::null();
		}
		$cc = new CoocaaWebservice($functionSets[$funcName]['serviceUrl'], $functionSets[$funcName]['nameSpace']);
		$cc->setHeader($header);
		return $cc->callFunc($funcName, $params);
	}
	
	protected static function createXMLNode($rootNodeName,$data,$dataNodeName=NULL){
		$dom = new \DOMDocument('1.0', 'utf-8');
		$rootNode = $dom->appendChild($dom->createElement($rootNodeName));
		if (!$dataNodeName){
			self::appendDataToNode($dom,$rootNode,$data);
		}else {
			foreach ($data AS $v){
				$childNode = $rootNode->appendChild($dom->createElement($dataNodeName));
				self::appendDataToNode($dom,$childNode,$v);
			}
		}
		return $dom->saveXML($rootNode);
		
/*		if (!$dom){
			$dom = new DOMDocument('1.0', 'utf-8');
		}
		$element = $dom->createElement($rootNodeName);
		$rootNode = $dom->appendChild($element);
		foreach ($data AS $k => $v){
			if (is_array($v)){
				//$rootNode->appendChild($dom->createElement($k))->appendChild($v);	
			}else{
				$rootNode->appendChild($dom->createElement($k,$v));
			}
		}
		return $dom->saveXML($rootNode);*/
	}
	
	private static function appendDataToNode(&$dom,&$parentNode,$data){
		foreach ($data AS $k => $v){
			if (is_array($v)){
				self::appendDataToNode($dom,$parentNode->appendChild($dom->createElement($k)),$v);
			}else{
				$parentNode->appendChild($dom->createElement($k,$v));
			}
		}
	}
}