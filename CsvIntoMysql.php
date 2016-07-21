<?php
/**
// csv文件格式
user_id,user_name,password,sex,last_login,last_ip,visit_count,msn,qq,mobile_phone
3452944,eagle,b09315ea09c6d3b5680094257f1f70e4,0,1465437719,116.6.104.7,238,eaglekbr,393425760,13815413996
3452945,晴雨,4f66ae52c13c3eec355c05cd1990b098,0,1433464366,113.98.247.92,14,kakijing,835960968,15850602042
3452946,惆怅,b3eb979267d5385e1d5b703f75883c7b,1,1437158118,113.98.247.92,31,as15813728296,626539826,15813728296
3452947,骨灰级,54fa9698627c7161f2a275d19bc719b7,1,1465254635,113.98.247.92,1537,1234124,2497239857,13066813567
3452948,test,098f6bcd4621d373cade4e832627b4f6,0,1468350167,192.168.42.123,441,2345,32456345,15013873383
3452949,柴珺,567d22518f0056aa1be6562aa9b85ba9,0,1418773818,112.95.232.195,1,cjy10170,50340687,13823796008
*/

/**
 * 读取csv文件,分行插入数据库（csv文件第一行必须是字段名）
 *
 * @param string $filename 文件名
 * @param int $length 一次读取的行数
 * @param string $table 表名
 */
function CsvIntoMysql($filepname, $length, $table){
	set_time_limit(0);
	ini_set('mysql.connect_timeout', 300);
	ini_set('default_socket_timeout', 300);
	include 'CsvReader.class.php';
	$csv_obj = new CsvReader($filepname);

	// 连接数据库
	if (!($connection = mysqli_connect('127.0.0.1', 'root', 'root', 'demo'))) exit('连接 MySQL 失败！');
	mysqli_query($connection, "SET NAMES 'utf8'");

	$lines = $csv_obj->get_lines();
	$length = $length;
	$number = ceil($lines/$length);
	$start = 0;
	$all_start_time = microtime(true);
	for ($i = 0; $i < $number; $i++){
		$start_time = microtime(true);
		$data = $csv_obj->get_data($length,$start);
		if ($i == 0) { // 获取表字段
			$title_str = _get_title_str($data[0]);
		}
		// 转换数组插入数据库
		$value_str = '';
		$mark = $i;
		foreach ($data as $key => $value) {
			if($mark == 0) { // 过滤掉第一行字段名
				$mark = 1;
				continue;
			}
			if ($value[0] != null){
				$value_str .= _get_value_str($value);
			}
		}
		$value_str = rtrim($value_str,',');	
		$sql_str = "INSERT INTO {$table}{$title_str} VALUES {$value_str}";
		// echo $sql_str;
		// exit;
		mysqli_query($connection, $sql_str);
		if(mysqli_errno($connection))
		{
			echo mysqli_errno($connection).'：';
			echo mysqli_error($connection);
			echo '<br />';
			echo '插入失败！';
			return;
		}
		$end_time = microtime(true);
		echo '插入',$start,'--',$start+$length,'成功!','   共用时 ',($end_time-$start_time),' 秒.<br>';
		$start = $start + $length;
	}
	$all_end_time = microtime(true);
	echo $lines-1,' 条数据,每次插入',$length,',全部插入共用时 ',($all_end_time-$all_start_time),' 秒.<br>';
	mysqli_close($connection);
}


/**
 * 获取表字段名 并拼接成字符串
 * @param $arr
 * @return string 字符串 （字段1,字段2,...）
 */
function _get_title_str($arr){
	$str = "(";
	foreach ($arr as $key => $value) {
		$str .= $value.",";
	}
	$str = rtrim($str,',');
	$str .= ")";
	return $str;
}


/**
 * 获取数据 并拼接成字符串
 * @param $arr
 * @return string 字符串 （'1383043','Petter',...）
 */
function _get_value_str($arr){
	$str = "(";
	foreach ($arr as $key => $value) {
		$str .= "'" . $value ."',";
	}
	$str = rtrim($str,',');
	$str .= "),";
	return $str;
}

CsvIntoMysql('20160719193616.csv',10000,'users_6');