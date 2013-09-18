<?php
/*
 * @Info 处理一些公共函数
 * @Date 2013.06.30
 * @Autor twl
 */
namespace skyosadmin\components;
use Sky\Sky;
use Sky\base\Controller;
use Sky\utils\Ftp;
use Sky\utils\Curl;

class PolicyController extends Controller{
	
	
	
	public static function Strip($value)
	{
		if(get_magic_quotes_gpc() != 0)
		{
			if(is_array($value))
			if ( self::array_is_associative($value) )
			{
				foreach( $value as $k=>$v)
					$tmp_val[$k] = stripslashes($v);
				$value = $tmp_val;
			}
			else
			for($j = 0; $j < sizeof($value); $j++)
				$value[$j] = stripslashes($value[$j]);
				else
				$value = stripslashes($value);
		}
		return $value;
	}
	
	public static function array_is_associative ($array)
	{
		if ( is_array($array) && ! empty($array) )
		{
			for ( $iterator = count($array) - 1; $iterator; $iterator-- )
			{
			   if ( ! array_key_exists($iterator, $array) ) {
			   	 return true; 
			   }
			}
			return ! array_key_exists(0, $array);
		}
		return false;
	}
	
    /*
     *  支持多字段sql拼接,只针对jqgrid插件
     */
	function getStringForGroup( $group )
	{
		$i_='';
		$sopt = array('eq' => "=",'ne' => "<>",'lt' => "<",'le' => "<=",'gt' => ">",'ge' => ">=",'bw'=>" {$i_}LIKE ",'bn'=>" NOT {$i_}LIKE ",'in'=>' IN ','ni'=> ' NOT IN','ew'=>" {$i_}LIKE ",'en'=>" NOT {$i_}LIKE ",'cn'=>" {$i_}LIKE ",'nc'=>" NOT {$i_}LIKE ", 'nu'=>'IS NULL', 'nn'=>'IS NOT NULL');
		$s = "(";
		if( isset ($group['groups']) && is_array($group['groups']) && count($group['groups']) >0 )
		{
			for($j=0; $j<count($group['groups']);$j++ )
			{
				if(strlen($s) > 1 ) {
					$s .= " ".$group['groupOp']." ";
				}
				try {
					$dat = self::getStringForGroup($group['groups'][$j]);
					$s .= $dat;
				} catch (\Exception $e) {
					echo $e->getMessage();
				}
			}
		}
		if (isset($group['rules']) && count($group['rules'])>0 ) {
			try{
				foreach($group['rules'] as $key=>$val) {
					if (strlen($s) > 1) {
						$s .= " ".$group['groupOp']." ";
					}
					$field = $val['field'];
					$op = $val['op'];
					$v = $val['data'];
					if( $op ) {
						switch ($op)
						{
							case 'bw':
							case 'bn':
								$s .= $field.' '.$sopt[$op]."'$v%'";
								break;
							case 'ew':
							case 'en':
								$s .= $field.' '.$sopt[$op]."'%$v'";
								break;
							case 'cn':
							case 'nc':
								$s .= $field.' '.$sopt[$op]."'%$v%'";
								break;
							case 'in':
							case 'ni':
								$s .= $field.' '.$sopt[$op]."( '$v' )";
								break;
							case 'nu':
							case 'nn':
								$s .= $field.' '.$sopt[$op]." ";
								break;
							default :
								$s .= $field.' '.$sopt[$op]." '$v' ";
								break;
						}
					}
				}
			} catch (\Exception $e) 	{
				echo $e->getMessage();
			}
		}
		$s .= ")";
		if ($s == "()") {
			//return array("",$prm); // ignore groups that don't have rules
			return " 1=1 ";
		} else {
			return $s;;
		}
	}
	
	
	
	/*
	 *  上传ftp
	*/
	public static function uploadFtp($uploadList){
			
		$ftp = Sky::$app->ftp;
		if(is_array($uploadList) && count($uploadList)){
			foreach($uploadList as $local=>$remote){
				if(is_dir($local)){
					//$ftp->mkdir($remote); //远程创建目录 
					///data/www/rs/onlineupgrade/64/../64
					self::createFtpDir($remote);
					$ftp->putAll($local, $remote);
				}else{
					//只判断此文件的父目录是否存在，与此文件最近的一个目录
					$Dir = dirname($remote);
					self::createFtpDir($Dir);
					$ftp->put($local, $remote);
				}
			}
			return true;
		}
		return false;
	}
	
	/*
	 * 创建一个ftp目录,存在则不创建，不存在则创建
	 * 无返回值 
	 */
	public static function createFtpDir($remote){
		$ftp = Sky::$app->ftp;
		$str = rtrim($remote,'/');
		$str = ltrim($str,'/');
		$exArr = explode('/', $str);
		array_pop($exArr);
		$pathed = '/'.join('/',$exArr).'/';
		$ftp->chdir($pathed);//已存在的目录
		$listArray = $ftp->listFiles();
		if(!in_array(basename($remote), $listArray)){
			$ftp->mkdir($remote); //远程创建基目录
		}
	}
	
	/*
	 * 下载apk,icon图标到本地的临时文件夹里
	 *
	 * @param array $downLists 下载类表key=>value
	 */
	public static function downloadfileWget($downLists){ 
		if(is_array($downLists) && count($downLists)){
			foreach($downLists as $url=>$local){
				shell_exec("wget ".$url." -O ".$local);
			}
		}
	}
	
	/**产生格式为201301011122331234的文件名
	 * @return string 时间
	*/
	public static function createUniqueName(){
		$time = explode(" ",microtime());
		$time = date('YmdHis').($time[0] * 10000);
		$time2 = explode(".", $time);
		$time = $time2[0];
		return $time;
	}
	
	 
	
	/**
	 * 递归创建目录函数
	 *
	 * @param $path 路径，比如 "aa/bb/cc/dd/ee"
	 * @param $mode 权限值，php中要用0开头，比如0777,0755
	 */
	public static function RecursiveMkdir($path,$mode)
	{
		if (!file_exists($path))
		{
			self::RecursiveMkdir(dirname($path), $mode);
			mkdir($path, $mode);
		}
	}
	
	
	
	
	
}