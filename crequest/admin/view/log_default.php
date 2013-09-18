<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
<table>
<?php foreach ($logUrls AS $name => $urls){?>
<tr>
	<td><?php echo $name;?></td>
	<td><a href="<?php echo addslashes($urls['report']);?>">统计报告</a></td>
	<td><a href="<?php echo addslashes($urls['monitor']);?>">查看文件</a></td>
</tr>
<?php }?>
</table>
</body>
</html>