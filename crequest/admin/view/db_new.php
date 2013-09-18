<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>CReq Admin New DB</title>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<link rel="stylesheet" href="view/main.css">
<style>

</style>
<script type="text/javascript">

</script>
</head>
<body>
<form action="<?php echo addslashes($dbCreateUrl);?>" method="post">
名称：<input name='name' type='text'/><br>
<input type='submit' value='提交'>
</form>
</body>
</html>