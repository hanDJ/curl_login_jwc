<?php
	include './header.php';
	$ch = curl_init("http://www.ctgu.edu.cn/portal/xs_schedule.jsp?urltype=tree.TreeTempUrl&wbtreeid=1049");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "xh={$_COOKIE['jwid']}&SchoolYear=".YEAR."&SchoolTerm=".TERM);
	curl_setopt($ch, CURLOPT_COOKIE, 'JSESSIONID='.$_COOKIE['cookie']);
	$str = curl_exec($ch);
	curl_close($ch);
	preg_match('/<table width="100%" border="0"  align="center">(.*?)<\/table>/is', $str, $m);
	$td = get_td_array($m[1]);
	foreach ($td as $k => $v) {
		if(count($v) == 6){
			$arr[$v[2]][] = $v;
		}
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>查课表</title>
	<meta name="viewport" id="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection" content="telephone=no">
	<script type="text/javascript">
		document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
			WeixinJSBridge.call('hideOptionMenu');
		});
	</script>
    <link href="http://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body{background-color: #F1F1F1;}
		ul{list-style: none; padding: 15px 0 10px 0; margin: 0; height: 49px; background-color: #FFF;}
		li {float: left; width:14%; text-align: center;}
		a{color: #000; font-weight: bold; padding:4px;}
		a:hover{text-decoration: none; color: #fff; border-radius:12px; background: #418BCA;}
		.active{color: #fff; border-radius:12px; -webkit-border-radius:12px; background: #418BCA;}
		.active a{color: #fff;}
		.content{margin-top: 10px;}
		.content>div{display: none;}
		.content .show{display: block;}
		.box{background-color: #FFF; padding:10px; border: 1px solid #F1F1F1;}
		h4{font-weight: bold; line-height: 24px;}
		header{
			background-color: #418BCA; 
			height: 44px;
			padding-top: 4px;
		}
		.ui-groupbutton {
			display: table;
			font-size: 1em;
			border-color: rgba(0, 0, 0, 0);
			white-space: nowrap;
			margin: 0px auto;
		}
		.ui-button {
			display: inline-block;
			border-radius: 3px;
			padding: 6px 10px;
			margin: 4px 0;
			border: 1px solid #fff;
			color: #fff;
			font-size: 13px;
			line-height: 13px;
			text-align: center;
			background-color: transparent;
			-webkit-appearance: none;
			text-decoration: none;
			-webkit-box-sizing: border-box;
		}
		.ui-groupbutton .ui-button:first-child {
			border-radius: 3px 0 0 3px;
			border-right-width: 0;
		}
		.ui-groupbutton .ui-button:last-child {
			border-radius: 0 3px 3px 0;
			border-width: 1px;
		}
		.js-active{
			background-color: #fff;
			color: #418BCA;
		}
	</style>
</head>
<body>
	<header>
		<div class="ui-groupbutton">
            <div class="ui-button js-active" data-href="#">查课表</div>
            <div class="ui-button" data-href="chengji.php">查成绩</div>
        </div>
	</header>
	<ul class="tabs">
		<li><a href="#" class="active" data-week="0">一</a></li>
		<li><a href="#" data-week="1">二</a></li>
		<li><a href="#" data-week="2">三</a></li>
		<li><a href="#" data-week="3">四</a></li>
		<li><a href="#" data-week="4">五</a></li>
		<li><a href="#" data-week="5">六</a></li>
		<li><a href="#" data-week="6">日</a></li>
	</ul>
	<div class="content">
	<?php 
	for($i=1;$i<8;$i++){
		echo "<div>";
		if(isset($arr[$i])){
			foreach ($arr[$i] as $v) {
				echo '<div class="box">
							<h4>'.$v[0].'</h4>
							<p><span class="glyphicon glyphicon-map-marker"></span> '.$v[4].'</p>
							<p><span class="glyphicon glyphicon-user"></span> '.$v[5].'</p>
							<p><span class="glyphicon glyphicon-time"></span> '.$v[1].'周 '.$v[3].'节</p>
						</div>';
			}
		}
		echo "</div>";
	}
	?>
	</div>
</body>
<script src="http://cdn.bootcss.com/jquery/2.1.3/jquery.min.js"></script>
<script>
$(".content").children().eq(0).addClass("show");
$("a").click(function(){
	$(".active").removeClass("active");
	$(this).addClass("active");
	$(".show").removeClass("show");
	$(".content").children().eq($(this).data('week')).addClass("show");
})
$('.ui-button').click(function(){
	location.href= $(this).data('href');
});
</script>
</html>