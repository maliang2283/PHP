<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<load href="__PUBLIC__/Css/public.css"/>
<title>跳转提示</title>
<style type="text/css">
	p{
		line-height: 30px;
	}
</style>
</head>
<body>
	<!--  -->
<!--  -->
<div style="margin:0 auto;width:600px;padding:20px 0;margin-top:180px;text-align:center;">
	<p class="success" style="color:#e51937;font-size:15px;"><?php echo($message); ?></p>
	<p class="error" style="color:#e51937;font-size:15px;"><?php echo($error); ?></p>
	<p class="jump">页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
</p>

</div>



<!-- foot -->


<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>