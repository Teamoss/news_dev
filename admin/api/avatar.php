<?php 
//根据邮箱获取头像
header('Content-Type: text/html; charset=utf-8');
require_once '../../config.php' ;
//接收传递过来邮箱
if(empty($_GET['email'])) {
	exit('缺少必要参数');
}
$email=$_GET['email'];

//连接数据库获取头像
$conn = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

if(!$conn) {
	exit('数据库连接失败');
}
$res = mysqli_query($conn, "select avatar from users where email= '{$email}' limit 1; ");

if(!$res) {
	exit('查询失败');
}

$row = mysqli_fetch_assoc($res);
echo $row['avatar'];
 ?>

