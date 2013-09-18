<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>CReq Admin: Log Report</title>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="view/jquery.peity.min.js"></script>
<link rel="stylesheet" href="view/main.css">
<style>

</style>
<script type="text/javascript">
$(document).ready(function(){
	$("span.pie").peity("pie");
	$("span.line").peity("line");
});
</script>
</head>
<body>
<h3>访问报表</h3>
<div style='margin-left: 20px;'>
<table>
	<tr>
		<td>测试时间：</td>
		<td><span style='color: red;'><?php echo $total['beginTime'];?></span> - <span style='color: red;'><?php echo $total['endTime'];?></span></td>
	</tr>
	<tr>
		<td><div style='float: left;'>访问次数：</div></td>
		<td>
		<div style='float: left;margin-right:20px;'>
		成功<span style='color: red;'><?php echo $total['countOK'];?></span>个<br>
		失败<span style='color: red;'><?php echo $total['countError'];?></span>个
		</div>
		<div><span class='pie' data-diameter='32'><?php echo $total['countOK'],",",$total['countError'];?></span></div>
		</td>
	</tr>
	<tr>
		<td>执行用时：</td>
		<td><span style='color: red;'><?php echo $total['exeTime'];?></span>ms</td>
	</tr>
	<tr>
		<td>时间分布：</td>
		<td><span class="line" data-height='64' data-width='128'><?php echo implode(",", $total['timeSection'])?></span></td>
	</tr>
</table>
</div>
<h4>详情</h4>
<div style='margin-left: 20px;'>
<table class='datatable'>
	<tr>
		<th>接口名称</th><th>访问次数</th><th>执行用时</th><th>时间分布</th><th>高频访问</th>
	</tr>
	<?php foreach ($request AS $reqId => $reqST){?>
	<tr>
		<td><span><?php echo $reqId;?></span></td>
		<td>
		<div style='float: left;margin-right:10px;'>
			成功<span><?php echo $reqST['countOK'];?></span>个<br>
			失败<span><?php echo $reqST['countError']?></span>个
		</div>
		<span class='pie' data-diameter='32'><?php echo $reqST['countOK'],",",$reqST['countError'];?></span>
		</td>
		<td><span><?php echo $reqST['exeTime'];?></span>ms</td>
		<td><span class="line" data-height='32' data-width='64'><?php echo implode(",", $reqST['timeSection'])?></span></td>
		<td><pre><?php if ($reqST['question']) echo implode(' ', $reqST['question']);?></pre></td>
	</tr>
	<?php }?>
</table>
</div>
<br>
<div>
<input type="button" value="返回" onclick="history.back()"/>
</div>
</body>
