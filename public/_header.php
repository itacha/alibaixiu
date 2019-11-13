<?php
include_once './config.php';
include_once 'function.php';
//连接数据库，创建对象
// $conn=mysqli_connect(DB_HOST,DB_USER,DB_PWD,DB_NAME);
$sql="select * from categories where classname!=''";
// 查询
// $res=mysqli_query($conn,$sql);
// $arr=[];
// // 将数据集合转换成二维数组
// while ($row=mysqli_fetch_assoc($res)) {
//   $arr[]=$row;
// }
$arr=query($sql);
?>
<div class="header">
      <h1 class="logo"><a href="index.php"><img src="static/assets/img/logo.png" alt=""></a></h1>
      <ul class="nav">
        <?php
foreach ($arr as $key => $value) {?>
        <li><a href="list.php?categories-id=<?php echo $value['id'] ?>"><i class="fa <?php echo $value['classname'] ?>"></i><?php echo $value['name'] ?></a></li>  
<?php } ?>
      </ul>
      <div class="search">
        <form>
          <input type="text" class="keys" placeholder="输入关键字">
          <input type="submit" class="btn" value="搜索">
        </form>
      </div>
      <div class="slink">
        <a href="javascript:;">链接01</a> | <a href="javascript:;">链接02</a>
      </div>
    </div>
