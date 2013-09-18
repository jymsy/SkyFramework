<?php
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."cReqUtil.php";

abstract class CReqPage{
	const DEFAULT_ACTION = 'default';
	const ENABLE_PATH_INFO = false;
	const PATH_SEPARATOR = '|';
	const DEBUG = true;
	private static $_action;
	private static $_returnView;
	private static $_constructArgs = array();
	private static $_rendered = false;
	private static function param($key,$defaultValue = null){
		return isset($_REQUEST[$key])?$_REQUEST[$key]:$defaultValue;
	}
	
	public function __construct(){
		self::$_constructArgs = func_get_args();
	}
	
	static function run(){
		ob_start();
		set_error_handler(__CLASS__.'::_errorHandler',E_ALL);
		set_exception_handler(__CLASS__.'::_exceptionHandler');
		$path = self::ENABLE_PATH_INFO
				?(isset($_SERVER['PATH_INFO'])?substr($_SERVER['PATH_INFO'],1):'')
				:(isset($_REQUEST['_path'])?$_REQUEST['_path']:self::DEFAULT_ACTION);
		
		$pathArr = explode(self::PATH_SEPARATOR, $path);
		
		self::$_action = $action = array_shift($pathArr);
		$actionName = 'action'.ucfirst($action?$action:self::DEFAULT_ACTION);
		
		$className = get_called_class();
		$class = new ReflectionClass($className);
		self::$_returnView = isset($_REQUEST['_return'])?('return_'.$_REQUEST['_return']):false;
		try {
			$method = $class->getMethod($actionName);
			if (!($method instanceof ReflectionMethod)) self::_error(new Exception("Action[$action] doesn't exists."));
		}catch (Exception $e){
			return self::_error(new Exception("Action[$action] doesn't exists."));
		}

		$mArgs = array();
		foreach ($method->getParameters() AS $param){
			$param instanceof ReflectionParameter;
			$pKey = $param->getName();
			if (isset($_REQUEST[$pKey])) {
				$mArgs[] = $_REQUEST[$pKey];
			}else{
				if ($param->isOptional()) {
					$mArgs[] = $param->getDefaultValue();
				}else return self::_error(new Exception("Missing Arg[$pKey]"));
			}
		}
		try {
			if ($method->isStatic()) {
				$return = call_user_func_array(array($className,$actionName), $mArgs);
			}else {
				if (count($pathArr)) {
					$return =  $method->invokeArgs($class->newInstanceArgs($pathArr), $mArgs);
				}else $return =  $method->invokeArgs($class->newInstance(), $mArgs);
			}
			if (self::$_returnView) {
				self::_renderTo(self::$_returnView, array(
						'resultCode'=>0,
						'resultMsg'=>self::DEBUG?ob_get_clean():'',
						'data'=>$return,
				));
			}else return $return;
		}catch (Exception $e){
			self::_error($e);
		}
	}
	
	protected static function _error(Exception $e){
		if (self::DEBUG) {
			$msg = $e->getMessage()."\n".$e->getTraceAsString()."\n".ob_get_clean();
		}else{
			ob_clean();
			$msg = $e->getMessage();
		}
		
		if (self::$_returnView) {
			try {
				self::_renderTo(self::$_returnView, array(
						'resultCode'=>$e->getCode()?$e->getCode():1,
						'resultMsg'=>$msg,
						'data'=>''
				));
			}catch (Exception $e){
				die($e->getMessage());
			}
		}else{
			if (self::$_action == 'error') {
				die($msg);
			}
			$url = self::_generateUrl('error',array(
					'msg'=>$msg,
					'from'=>self::__currentUrl(),
			));
			header("Location:$url");
			echo sprintf('<script>window.location ="%s";</script>',addslashes($url));
			ob_end_flush();
		}
		
		exit();
// 		$className = get_called_class();
// 		$obj = new $className();
// 		$obj->actionError($e->getMessage());
	}
	
	protected static function __currentUrl(){
		return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	
	protected static function _generateUrl($action,$data = array(),$constructArgs=array(),$script = null){
		if (is_null($script)) {
			$webpath = $_SERVER['SCRIPT_NAME'];
		}else{
			$file = realpath($script);
			$documentRoot = $_SERVER['DOCUMENT_ROOT'];
			
			if (DIRECTORY_SEPARATOR != '/') {
				$file = str_replace(DIRECTORY_SEPARATOR, '/', $file);
				$documentRoot = str_replace(DIRECTORY_SEPARATOR, '/', $documentRoot);
			}
			
			$documentRootLen = strlen($documentRoot);
			if (strncmp($file, $documentRoot, $documentRootLen)==0) {
				$webpath=substr($file, $documentRootLen);
			}else{
				throw new Exception("File[$file] is not in webDir[$documentRoot].");
			}
		}

		$path = $action;
		if (self::ENABLE_PATH_INFO && !$path) {
			$path = self::DEFAULT_ACTION;
		}
		foreach ($constructArgs AS $cArg){
			$path .= self::PATH_SEPARATOR.$cArg;
		}
		$urlPrefix = 'http://'.$_SERVER['HTTP_HOST'].$webpath;
		if (self::ENABLE_PATH_INFO) {
			return $urlPrefix.'/'.$path.(count($data)?'?'.http_build_query($data):'');
		}else {
			$queryStr = $path?'_path='.urlencode($path):'';
			if (count($data)) {
				if ($queryStr) {
					$queryStr .= '&';
				}
				$queryStr .= http_build_query($data);
			}
			return $urlPrefix.($queryStr?"?$queryStr":'');
		}
	}
	
	protected  static function _actionUri($action,$params=array(),$constructArgs=null){
		if (is_null($constructArgs)) {
			$constructArgs = self::$_constructArgs;
		}
		return self::_generateUrl($action,$params,$constructArgs);
	}
	
// 	public function actionDefault(){
// 		echo 'Call ',__CLASS__,"::",__FUNCTION__,"<br>\n";
// 	}
	
	static public function actionError($msg,$from){
		echo "<pre>Fatal Error: $msg</pre>";
		echo sprintf('<a href="%s">Click to Retry: %s</a>',addslashes($from),$from);
		echo '&nbsp;<input type="button" value="Back" onclick="history.back()"/>';
		exit();
	}
	
	protected static function _renderTo($view,$data,$options=array()){
		if (self::$_rendered) {
			throw new Exception("Double Render View: $view");
		}else self::$_rendered = true;
		$viewType = isset($options['viewType'])?$options['viewType']:'php';
		$view = __DIR__.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.$view.'.'.$viewType;
		$extract_type = isset($options['extractType'])?$options['extractType']:EXTR_OVERWRITE;
		$extract_prefix = isset($options['extractPrefix'])?$options['extractPrefix']:null;
		extract($data,$extract_type,$extract_prefix);
		if (self::DEBUG) {
			//header('DebugLog: '.ob_get_clean());
		}else ob_end_clean();
		require($view);
	}
	
	public static function _errorHandler($errno, $errstr, $errfile, $errline){
		//die(__FUNCTION__);
		self::_error(new ErrorException($errstr, 0, $errno, $errfile, $errline));
	}
	
	public static function _exceptionHandler(Exception $e){
		self::_error($e);
	}
}