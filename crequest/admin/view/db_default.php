<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>CReq Admin: DB</title>
<link rel="stylesheet" href="view/main.css">
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript">
function submitDefaultEvent(){
	var url = "<?php echo addslashes($urlSetDefaultEvent);?>";
	var min_sleep = document.getElementById('ipt_sets_min_sleep').value;
	var max_sleep = document.getElementById('ipt_sets_max_sleep').value;
	var exception_prob = document.getElementById('ipt_sets_exception_prob').value;
	url += '&min_sleep='+encodeURIComponent(min_sleep);
	url += '&max_sleep='+encodeURIComponent(max_sleep);
	url += '&exception_prob='+encodeURIComponent(exception_prob);
	//alert(url);
	window.location.href = url;
}
function attachNewClient(){
	new_client = $('#new_client').val();
	if(new_client){
		var url = '<?php echo addslashes($urlAttachClient);?>';
		$.ajax({
			url: url,
			data:{
				client:new_client
			},
			beforeSend:function(xhr){
				$('#btn_new_client').attr("disabled","disabled");
			},
			fail:function(){
				$('#btn_new_client').removeAttr("disabled");
			},
			success: function(data){
				$('#btn_new_client').removeAttr("disabled");
				eval("var d = "+data);
				if(d.resultCode == 0){
					if(d.data){
						addClientToView([new_client]);
						$('#new_client').val('');
					}else{
						alert("添加失败");
					}
				}else{
					alert("添加失败："+d.resultMsg);
				}
			}
		});
	}else {
		alert("请输入名称");
	}
}
function removeAttachedClient(ele){
	var client = $(ele).attr('client');
	if((typeof globalWorkingClientDelEle) == "undefined" || globalWorkingClientDelEle == null){
		globalWorkingClientDelEle = ele;
		$.ajax({
			url: '<?php echo addslashes($urlRemoveAttachedClient);?>',
			data:{
				client:client
			},
			beforeSend:function(xhr){
				
			},
			fail:function(){
				globalWorkingClientDelEle = null;
			},
			success: function(data){
				var ele = globalWorkingClientDelEle;
				globalWorkingClientDelEle = null;
				eval("var d = "+data);
				if(d.resultCode == 0){
					if(d.data){
						$(ele).parent().remove();
					}else{
						alert("删除失败");
					}
				}else{
					alert("删除失败："+d.resultMsg);
				}
			}
		});
	}else{
		alert("已有删除任务正在执行，请耐心等待");
	}
}


function addClientToView(clients){
	for(var i in clients){
		var card = $("<div class='card'></div>");
		card.append($("<span>"+clients[i]+"</span>"));
		var delEle = $("<span style='color:red;cursor:pointer;' onclick='removeAttachedClient(this)'>&nbsp;x</span>");
		delEle.attr('client',clients[i]);
		card.append(delEle);
		card.insertBefore('#client_list_lastEle');
	}
}
$(document).ready(function(){
	addClientToView(<?php echo json_encode($clients);?>);
});
</script>
</head>
<body>

<a href="<?php echo addslashes($urlApplyDB);?>">应用到配置文件</a>
<h4>设置缺省事件</h4>
Sleep下界：<input type="text" id="ipt_sets_min_sleep" name="ipt_sets_min_sleep" value="<?php echo $defaultEvent['min_sleep'];?>" style="width:30px"/> ms
&nbsp;Sleep上界：<input type="text" id="ipt_sets_max_sleep" name="ipt_sets_max_sleep" value="<?php echo $defaultEvent['max_sleep'];?>" style="width:30px"/> ms
<br>
Exception概率(0-100)：<input type="text" id="ipt_sets_exception_prob" name="ipt_sets_exception_prob" value="<?php echo $defaultEvent['exception_prob'];?>" style="width:20px"/> %
&nbsp;<input type="button" value="提交更改" onclick="submitDefaultEvent()">
<br>

<h4>客户端</h4>
<div id='client_list'>
<div id='client_list_lastEle' class='card' style='border-style: dashed;padding:2px;'>
<input type="text" id="new_client" style="overflow-x:visible;">&nbsp;<input id="btn_new_client" type="button" value="+" onclick="attachNewClient();">
</div>

<div style='clear:both;'></div>
</div>

</body>
</html>