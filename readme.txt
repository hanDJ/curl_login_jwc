<?php
//冲当浏览器作用
//首先要接受cookie
//处理cookie数据
//模拟登录就是模拟会话
	header('Content-Type:text/html;charset=utf-8');
	$curl=curl_init();//// 创建一个新cURL资源
	$url='www.***.com/index.php?p=back&&c=Admin&&a=Check';
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_POST,true);//以返回值形式输出
	$post_data=array('username'=>'admin','password'=>'12345abcd','captcha'=>'HHHH');
	curl_setopt($curl,CURLOPT_POSTFIELDS ,$post_data);
	curl_setopt($curl,CURLOPT_HEADER,true);//是否处理响应头数据
	curl_setopt($curl,CURLOPT_COOKIEJAR,$cookie_ir);//记录cookie
	$response_content=curl_exec($curl);//// 抓取URL并把它传递给浏览器
	//echo "<pre>";
	//var_dump($response_content);
	curl_close($curl);
	$curl=curl_init();
	// // 设置URL和相应的选项
	$url='www.***.com/index.php?p=back&&c=Manage&&a=index';
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_HEADER,true);//是否处理响应头数据
	curl_setopt($curl,CURLOPT_COOKIEFILE ,$cookie_ir);//发出请求时携带拥有的cookie：
	curl_exec($curl);//// 抓取URL并把它传递给浏览器
	curl_close($curl);
	/*
		环境要求
		---------------------------------
		PHP5.2+
		支持curl
		网站：https://sso.ctgu.edu.cn:7002/cas/login?service=http%3A%2F%2Fportal.ctgu.edu.cn%2Fportal%2Findex.jsp
		测试账号2013422231
		密码16958888
	*/
?>