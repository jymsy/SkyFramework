<?php
defined('OPERATOR_CODE') or define('OPERATOR_CODE', 1111);//操作码
defined('HostName') or define('HostName', 'dev.tvos.skysrt.com');//主机名
defined('AREA_CODE') or define('AREA_CODE', 000000);//地区码
defined('TVOS_MASTER') or define('TVOS_MASTER', '127.0.0.1:3306');//主数据库，用逗号分割不同主库
defined('TVOS_SLAVE') or define('TVOS_SLAVE', '127.0.0.1:3306');//从数据库，用逗号分割不同从库
defined('TVOS_DB_NAME') or define('TVOS_DB_NAME', 'root');//数据库用户名
defined('TVOS_DB_PASSWORD') or define('TVOS_DB_PASSWORD', 'jymsy');//数据库密码
// defined('TVOS_DB_NAME') or define('TVOS_DB_NAME', 'root');//数据库用户名
// defined('TVOS_DB_PASSWORD') or define('TVOS_DB_PASSWORD', 'dota.123');//数据库密码
defined('MEMCACHE_CONFIG') or define('MEMCACHE_CONFIG', '127.0.0.1:11211');//memcahce
defined('MEMCACHE_ENABLE') or define('MEMCACHE_ENABLE', false);//是否启用memcache
//@@area_config 注意：请不要删除或修改该行的内容！！！！！！
defined('INFO_URL_PREFIX') or define('INFO_URL_PREFIX', 'http://42.121.119.71/resource/info.php?id=');//
defined('TIANCI_OS_ADMIN_ID') or define('TIANCI_OS_ADMIN_ID', 10010);//
defined('REPORT_SERVICE_URI') or define('REPORT_SERVICE_URI', 'http://121.199.33.20:40025/ReportService/UserActivity');//
defined('REQ_LOG_DIR') or define('REQ_LOG_DIR', '/tmp/request_log/');//
