<?php
include './header.php';
if(!isset($_POST['time'])){
	echo "非法访问！";
	exit();
}
$time = $_POST['time'];
$first = get_chengji($time);
if(empty($first) || count($first[2]) == 1){
	$cont = "暂无您的成绩信息！";
}else{
	$cont = '<table class="am-table">
	    		<tr>
		    		<th>课程名称</th>
		    		<th>成绩</th>
		    		<th>学时</th>
		    		<th>绩点</th>
		    	</tr>';
	foreach ($first as $v) {
		if(count($v) == 6){
			$cont .= '<tr>
				    		<td>'.$v[0].'</td>
				    		<td>'.$v[1].'</td>
				    		<td>'.$v[2].'</td>
				    		<td>'.$v[5].'</td>
				    	</tr>';
		}
	}
	$cont .= '</table>';
}
echo $cont;
function get_chengji($t){
	$year = substr($t, 0, 4);
	if(preg_match('/秋/', $t)){
		$term = '3';
	}else{
		$term = '1';
	}
	$ch = curl_init("http://www.ctgu.edu.cn/portal/xs_score.jsp?urltype=tree.TreeTempUrl&wbtreeid=1050");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "xh={$_COOKIE['jwid']}&SchoolYear={$year}&SchoolTerm={$term}");
	curl_setopt($ch, CURLOPT_COOKIE, 'JSESSIONID='.$_COOKIE['cookie']);
	$str = curl_exec($ch);
	curl_close($ch);
	preg_match('/<table width="100%" border="0"  align="center">(.*?)<\/table>/is', $str, $m);
	$td = get_td_array($m[1]);
	return $td;
}
?>