<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>CReq Admin</title>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<link rel="stylesheet" href="view/main.css">
<style>

</style>
<script type="text/javascript">
if(typeof(parent.refreshCurrentCfg)=='function'){
	parent.refreshCurrentCfg(<?php echo json_encode($config);?>);
}else{
	alert("修改成功");
}
</script>
</head>
<body>
<pre>
<?php print_r($config);?>
</pre>
</body>
</html>