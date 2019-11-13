<?php
$currentPage=$_POST["currentPage"];
$pageSize=$_POST['pageSize'];
$offset=($currentPage-1)*$pageSize;
include_once 'config.php';
include_once 'function.php';
$sql="select p.title,p.created,p.content,p.views,p.likes,c.name,p.feature,
(select count(*) from comments co where co.post_id=p.id) as commentsCount,u.nickname
from posts p join categories c on c.id=p.category_id 
join users u on u.id=p.user_id
where c.classname!=''
order by p.created desc limit $offset, $pageSize;";
$postArr=query($sql);
$sql2="select count(id) totalCount from posts;";
$totalCount=query($sql2);
$response=['code'=>0,'msg'=>'操作失败'];
if ($postArr) {
  $response['code']=1;
  $response['msg']='操作成功';
  $response['data']=$postArr;
  $response['totalCount']=$totalCount;
}
header("content-type:application/json;charset=utf-8");
echo json_encode($response);
?>