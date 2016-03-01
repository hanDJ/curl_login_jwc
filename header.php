<?php
if (!isset($_COOKIE['cookie'])) {
	header("Location:login.php");
}
include './config.php';
function get_td_array($table){
	$table = preg_replace("/<table[^>]*?>/is","",$table);
	$table = preg_replace("/<tr[^>]*?>/si","",$table);
	$table = preg_replace("/<td[^>]*?>/si","",$table);
	$table = str_replace("</tr>","{tr}",$table);
	$table = str_replace("</td>","{td}",$table);
	//去掉 HTML 标记
	$table = preg_replace("'<[/!]*?[^<>]*?>'si","",$table);
	//去掉空白字符
	$table = str_replace(" ","",$table);
	$table = preg_replace('/\s(?=\s)/', '', $table);
	$table = preg_replace('/[\n\r\t]/', '', $table);
	$table = str_replace("&nbsp;","",$table);
	$table = explode('{tr}', $table);
	array_pop($table);
	foreach ($table as $key=>$tr) {
		$td = explode('{td}', $tr);
		$td = explode('{td}', $tr);
		array_pop($td);
		$td_array[] = $td;
	}
	return $td_array;
}