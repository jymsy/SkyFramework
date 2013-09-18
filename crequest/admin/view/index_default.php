<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>CReq Admin</title>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<link rel="stylesheet" href="view/main.css">
<script type="text/javascript">
function showDBView(url){
	$('#iframe_db').attr('src',url);
}
function goToLogDividePage(ele){
	client = $(ele).parent().parent().attr("client");
	var urlPrefix = '<?php echo addslashes($logDivideUrl);?>';
	//window.location = urlPrefix+'&client='+encodeURIComponent(client);
	var ifm = $('<iframe></iframe>');
	ifm.attr("src", urlPrefix+'&client='+encodeURIComponent(client));
	ifm.appendTo($('#div_no_display'));
	//alert(urlPrefix+'&client='+encodeURIComponent(client));
}
var pushDBList = function (name, url){
	//alert(name+":"+url);
	$('<li><span onclick="showDBView(\''+url+'\')">'+name+'</span></li>').insertBefore('#db_li_last');
}
function refreshCurrentCfg(cfgMap){
	var cfgSelector = $('#tbl_currentCfg');
	cfgSelector.empty();
	cfgSelector.append('<tr><th>客户端</th><th>事件</th><th>日志</th><th>操作</th></tr>');
	var clientName, trSelector,events,log;
	for(var client in cfgMap){
		trSelector = $("<tr></tr>");
		trSelector.attr("client",client);
		clientName = (client=='default')?'缺省':client;
		events = cfgMap[client].hasOwnProperty('events')?cfgMap[client]['events']:'缺省';
		log = cfgMap[client].hasOwnProperty('log')?cfgMap[client]['log']:'缺省';
		trSelector.append('<td>'+clientName+'</td>');
		trSelector.append('<td>'+events+'</td>');
		trSelector.append('<td>'+log+'</td>');
		if(client=='default'){
			trSelector.append('<td></td>');
		}else{
			trSelector.append('<td><span style="cursor: pointer;text-decoration: underline;" onclick="goToLogDividePage(this)">日志分割</span></td>');
		}
		trSelector.appendTo(cfgSelector);
	}
}
$(document).ready(function(){
	refreshCurrentCfg(<?php echo json_encode($config);?>);
});
</script>
</head>
<body>
<?php if (isset($quickSetUrl)) {?>
<a href="<?php echo addslashes($quickSetUrl)?>">点击一键安装</a><br>
<?php }?>
<h3>当前配置</h3>
<div style='width:800px;'>
<table class='datatable' id='tbl_currentCfg'>
</table>
<iframe id="ifm_no_display" src="" style="display: none;"></iframe>
</div>
<h4>DB列表</h4>
<div class='sidenav'>
<ul>
<?php foreach ($dbUrls AS $dbName => $dbUrl){?>
	<li><span onclick="showDBView('<?php echo addslashes($dbUrl)?>')"><?php echo $dbName;?></span></li>
<?php }?>
	<li id='db_li_last'><span style='text-align: center;color: rgba(190, 190, 190, 1);' onclick="showDBView('<?php echo addslashes($dbNewUrl);?>')">添加</span></li>
</ul>
</div>
<iframe id='iframe_db' src='' width='600px' height='600px'>
</iframe>
<div id="div_no_display" style="display: none;"></div>
</body>
</html>