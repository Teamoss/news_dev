 <?php 
 header('Content-Type: text/html; charset=utf-8');
 //配置文件
 require_once '../config.php';
 //设置session防止用户直接登录后台
 session_start();

 //用户从管理页面推出后清除session

 if (isset($_GET['action'])) {
 	session_unset($_SESSION['current_login_user']);
 }

 //校验用户提交账号和密码
 function login () {
	if (empty($_POST['email'])) {
		$GLOBALS['message'] = '请填写邮箱';
		return;
	}
	if (empty($_POST['password'])) {
		$GLOBALS['message'] = '请填写密码';
		return;
	}

	$email=$_POST['email'];
	$password=$_POST['password'];

	// if($email!=='admin@163'){
	//   $GLOBALS['message'] = '账号错误';
	//   return;
	// }
	// if($password!=='123456'){
	//   $GLOBALS['message'] = '密码错误';
	//   return;
	// }

	// 连接数据库校验
	 $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
   if (!$conn) {
    exit('<h1>连接数据库失败</h1>');
  }
 
	$query=mysqli_query($conn, "select * from users where email = '{$email}' limit 1;");

	if (!$query) {
     $GLOBALS['message'] = '登录失败';
    return;
  }

  //获取登录用户
   $user=mysqli_fetch_assoc($query);
   
   if(!$user) {
   	$GLOBALS['message'] = '用户不存在';
    	return;
   }

   //密码校验
   if($user['password']!==$password) {
   	$GLOBALS['message'] = '密码错误';
    	return;
   }


   $_SESSION['current_login_user']=$user;

	header('location:/baixiu/admin');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
		 login();
}
 ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<title>Sign in &laquo; Admin</title>
	<link rel="stylesheet" href="/baixiu/static/assets/vendors/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="/baixiu/static/assets/css/admin.css">
	<link rel="stylesheet" type="text/css" href="/baixiu/static/assets/vendors/animate/animate.css ">
</head>
<body>

	<div class="login ">
		<form class="login-wrap <?php echo isset($message)?'jello animated':'' ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post'>
			<img class="avatar" src="/baixiu/static/assets/img/default.png">
			 <?php if (isset($message)): ?>
			<div class="alert alert-danger">
				<strong>错误！</strong> <?php echo $message; ?>
			</div>
			<?php endif ?>      
			<div class="form-group">
				<label for="email" class="sr-only">邮箱</label>
				<input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus>
			</div>
			<div class="form-group">
				<label for="password" class="sr-only">密码</label>
				<input id="password" name="password" type="password" class="form-control" placeholder="密码">
			</div>
			<button class="btn btn-primary btn-block">登 录</button>
		</form>
	</div>

	//根据用户账号获取头像

	<script src="/baixiu/static/assets/vendors/jquery/jquery-1.12.1.min.js"></script>
	<script type="text/javascript">
		$(function($){
			var emailFormat = /^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/
             $('#email').on('blur',function(){
                 var value = $(this).val()
                 if(!value || !emailFormat.test(value)) return
                   $.get('/baixiu/admin/api/avatar.php',{email: value},function(res){
                             if(!res) return;
                               $('.avatar').fadeOut(function () {
            // 等到 淡出完成
            $(this).on('load', function () {
              // 图片完全加载成功过后
              $(this).fadeIn()
            }).attr('src', res)
          })
                   })
             })
		})

	</script>
</body>
</html>
