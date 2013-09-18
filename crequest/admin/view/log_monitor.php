<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<title>CReq Admin: Log Monitor</title>
<script type="text/javascript">
function fetchData(offset){
	$.ajax({
		url: "<?php echo addslashes($dataUrl);?>",
		data:{
			offset:offset
		},
		beforeSend:function(xhr){
			$('#status').html("正在刷新数据...");
		},
		fail:function(){
			$('#status').html("监听已停止");
		},
		success: function(data){
			eval("var d = "+data);
			if(d.resultCode == 0){
				if(d.data[1]){
					$('#textarea1').append(d.data[1]);
					$('#status').html("新增数据");
				}else{
					$('#status').html("无数据更新");
				}
				setTimeout('fetchData('+d.data[0]+')',3000);
			}else{
				$('#status').html("Fetch Data Error: "+d.resultMsg);
			}
		}
	});
}
fetchData(0);

</script>
</head>
<body>
<div>状态：<span id="status"></span></div>
<textarea rows="40" cols="120" id="textarea1"></textarea>
</body>
</html>