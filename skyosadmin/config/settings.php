<?php
defined('OPERATOR_CODE') or define('OPERATOR_CODE', 1111);//操作码
defined('HostName') or define('HostName', 'dev.tvos.skysrt.com');//主机名
defined('RemoteHostName') or define('RemoteHostName', 'tvos.skysrt.com');//远程接口服务中心域名
defined('AREA_CODE') or define('AREA_CODE', 000000);//地区码
defined('TVOS_MASTER') or define('TVOS_MASTER', '127.0.0.1:3306');//主数据库，用逗号分割不同主库
defined('TVOS_SLAVE') or define('TVOS_SLAVE', '127.0.0.1:3306');//从数据库，用逗号分割不同从库
defined('TVOS_DB_NAME') or define('TVOS_DB_NAME', 'root');//数据库用户名
defined('TVOS_DB_PASSWORD') or define('TVOS_DB_PASSWORD', 'dota.123');//数据库密码
defined('MEMCACHE_IP') or define('MEMCACHE_IP', '127.0.0.1');//memcahce
defined('MEMCACHE_PORT') or define('MEMCACHE_PORT', 11211);//memcache端口
defined('MEMCACHE_ENABLE') or define('MEMCACHE_ENABLE', false);//是否启用memcache
defined('FTP_HOST') or define('FTP_HOST', '10.200.240.21');//ftp密码
defined('FTP_PORT') or define('FTP_PORT', 21);//ftp端口
defined('FTP_USERNAME') or define('FTP_USERNAME', 'srtuser');//ftp用户名
defined('FTP_PASSWORD') or define('FTP_PASSWORD', 'dota.123');//ftp密码
//@@area_config 注意：请不要删除或修改该行的内容！！！！！！
defined('ROOT') or define('ROOT', '/data/cloudservice');//php脚本主目录，网站根目录
defined('UPLOADROOT') or define('UPLOADROOT', '/rs');//本地上传主目录==>对应到资源文件服务器目录
defined('APKROOT') or define('APKROOT', '/apk');//本地apk包上传的临时目录==>对应到资源文件服务器目录
defined('ICONROOT') or define('ICONROOT', '/icon');//本地apk包的图标的临时目录==>对应到资源文件服务器目录
defined('ZIPROOT') or define('ZIPROOT', '/zip');//本地升级包上传的临时目录==>对应到资源文件服务器目录 
defined('THESAURUSROOT') or define('THESAURUSROOT', '/thesaurus');//本地上传词库的临时目录==>对应到资源文件服务器目录
defined('UPGRADEROOT') or define('UPGRADEROOT', '/onlineupgrade');//本地升级包解压后上传的临时目录==>对应到资源文件服务器目录
defined('RS_HostName') or define('RS_HostName', 'pic.skysrt.com');//资源文件服务器域名，ftp上传有用到
defined('RS_ROOT') or define('RS_ROOT', '/data/www');//资源文件服务器存储主目录,ftp上传有用到
