<div class="aside">
    <div class="profile">
      <img class="avatar" src="../static/uploads/avatar.jpg">
      <h3 class="name">布头儿</h3>
    </div>
    <ul class="nav">
      <li class="<?php echo $currentPage == 'index' ? 'active' : ''; ?>">
        <a href="index.php"><i class="fa fa-dashboard"></i>仪表盘</a>
      </li>
      <li>
        <?php
        $currentPage=strrchr($_SERVER['PHP_SELF'],'/');
        $currentPage=substr($currentPage,1);
        $pageArr=['posts.php','categories.php','post-add.php'] ;
        $bool=in_array($currentPage,$pageArr);
        // echo $currentPage;
        // var_dump($bool);
        ?>
        <a href="#menu-posts" class="<?php echo $bool?'':"collapsed" ?>" <?php echo $bool?'aria-expanded="true"':'' ?> data-toggle="collapse">
          <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-posts" class="collapse <?php echo $bool?"in":'' ?>" <?php echo $bool?'aria-expanded="true"':'' ?> >
          <li class="<?php echo $currentPage=='posts.php'?'active':'' ?>"><a href="posts.php">所有文章</a></li>
          <li class="<?php echo $currentPage=='post-add.php'?'active':'' ?>"><a href="post-add.php">写文章</a></li>
          <li class="<?php echo $currentPage=='categories.php'?'active':'' ?>"><a href="categories.php">分类目录</a></li>
        </ul>
      </li>
      <li class="<?php echo $currentPage=='comments'?'active':'' ?>">
        <a href="comments.php"><i class="fa fa-comments"></i>评论</a>
      </li>
      <li class="<?php echo $currentPage=='users'?'active':'' ?>">
        <a href="users.php"><i class="fa fa-users"></i>用户</a>
      </li>
      <li>
        <?php
          $pageArr=['nav-menus.php','slides.php','settings.php'];
          $bool=in_array($currentPage,$pageArr);
        ?>
        <a href="#menu-settings" class="<?php echo $bool?'':"collapsed" ?>" <?php echo $bool?'aria-expanded="true"':'' ?> data-toggle="collapse">
          <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-settings" class="collapse <?php echo $bool?"in":'' ?>" <?php echo $bool?'aria-expanded="true"':'' ?>>
          <li class="<?php echo $currentPage=='nav-menus.php'?'active':'' ?>"><a href="nav-menus.php">导航菜单</a></li>
          <li class="<?php echo $currentPage=='slides.php'?'active':'' ?>"><a href="slides.php">图片轮播</a></li>
          <li class="<?php echo $currentPage=='settings.php'?'active':'' ?>"><a href="settings.php">网站设置</a></li>
        </ul>
      </li>
    </ul>
  </div>

  <script src="../static/assets/vendors/jquery/jquery.min.js"></script>
  <script>
  $(function(){
    $.ajax({
      type:"post",
      url:"api/_getUserAvatar.php",
      success:function(response){
        if (response.code==1) {
          $(".avatar").attr('src',response.avatar);
        $(".name").text(response.name);
        }
    }
    })
  })
  </script>