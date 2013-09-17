<?php
defined('OPERATOR_CODE') or define('OPERATOR_CODE', 1111);//操作码
defined('HostName') or define('HostName', 'dev.tvos.skysrt.com');//主机名
defined('RemoteHostName') or define('RemoteHostName', 'beta.tvos.skysrt.com');//远程接口服务中心域名
defined('TVOS_MASTER') or define('TVOS_MASTER', '127.0.0.1:3306');//主数据库，用逗号分割不同主库
defined('TVOS_SLAVE') or define('TVOS_SLAVE', '127.0.0.1:3306');//从数据库，用逗号分割不同从库
defined('TVOS_DB_NAME') or define('TVOS_DB_NAME', 'root');//数据库用户名
defined('TVOS_DB_PASSWORD') or define('TVOS_DB_PASSWORD', 'dota.123');//数据库密码
defined('MEMCACHE_CONFIG') or define('MEMCACHE_CONFIG', '127.0.0.1:11211');//memcahce
defined('MEMCACHE_ENABLE') or define('MEMCACHE_ENABLE', false);//是否启用memcache
defined('FTP_HOST') or define('FTP_HOST', '10.200.240.21');//ftp密码
defined('FTP_PORT') or define('FTP_PORT', 21);//ftp端口
defined('FTP_USERNAME') or define('FTP_USERNAME', 'srtuser');//ftp用户名
defined('FTP_PASSWORD') or define('FTP_PASSWORD', 'dota.123');//ftp密码
defined('CREQ_DIR') or define('CREQ_DIR', '/data/cloudservice/Framework/crequest/');//CReqST工具目录(dev专用，勿同步到Release)(Only for dev. Don't release this const.)
defined('CP_SWITCH') or define('CP_SWITCH', 'COOCAA');//内容商开关
defined('BOOTUIROOT') or define('BOOTUIROOT', 'rs/bootui');//开机画面上传路径
defined('PUSHROOT') or define('PUSHROOT', 'rs/pushmanage');//推送管理上传路径
defined('PROF_PROBABILITY') or define('PROF_PROBABILITY', 5);//接口执行时间采样率
//@@area_config 注意：请不要删除或修改该行的内容！！！！！！
defined('INFO_URL_PREFIX') or define('INFO_URL_PREFIX', 'http://'.HostName.'/resource/info.php?id=');//资讯
defined('ROOT') or define('ROOT', '/data/cloudservice');//php脚本主目录，网站根目录
defined('REQ_LOG_DIR') or define('REQ_LOG_DIR', '/tmp/request_log/');//web接口访问耗时日志目录
defined('PROF_PROBABILITY') or define('PROF_PROBABILITY', 100);//接口执行时间采样率