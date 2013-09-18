<?php
/*
 * @Info 原webservice的类
 * @Date 2013.06.24
 * @Autor twl
 */
namespace skyosadmin\components;
define("AaptDir",  dirname(__FILE__)."/tool/");
class ApkInfo{
	public $info_arr;
	public $info_str;
	public $packageName;
	public $versionCode;
	public $versionName;
	public $appName;
	public $appIcon;
	public $minSdkVersion;
	function ApkInfo($apk_path){
		$command=AaptDir."aapt d badging ".$apk_path;
//		$command=AaptDir."aapt dump xmltree ".$apk_path." AndroidManifest.xml";
		$out=array();
		exec($command,$out);
		$this->info_arr=$out;
		$str = "";
		for ($i=0;$i<count($out);$i++){
			$str.=$out[$i]."\n";
		}
		$this->info_str=$str;

		$this->setInfo1();
		$this->setInfo2();
		//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
		//echo "<pre>";
		//print_r($out);
		//echo "</pre>";
		$command=AaptDir."aapt dump xmltree ".$apk_path." AndroidManifest.xml"; 
		exec($command,$out1);
		$this->info_arr=$out1;
		$this->setInfo3();
		//echo "<pre>";
		//print_r($out1);
		//echo "</pre>";
	}

	function search($key){
		for($i=0;$i<count($this->info_arr);$i++){
			//echo $this->info_arr[$i]," ",$key,"<br>";
			if (strpos($this->info_arr[$i], $key)!==FALSE){
				return $this->info_arr[$i];
			}
		}
		return "";
	}

	function setInfo1(){
		$str = $this->search("package:");
		$this->packageName=$this->getValue($str, "name");
		$this->versionCode=$this->getValue($str, "versionCode");
		$this->versionName=$this->getValue($str, "versionName");
	}

	function setInfo2(){
		$str = $this->search("application:");
		$this->appName=$this->getValue($str, "label");
		$this->appIcon=$this->getValue($str, "icon");
	}

	function setInfo3(){
		$str = $this->search("android:minSdkVersion");
		$this->minSdkVersion = base_convert(substr($str, strrpos($str, "0x")+2), 16, 10);
		//echo "minSdkVersion: ".$this->minSdkVersion;
	}

	function getValue($str,$key,$sign="="){
		//echo $str," ",$key,$sign,"<br>";
		$loc=strpos($str, $key.$sign);
		if ($loc===false) return null;
		$loc2=$loc+strlen($key)+1;
		$quoteChar=substr($str, $loc2,1);
		$haystack=str_replace("\\".$quoteChar, "**", $str);
		$loc3=strpos($haystack, $quoteChar,$loc2+1);
		if ($loc3===false) return null;
		return stripslashes(substr($str, $loc2+1,$loc3-$loc2-1));
	}
}

/*$apk_path="/srv/www/htdocs/appstoreadmin/apk/20111129142923AhZk.Bsdq.V1.3.apk";
 $apk_path="/srv/www/htdocs/appstoreadmin/apk/20110810155104CloudPlatform-115.apk";
 echo "<pre>";
 $ai = new ApkInfo($apk_path);
 print_r($ai->info_arr);
 echo "</pre>";

 echo $ai->packageName;echo "<br>";
 echo $ai->versionCode;echo "<br>";
 echo $ai->versionName;echo "<br>";
 echo $ai->appName;echo "<br>";
 echo $ai->appIcon;echo "<br>";*/
