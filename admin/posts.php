<?php 
  
  require_once '../functions.php' ;
 
 xiu_get_current_user();
 
 $where = '1 = 1';
 $search = '';
// 分类筛选
if (isset($_GET['category']) && $_GET['category'] !== 'all') {

  //string(31) "1 = 1 and posts.category_id = 2"
   $where .= ' and posts.category_id =' . $_GET['category'] ;
   // string(11) "&category=2" 
   $search .= '&category=' . $_GET['category'];
}

// 状态筛选
if (isset($_GET['status']) && $_GET['status'] !== 'all') {

   $where .= " and posts.status = '{$_GET['status']}' " ; 
   $search .= '&status=' . $_GET['status'];
}




//设置页面显示数据条数
 $size = 20;
//获取当前页面数
 $page = empty($_GET['page']) ? 1 : $_GET['page'] ;




//获取最大页码数
$total_count = (int)xiu_fetch_one("select count(1) as count from posts
inner join categories on posts.category_id = categories.id
inner join users on posts.user_id = users.id ;")['count'] ;

$total_pages = (int)ceil($total_count/$size);

 if($page <1) {
  $page = 1 ;
 }
 if($page>$total_pages) {
  $page = $total_pages;
 }
//设置显示页数为5，
 $visiables = 5;
 $begin = $page - ($visiables - 1)/2;
 $end = $begin + $visiables - 1;
 //确保开始页码不会小于1,最大页面不大于有数据显示页面
 $begin = $begin < 1 ? '1' : $begin;
 $end = $end > $total_pages ? $total_pages : $end ;

 // 获取翻页需要越过数据查询
$offset = ($page - 1) * $size ;

 //获取所有数据
$posts = xiu_fetch_all("select
  posts.id,
  posts.title,
  users.nickname as user_name,
  categories.name as category_name,
  posts.created,
  posts.status
from posts
inner join categories on posts.category_id = categories.id
inner join users on posts.user_id = users.id
where {$where}
order by posts.created desc
limit {$offset}, {$size};");

$categories = xiu_fetch_all('select * from categories;');

//转换文章状态显示
function convert_post($status) {
  $dict = array (
 'published' => '已发布',
 'drafted' => '草稿箱',
 'trashed' => '回收站'
  ) ;
   return  isset($dict[$status])? $dict[$status] : '未知';
}

//转化时间格式
function convert_date ($created) {
  //2019-05-28 08:08:00
   date_default_timezone_set('PRC'); //配置时区
  $timestamp = strtotime($created);
  return date('Y年m月d日<b\r>H:i:s', $timestamp);
}


?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <link rel="stylesheet" href="/baixiu/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/baixiu/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/baixiu/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/baixiu/static/assets/css/admin.css">
  <script src="/baixiu/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
<?php  include 'inc/navbar.php' ?> 
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有文章</h1>
        <a href="post-add.php" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
          <select name="category" class="form-control input-sm">
            <option value="all">所有分类</option>
            <?php foreach ($categories as $item): ?>
              <option value="<?php echo $item['id']; ?>" <?php echo isset($_GET['category']) && $_GET['category'] == $item['id']? 'selected' : '' ; ?>><?php echo $item['name'] ; ?></option>
            <?php endforeach ?>
          </select>

          <select name="status" class="form-control input-sm">
            <option value="all" >所有状态</option>
            <option value="published" <?php echo isset($_GET['status']) && $_GET['status'] == 'published'? 'selected' : '' ; ?>>已发布</option>
            <option value="drafted" <?php echo isset($_GET['status']) && $_GET['status'] == 'drafted'? 'selected' : '' ; ?>>草稿</option>
            <option value="trashed" <?php echo isset($_GET['status']) && $_GET['status'] == 'trashed'? 'selected' : '' ; ?>>回收站</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="?page=<?php echo $page-1 ; ?>">上一页</a></li>
        <?php for($i = $begin; $i <= $end; $i++):  ?>
        <li<?php echo $i == $page ? ' class="active"' : '' ?>><a href="?page=<?php echo $i . $search ; ?>"><?php echo $i ; ?></a></li>
          <?php endfor ?>  
          <li><a href="?page=<?php echo $page+1 ; ?>">下一页</a></li>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
         <?php foreach ($posts as $item): ?>
          <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td><?php echo $item['title']; ?></td>
            <!-- <td><?php // echo get_user($item['user_id']); ?></td>
            <td><?php // echo get_category($item['category_id']); ?></td> -->
            <td><?php echo $item['user_name']; ?></td>
            <td><?php echo $item['category_name']; ?></td>
            <td class="text-center"><?php echo convert_date($item['created']); ?></td>
            <!-- 一旦当输出的判断或者转换逻辑过于复杂，不建议直接写在混编位置 -->
            <td class="text-center"><?php echo convert_post($item['status']); ?></td>
            <td class="text-center">
              <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
              <a href="/baixiu/admin/post-delete.php?id=<?php echo $item['id'] ?>" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
          <?php endforeach ?> 
        </tbody>
      </table>
    </div>
  </div>

  <?php $current_page ='posts' ; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/baixiu/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/baixiu/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
