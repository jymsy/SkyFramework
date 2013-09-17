<?php
namespace advertise\controllers;
use advertise\controllers;

class CoocaaResponse extends srtOption {
	private $rawResponseData;
	private $funcName;
	private $nameSpace;
	
	function coocaaResponse($responseData, $funcName, $nameSpace = NULL) {
		
		// echo " in coocaaResponse:"."<br><br>";
		// echo " responseData:".$responseData."<br><br>";
		// echo " funcName:".$funcName."<br><br>";
		
		$this->rawResponseData = $responseData;
		$this->funcName = $funcName;
		$this->nameSpace = $nameSpace;
		$options = array (
				"resultCodeTag" => "RESULT_CODE",
				"successCode" => "00000000",
				"contentTag" => "REP_CONTENT" 
		);
		$this->setOptions ( $options );
	}
	
	static function null() {
		return new self ( null, null );
	}
	
	function toRawResponse() {
		return $this->rawResponseData;
	}
	
	function toRawXML() {
		return substr ( $this->rawResponseData, strpos ( $this->rawResponseData, "<?xml" ) );
	}
	
	private static function xmlToDom($xml) {
		if (strpos ( $xml, "<?xml" ) !== FALSE) {
			$dom = new \DOMDocument ( '1.0', 'utf-8' );
			try {
				$dom->loadXML ( $xml );
			} catch (\Exception $e ) {
				return null;
			}
			return $dom;
		} else
			return null;
	
	}
	
	function toRawDOM() {
		return self::xmlToDom ( $this->toRawXML () );
	}
	
	function toString() {
		return $this->toXML ();
	}
	
	function toInt() {
		$result = $this->toXML ();
		if (is_numeric ( $result )) {
			return $result;
		} else
			return null;
	}
	
	function toXML() {
		if ($rawDom = $this->toRawDOM ()) {
			// echo "nameSpace: ";var_dump($this->nameSpace);
			if (isset ( $this->nameSpace )) {
				$nodeList = $rawDom->getElementsByTagNameNS ( $this->nameSpace, $this->funcName . "Result" );
			} else {
				$nodeList = $rawDom->getElementsByTagName ( $this->funcName . "Result" );
			}
			if ($nodeList->length) {
				return $nodeList->item ( 0 )->nodeValue;
			} else {
				return null;
			}
		} else
			return null;
		// return
	// $this->toRawDOM()->getElementsByTagNameNS($this->nameSpace,$this->funcName."Result")->item(0)->nodeValue;
	}
	
	function toDOM() {
		return self::xmlToDom ( $this->toXML () );
	}
	
	function toArray($options = array()) {
		if ($dom = $this->toDOM ()) {
			try {
				$resultCodeTag = $this->getOption ( "resultCodeTag", $options );
				$successCode = $this->getOption ( "successCode", $options );
				if ($dom->getElementsByTagName ( $resultCodeTag )->item ( 0 )->nodeValue == $successCode) {
					$arr = array ();
					$contentTag = $this->getOption ( "contentTag", $options );
					$nodeList = $dom->getElementsByTagName ( $contentTag );
					if ($nodeList->length) {
						// echo "nodeList->item(0)->firstChild:
						// ".$dom->saveXML($nodeList->item(0)->firstChild)."\n";
						// echo "nodeType:
						// ".$nodeList->item(0)->firstChild->firstChild->nodeType."\n";
						if ($nodeList->item ( 0 )->firstChild->firstChild->nodeType == XML_TEXT_NODE) {
							for($i = 0; $i < $nodeList->length; $i ++) {
								$arr [$i] = array ();
								$childNodeList = $nodeList->item ( $i )->childNodes;
								for($j = 0; $j < $childNodeList->length; $j ++) {
									$arr [$i] [$childNodeList->item ( $j )->nodeName] = $childNodeList->item ( $j )->nodeValue;
								}
							}
							return $arr;
						} else {
							$tagName = $nodeList->item ( 0 )->firstChild->nodeName;
							$nodeList = $dom->getElementsByTagName ( $tagName );
							for($i = 0; $i < $nodeList->length; $i ++) {
								$arr [$i] = array ();
								$childNodeList = $nodeList->item ( $i )->childNodes;
								for($j = 0; $j < $childNodeList->length; $j ++) {
									$arr [$i] [$childNodeList->item ( $j )->nodeName] = $childNodeList->item ( $j )->nodeValue;
								}
							}
							return $arr;
						}
					} else {
						return $arr;
					}
					// return
				// $dom->getElementsByTagName("DownUrl")->item(0)->nodeValue;
				} else {
					return array ();
				}
			} catch (\Exception $e ) {
				return array ();
			}
		} else
			return null;
	}
	
	function toArrayAd($options = array()) {		
		if ($dom = $this->toDOM ()) {
			try {				
				$resultCodeTag = $this->getOption ( "resultCodeTag", $options );
				$successCode = $this->getOption ( "successCode", $options );
				if ($dom->getElementsByTagName ( $resultCodeTag )->item ( 0 )->nodeValue == $successCode) {
					$arr = array ();
					$contentTag = $this->getOption ( "contentTag", $options );
					
					$idx = 0;					
					$nodeList = $dom->getElementsByTagName ( "AD" );
					foreach($nodeList as $node){					
						$SceneList = $node->getElementsByTagName ( "AdvertisingScene" );						
 						$sceneChild = $SceneList->item(0)->childNodes;
						foreach ($sceneChild as $child){							
							$arr[$idx] [$child->nodeName] = $child->nodeValue;
						}
						
						$resourceList = $node->getElementsByTagName ( "AdvertisingResource" );
						$resourceChild = $resourceList->item(0)->childNodes;
						foreach ($resourceChild as $child){				
							if ($child->nodeName != "REP_ADRURL"){
								$arr[$idx] [$child->nodeName] = $child->nodeValue;
							}							
						}
						
						$adrurlList = $node->getElementsByTagName ( "REP_ADRURL" );
						$adrurlChild = $adrurlList->item(0)->childNodes;
						foreach ($adrurlChild as $child){
							if($child->nodeName == "ADR_Format"){								
								$arr[$idx] ["ADR_Md5Format"] = $child->nodeValue;
							}
							else{
								$arr[$idx] [$child->nodeName] = $child->nodeValue;
							}							
						}						
						
						$idx += 1;
					}
					
// 					foreach ($arr as $aa){
// 						foreach ($aa as $key=>$value){
// 							echo $key.":".$value."<br/>";
// 						}
// 						echo "=============================================================="."<br/>";
// 					}					
					
					return $arr;	
				} else {
					return array ();
				}
			} catch (\Exception $e ) {
				return array ();
			}
		} else
			return null;
	}
	
	function toResult($options = array()) {
		if ($dom = $this->toDOM ()) {
			try {
				$resultCodeTag = $this->getOption ( "resultCodeTag", $options );
				$successCode = $this->getOption ( "successCode", $options );
				if ($dom->getElementsByTagName ( $resultCodeTag )->item ( 0 )->nodeValue == $successCode) {
					return 1;
				}
			} catch (\Exception $e ) {
				return 0;
			}
		}
		return 0;
	}
	
	function toList() {
		if ($arr = $this->toArray ()) {
			$list = array ();
			foreach ( $arr as $row ) {
				array_push ( $list, ( object ) $row );
			}
			return $list;
		} else
			return null;
	}

}