<?php 
require_once '../functions.php' ;

xiu_get_current_user ();

$posts_count = xiu_fetch_one('select count(1) as num from posts;')['num'];
$categories_count = xiu_fetch_one('select count(1) as num from categories;')['num'];
$comments_count = xiu_fetch_one('select count(1) as num from comments;')['num'];


 ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
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
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.php" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $posts_count ; ?></strong>篇文章（<strong>2</strong>篇草稿）</li>
              <li class="list-group-item"><strong><?php echo $categories_count ; ?></strong>个分类</li>
              <li class="list-group-item"><strong><?php echo $comments_count ; ?></strong>条评论（<strong>1</strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4">
          <canvas id="chart"></canvas>
        </div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>
 <?php $current_page ='index' ; ?>
 <?php include 'inc/sidebar.php'; ?>

  <script src="/baixiu/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/baixiu/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/baixiu/static/assets/vendors/chart/chart.js"></script>
  <script>
    var ctx = document.getElementById('chart').getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        datasets: [
          {
            data: [<?php echo $posts_count; ?>, <?php echo $categories_count; ?>, <?php echo $comments_count; ?>],
            backgroundColor: [
              'skyblue',
              'pink',
              'gray',
            ]
          },
          {
            data: [<?php echo $posts_count; ?>, <?php echo $categories_count; ?>, <?php echo $comments_count; ?>],
            backgroundColor: [
              'skyblue',
              'pink',
              'gray',
            ]
          }
        ],

        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: [
          '文章',
          '分类',
          '评论'
        ]
      }
    });
  </script>
  <script>NProgress.done()</script>
</body>
</html>
