<?php 
   header('Content-Type: text/html; charset=utf-8');
 require_once '../functions.php' ;


  if(empty($_GET['id'])) {
  exit('缺少必要参数');
  }

  $id = $_GET['id'];
  
  $rows = xiu_execute('delete from posts where id in (' . $id . ');'); 
  

  	header('location:/baixiu/admin/posts.php');
 

 ?>