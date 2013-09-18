<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>CReq Admin</title>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="view/jquery.peity.min.js"></script>
<link rel="stylesheet" href="view/main.css">
<style>

</style>
</head>
<body>
<script type="text/javascript">
if(typeof(parent.pushDBList)=='function'){
	parent.pushDBList('<?php echo addslashes($name);?>','<?php echo addslashes($url);?>');
}else{
	alert('创建成功');
}
</script>
添加成功！
</body>
</html>