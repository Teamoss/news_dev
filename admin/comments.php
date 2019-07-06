<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
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
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none">
          <button class="btn btn-info btn-sm">批量批准</button>
          <button class="btn btn-warning btn-sm">批量拒绝</button>
          <button class="btn btn-danger btn-sm">批量删除</button>
        </div>
        <!-- 分页器 -->
        <ul class="pagination pagination-sm pull-right">
        </ul>
      </div>
      <table id="main"  class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>作者</th>
            <th>评论</th>
            <th>评论在</th>  
            <th>提交于</th>
            <th>状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <!-- :class="scope.row.status==1? 'active':'native'" -->
<!--           <tr :class="r.status=='held'? 'warning' : r.status=='rejected'? 'danger':''"  v-for = "r in rows" >
            <td class="text-center"><input type="checkbox"></td>
            <td>{{r.author}}</td>
            <td>{{r.content}}</td>
            <td>{{r.post_title}}</td>
            <td>{{r.created}}</td>
            <td>{{r.status}}</td>
            <td class="text-center" width="150px">
              <a v-if="r.status=='held'" href="post-add.php" class="btn btn-warning btn-xs">拒绝</a>
              <a v-if="r.status=='held'" href="post-add.php" class="btn btn-info btn-xs">批准</a>
              <a href="delete.php" class="btn btn-danger btn-xs">删除</a> 
            </td>
          </tr> -->
          
        </tbody>
      </table>
    </div>
  </div>

  <?php $current_page ='comments' ; ?>
  <?php include 'inc/sidebar.php' ?>

  <script id="comments_tmpl" type="text/x-jsrender">
    {{for comments}}
   {{!-- <tr><td>{{:#index}}</td><td>{{:content}}</td></tr>--}}
   <tr {{if status == 'held'}} class= "warning" {{else status == "rejected"}} class= "danger"
   {{/if}} data-id="{{:id}}">
            <td class="text-center"><input type="checkbox"></td>
            <td>{{:author}}</td>
            <td>{{:content}}</td>
            <td>{{:post_title}}</td>
            <td>{{:created}}</td>
            <td>
           {{if status =='approved'}}
            批准
           {{else  status == 'held'}}
            待审
           {{else status == 'rejected'}}
            拒绝
           {{/if}}
            </td> 
          <td class="text-center" width="150px">
            {{if status == 'held'}}
              <a  href="post-add.php" class="btn btn-warning btn-xs">拒绝</a>
              <a  href="post-add.php" class="btn btn-info btn-xs">批准</a>
              {{/if}}
              <a  href="javascript:;" class="btn btn-danger btn-xs btn-delete">删除</a> 
            </td>
          </tr>
  {{/for}}
  </script>

  <script src="/baixiu/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/baixiu/static/assets/vendors/jsrender/jsrender.js"></script>
  <script src="/baixiu/static/assets/vendors/vue/vue.min.js"></script>
  <script src="/baixiu/static/assets/vendors/twbs-pagination/jquery.twbsPagination.js"></script>
  <script src="/baixiu/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>

   var currentPage = 1

    function loadPageData(page) {
         
          $('tbody').fadeOut()
          $.getJSON("/baixiu/admin/api/comments.php",{ page: page }, function (data) {
            $(".pagination").twbsPagination('destroy')
            $(".pagination").twbsPagination({ 
                   //total总记录数，就是多少条数据  pages总页数
                     totalPages: data.totalPages,//总页数
                     startPage: page,//起始页
                     visiblePages: data.totalPages>5 ? 5 : data.totalPages,//显示页面数
                       first:'首页',
                       last:'末页',
                       prev:'上一页',
                       next:'下一页',
                       initiateStartPageClick: false, //在插件初始化时，触发点击事件 设置false不触发
                       onPageClick: function(e,page) {
                        loadPageData(page)
                       }
                   })
                  // vue渲染数据   
                   //   var v = new Vue({
                   //     el: '#main',
                   //     data: {
                   //         rows: data.comments
                   //     }        
                   // })
                   
                   //模板引擎渲染
                   var html = $('#comments_tmpl').render({ comments:data.comments})
                   $('tbody').html(html).fadeIn()
                   currentPage = page
             });       
    }
  
    loadPageData(currentPage);

    //删除评论
    $('tbody').on('click','.btn-delete',function() {
             
       // 获取删除按钮父元素tr      
       var tr = $(this).parent().parent()
       // 获取tr自定义属性 id
       var id = tr.data('id')

        $.get('/baixiu/admin/comment-delete.php', {id: id},function(res){

                  if(!res) return 
                  loadPageData(currentPage) 
                  //tr.remove()
        })

        })


  </script>

  <script>NProgress.done()</script>
</body>
</html>
