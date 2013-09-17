<?php
namespace advertise\controllers;

use advertise\controllers;
// require_once ROOT_COOCAA.'coocaaResponse.php';
// require_once ROOT.'webservices/lib/ip.php';

class CoocaaWebservice{
	private $hostname;// = "center.coocaatv.com";//"223.4.90.186";//
	private $remotepath;// = "/WebServiceEP.ashx";//"/external/testserver.php";//
	private $nameSpace;// = "http://open.coocaatv.com/FrameworkService";
	private $port;
	private $header = NULL;
	public static $debugMode = FALSE;
	
	function __construct($serviceUrl,$nameSpace){
		$urlInfo = parse_url($serviceUrl);
		$this->hostname = $urlInfo['host'];
		$this->remotepath = $urlInfo['path'];
		$this->nameSpace = $nameSpace;
		$this->port = (array_key_exists("port", $urlInfo) && $urlInfo['port']>0)?$urlInfo['port']:80;
	}
	
	function setHeader($header){
		$this->header = $header;
	}
	
	function callFunc($funcName,$params){
		$socket = fsockopen($this->hostname,$this->port, $errno, $errstr, 30);
		echo "【socket:".$this->hostname."  ".$this->port."】";
		if (!$socket){
			throw new \Exception($errstr, $errno);
			return null;
		}
		$hostName = $this->hostname.(($this->port && $this->port!=80)?":$this->port":"");
		$postData = $this->coocaaSoapPostData($this->nameSpace, $funcName, $params,$this->header);
		$requestArr = array();
		$requestArr[] = "POST ".$this->remotepath." HTTP/1.1";
		$requestArr[] = "Host: ".$hostName;
		$requestArr[] = "Content-Type: text/xml; charset=utf-8";
		$requestArr[] = "Content-Length: ".strlen($postData);
		$requestArr[] = "SOAPAction: ".$this->nameSpace."/".$funcName;	
		$Ip = new Ip();	
		$requestArr[] = "UserIpAddress: ".$Ip->getIP();
		$requestArr[] = "Connection: Close";
		$requestArr[] = "";
		$requestArr[] = $postData;
		$requestData = "";
		foreach ($requestArr AS $request){
			$requestData .= $request."\r\n";
		}
		fwrite($socket, $requestData);
		
		//echo "####################".$funcName."####################\n";
		//if ($funcName=="upd_etc_type")
		if (self::$debugMode) echo "$funcName Request:\n".$requestData."\n\n";////////////For Debug///////////////
		
		$responseData = "";
		while (!feof($socket)){
			$responseData .= fgets($socket,8192);
		}
		
		//if ($funcName=="upd_etc_type")
		if (self::$debugMode) echo "$funcName Response:\n".$responseData."\n\n";///////////For Debug//////////////
		
		fclose($socket);
		return new CoocaaResponse($responseData,$funcName,$this->nameSpace);
	}
	
	private function coocaaSoapPostData($nameSpace,$function,$params,$header=NULL){
		$dom = new \DOMDocument('1.0', 'utf-8');
		$ele = $dom->createElement("soap:Envelope");
		$ele->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
		$ele->setAttribute("xmlns:xsd", "http://www.w3.org/2001/XMLSchema");
		$ele->setAttribute("xmlns:soap", "http://schemas.xmlsoap.org/soap/envelope/");
		$ele->setAttribute("xmlns:nm1", $nameSpace);
		$rootNode = $dom->appendChild($ele);
		if ($header){
			$headNode = $rootNode->appendChild($dom->createElement("soap:Header"));
			CoocaaWebservice::appendArrToDOMNode($dom, $headNode, $header);
		}
		$bodyNode = $rootNode->appendChild($dom->createElement("soap:Body"));
		$funcNode = $bodyNode->appendChild($dom->createElement("nm1:".$function));
		CoocaaWebservice::appendArrToDOMNode($dom,$funcNode, $params,"nm1");
		return $dom->saveXML();
	}
	
	private static function appendArrToDOMNode(&$dom,&$node,$arr,$nameSpace=""){
		if ($nameSpace != ""){
			$nameSpace .= ":";
		}
		foreach ($arr AS $k => $v){
			$node->appendChild($dom->createElement($nameSpace.$k,htmlspecialchars($v)));
		}
	}
	
	public static function encryptAES($str,$key="8005e1d84149a2a0"){
		$cryptor = new CryptAES($key);
		return strtoupper($cryptor->encrypt($str,false));
	}
	
	public static function decryptAES($str,$key="8005e1d84149a2a0"){
		$cryptor = new CryptAES($key);
		return $cryptor->decrypt($str,false);
	}
	
}