<?php 
//封装公用函数

 require_once 'config.php' ;

 session_start() ;


 //通过session获取用户登录信息，没有则回到登录页面
 function xiu_get_current_user () {
   if(!isset($_SESSION['current_login_user'])) {
   //防止没有登录账号直接访问后台
     header('location: /baixiu/admin/login.php');
     exit();
   }
   return $_SESSION['current_login_user'];
 }


 // 获取数据库多条数据
    function xiu_fetch_all($sql) {
     
     $conn = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

     if(!$conn) {
     	exit('连接失败');
     }

     $query = mysqli_query($conn,$sql);

      if(!$query) {
     	return false ;
     }

     while ($row = mysqli_fetch_assoc($query)) {
             
          $resule[] = $row ; 
     }
           mysqli_free_result($query);
           mysqli_close($conn);
           return $resule;
 }

    //获取数据库单挑数据

     function xiu_fetch_one($sql) {  
       $res = xiu_fetch_all($sql);
       return isset($res[0]) ? $res[0] : null ;
     }


    //增加删除
    function xiu_execute($sql) {
       $conn = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
       if(!$conn) {
        exit('连接失败');
       }

       $query = mysqli_query($conn,$sql);
       if(!$query) {
        return false;
       }

       $affected_rows = mysqli_affected_rows($conn);
       mysqli_close($conn);
       return $affected_rows;

    }

 ?>

