<?php
header("Content-type:text/html;charset=utf-8;");
if(isset($_COOKIE['cookie'])){
	if(isset($_GET['type']) && $_GET['type'] == 'chengji'){
		header('Location: chengji.php');
	}else{
		header('Location: index.php');
	}
}
function curl_redir_exec($ch,$debug="") 
{ 
    static $curl_loops = 0; 
    static $curl_max_loops = 20; 

    if ($curl_loops++ >= $curl_max_loops) 
    { 
        $curl_loops = 0; 
        return FALSE; 
    } 
    curl_setopt($ch, CURLOPT_HEADER, true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $data = curl_exec($ch); 
    $debbbb = $data; 
    list($header, $data) = explode("\n\n", $data, 2); 
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

    if ($http_code == 301 || $http_code == 302) { 
        $matches = array(); 
        preg_match('/Location:(.*?)\n/', $header, $matches); 
        $url = @parse_url(trim(array_pop($matches))); 
        //print_r($url); 
        if (!$url) 
        { 
            //couldn't process the url to redirect to 
            $curl_loops = 0; 
            return $data; 
        } 
        $last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL)); 
    /*    if (!$url['scheme']) 
            $url['scheme'] = $last_url['scheme']; 
        if (!$url['host']) 
            $url['host'] = $last_url['host']; 
        if (!$url['path']) 
            $url['path'] = $last_url['path'];*/ 
        $new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query']?'?'.$url['query']:''); 
        curl_setopt($ch, CURLOPT_URL, $new_url); 
    //    debug('Redirecting to', $new_url); 

        return curl_redir_exec($ch); 
    } else { 
        $curl_loops=0; 
        return $debbbb; 
    } 
} 

if(isset($_POST['jwid'])){
	$jwid = $_POST['jwid'];
	$jwpwd = $_POST['jwpwd'];
	$cookie_file = tempnam('./tmp/', 'cookie');

	$ch = curl_init("https://sso.ctgu.edu.cn:7002/cas/login?service=http%3A%2F%2Fwww.ctgu.edu.cn%3A80%2Fportal%2Fxs_schedule.jsp%3Furltype%3Dtree.TreeTempUrl%26wbtreeid%3D1049");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
	$str = curl_exec($ch);
	curl_close($ch);
	preg_match('/<input type="hidden" name="lt" value="(.*?)" \/>/', $str, $m);
	$lt = $m[1];

	$ch = curl_init("https://sso.ctgu.edu.cn:7002/cas/login?service=http%3A%2F%2Fwww.ctgu.edu.cn%3A80%2Fportal%2Fxs_schedule.jsp%3Furltype%3Dtree.TreeTempUrl%26wbtreeid%3D1049");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_redir_exec($ch);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "loginType=0&username={$jwid}&password={$jwpwd}&lt={$lt}&_eventId=submit&Submit.x=40&Submit.y=10&Submit=%E7%99%BB%E9%99%86");
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
	$str = curl_exec($ch);
	curl_close($ch);
	if(!preg_match('/您提供的凭证有误/is', $str)){
		$cont = file_get_contents($cookie_file);
		preg_match_all('/JSESSIONID	(.*?)\n/is', $cont, $m);
		$cookie = $m[1][1];
		unlink($cookie_file);
		setcookie('cookie',$cookie,time()+3600);
		setcookie('jwid',$jwid,time()+7200);
		setcookie('jwpwd',$jwpwd,time()+7200);
		if(isset($_GET['type']) && $_GET['type'] == 'chengji'){
			header('Location: chengji.php');
		}else{
			header('Location: index.php');
		}
	}else{
		echo '<script>alert("你输入的信息有误，请重新输入！");</script>';
	} 
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>微教务</title>
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
	body{margin:10px;}
	</style>
</head>
<body>
	<form role="form" method="post">
	  <div class="form-group">
		<label for="jw_id">学号</label>
		<input type="text" class="form-control" name="jwid" placeholder="请输入学号">
	  </div>
	  <div class="form-group">
		<label for="jw_pwd">教务密码</label>
		<input type="password" class="form-control" name="jwpwd" placeholder="请输入密码">
	  </div>
	  <button type="submit" class="btn btn-primary btn-block">查询</button>
	  <br>
	  <br>
	  <center>版权所有：三峡大学学生处扬帆索源工作室</center>
	</form>
</body>
</html>