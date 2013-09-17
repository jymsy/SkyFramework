<?php
namespace tvos\autorun;

use tvos\autorun\models\ResUpdateModel;
use tvos\autorun\models\CrawlerFileVersionModel;

class resourceUpdateFirst {
    
    public function before() {
        ResUpdateModel::updateVideoIsOld();
    }
    
    public function GetData($date) {
        $server = 'lrc.skysrt.com';
        $server = '10.200.240.211';
        $floder = 'allInPython';
        $json = @file_get_contents("http://$server/$floder/$date");
        file_put_contents("temp.txt", $json);
        echo "\n resource_update GetData finish!";
    }
    
    private function GetKV($areabiz_type,$areabiz_table) {
        $kv = new kv();
        return $kv->GetArray($areabiz_type, $areabiz_table);
    }
    
    private function GetCondition($areabiz_id,$areabiz_type,$areabiz_table) {
        $sign = get_object_vars($areabiz_id);
        $kvarray = self::GetKV($areabiz_type, $areabiz_table);
        $wheres = "";
        foreach($sign as $key=>$value) {
            if (isset($kvarray[$key])) $key = $kvarray[$key];
            else continue;
            if(!empty($wheres)) $wheres .= " and ";
            if (is_int($value)) {
                $wheres .= "`$key`=$value";
            } else {
                $wheres .= "`$key`='".addslashes($value)."'";
            }
        }
        return $wheres;
    }
    
    function Update() {
        $handle = fopen("temp.txt", "r");
        while(!feof($handle)) {
            $row = fgets($handle);
            if (isset($row) && !empty($row)) {
                $object = json_decode($row);
                if (isset($object)) {
                    $array = get_object_vars($object);
                    $areabiz_type = $object->areabiz_type;
                    $areabiz_table = $object->areabiz_table;
                    $areabiz_id = $object->areabiz_id;
                    $kvarray = self::GetKV($areabiz_type, $areabiz_table);
                    $v_schema = $kvarray['areabiz_schema'];
                    $v_table = $kvarray['areabiz_table'];
                    switch ($areabiz_type) {
                        case 'insert':
                            //排重
                            $wheres = self::GetCondition($areabiz_id, $areabiz_type, $areabiz_table);
                            if (intval(ResUpdateModel::querydatacount($v_schema, $v_table, $wheres)) > 0) continue;
                            
                            $keys = '';
                            $values = '';
                            foreach ($array as $key=>$value) {
                                if ($key == "areabiz_table" || $key == "areabiz_type" || $key == "areabiz_id") continue;
                                if (isset($kvarray[$key])) $key = $kvarray[$key];
                                else continue;
                                if ($keys != '') $keys .= ',';
                                $keys .= "`$key`";
                                if ($values != '') $values .= ',';
                                if (is_int($value)) {
                                    $values .= $value;
                                } else {
                                    $values .= "'".addslashes($value)."'";
                                }
                            }
                            $id = ResUpdateModel::importdata($v_schema, $v_table, $keys, $values);
                            
//                             if ($id > 0) {
//                                 switch ($areabiz_table) {
//                                     case 'sokuSite':
//                                         ResUpdateModel::modifyvideosite($id);
//                                         break;
//                                     case 'sokuUrl':
//                                         ResUpdateModel::modifyvideourl($id, '');
//                                         break;
//                                     case 'sokuCommment':
//                                         ResUpdateModel::modifyvideocomment($id);
//                                         break;
//                                     case 'kuwoTop':
//                                         ResUpdateModel::modifymusictopbyid($id);
//                                         break;
//                                     case 'playbill':
//                                         ResUpdateModel::modifyplaybillbyid($id);
//                                         break;
//                                     default:
//                                         break;
//                                 }
//                             }
                            break;
                        case 'update':
                            $wheres = self::GetCondition($areabiz_id, $areabiz_type, $areabiz_table);
                            if ($wheres == '') continue;
                            $sets = '';
                            foreach ($array as $key=>$value) {
                                if ($key == "areabiz_table" || $key == "areabiz_type" || $key == "areabiz_id") continue;
                                if (isset($kvarray[$key])) $key = $kvarray[$key];
                                else continue;
                                if ($sets != '') $sets .= ',';
                                if (is_int($value)) {
                                    $sets .= "`$key`=$value";
                                } else {
                                    $sets .= "`$key`='".addslashes($value)."'";
                                }
                            }
                            ResUpdateModel::modifydata($v_schema, $v_table, $sets, $wheres);
                            break;
                        default:
                            break;
                    }
                }
            }
        }
        fclose($handle);
        echo "\n resource_update Update finish!";
    }
    
    function Other() {
        ResUpdateModel::insertvidesitebyvideourl();
//         ResUpdateModel::modifymusictop();
//         ResUpdateModel::modifyplaybill();
        echo "\n resource_update Other finish!";
    }
    
    function run() {
//         $pre = intval(CrawlerFileVersionModel::querycrawlerfileversion());
//         $today = intval(strtotime(date('Y-m-d'), time()));
//         if ($pre == 0) {
//             $pre = $today - 1;
//         }
//         while ($pre < $today) {
//         	$pre = $pre + 3600*24;
// 			self::before();
            self::Update();
            self::Other();
//             $handle = fopen("temp.txt", "w");
//             fwrite($handle, '');
//             fclose($handle);
//             CrawlerFileVersionModel::insertcrewlerfileversion($pre, date('Y-m-d', $pre));
//     	}
    }
    
}