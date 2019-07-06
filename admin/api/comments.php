<?php 

require_once  '../../functions.php' ;

$page = empty($_GET['page'])? 1 : $_GET['page'] ;


$size = 20 ;
$offest = ($page-1) * $size;



$comments = xiu_fetch_all("select  comments.*,
posts.title as post_title
from comments
INNER JOIN posts on comments.post_id = posts.id
order by comments.created desc
limit {$offest}, {$size};") ;



//获取最大页码数
$total_count = (int)xiu_fetch_one("select count(1) as count from comments
inner join posts on comments.post_id = posts.id;")['count'] ;
$totalPages = (int)ceil($total_count/$size);

$json = json_encode(array(
 
  'totalPages' =>$totalPages,
  'comments' => $comments

));

//设置响应体为json格式
header("Content-type:application/json;charset=utf-8");

echo $json;



 ?>