<?php
namespace advertise\controllers;
use advertise\controllers;

class CoocaaAd extends CoocaaClass{
	private static $instanceSets = array();
	private $functionSets;
	private $header;
	private $coocaaId;
	
	static public function getInstance($coocaaId){
		if (!array_key_exists($coocaaId, self::$instanceSets)){
			$ccapp = new self($coocaaId);
			self::$instanceSets[$coocaaId] = $ccapp;
			$ccapp->coocaaId = $coocaaId;
			return $ccapp;
		}else{
			return self::$instanceSets[$coocaaId];
		}
	}

	public function __construct($coocaaId){	
		$this->header = array();
		$this->functionSets = array();
		//echo "getSession: ".parent::getSession($coocaaId)."\n";		
		$params = array("Input"=>"<data><coocaaid>$coocaaId</coocaaid><version>C26I48Ver1.1.0</version><strpath>/ad</strpath></data>");
		$dom = parent::callCenter($params)->toDOM();
		echo "【dom】";
		var_dump($dom);
		if ($dom){			
			try {
				$headerNodeList = $dom->getElementsByTagName("Rules");
				if ($headerNodeList->length){
					$nodes = $headerNodeList->item(0)->childNodes;
					//var_dump($dom->saveXML($headerNodeList->item(0)));
					foreach ($nodes AS $node){
						if($node->nodeName!="#text"){
							$this->header[$node->nodeName]=$node->nodeValue;
						}
					}
				}
	
				$funcNodes = $dom->getElementsByTagName("Function");
				foreach ($funcNodes AS $funcNode){
					$attrs = $funcNode->attributes;
					$attr1 = $attrs->getNamedItem('FunName')->nodeValue;
					$attr2 = $attrs->getNamedItem('ServiceUrl')->nodeValue;
					$attr3 = $attrs->getNamedItem('Namespace')->nodeValue;
					$this->functionSets[$attr1]=array('serviceUrl'=>$attr2,'nameSpace'=>$attr3);			
				}	
			}
			catch (\Exception $e){
				return $e->getMessage();
			}
		}		
	}
	
	function printFunctionSets(){
		print_r($this->functionSets);
	}
		
	function getAdInfo($sceneCode,$positionCode,$version){
		$Ip = new Ip();
		$funcName = "GetADInfo";
		$data = array(
			"Scene_Code"=>$sceneCode,
			"Position_Code"=>$positionCode,
			"Version"=>$version,
			"ip"=>$Ip->getIP()		
		);
		$params = array(
			"Input"=>parent::createXMLNode("data", $data)
		);
		$response = $this->callFunc($funcName, $this->functionSets, $this->header, $params);
		echo "【response:";
		print_r($response);
		echo "】";
		return $response->toArrayAd();			
	}	
}
?>