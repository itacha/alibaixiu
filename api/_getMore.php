<?php
include_once '../config.php';
include_once '../function.php';
$currentPage=$_GET['currentPage'];
$pageSize=$_GET['pageSize'];
$categoryId=$_GET['categoryId'];
$offset=($currentPage-1)*$pageSize;
$sql="select p.id,p.title,p.created,p.content,p.views,p.likes,c.name,p.feature,
(select count(*) from comments co where co.post_id=p.id) as commentsCount,u.nickname
from posts p join categories c on c.id=p.category_id
join users u on u.id=p.user_id
where p.category_id = $categoryId
order by p.created desc limit $offset, $pageSize;";
$postArr=query($sql);

$sqlCount="select count(id) as postCount from posts where category_id=$categoryId;";
$countArr=query($sqlCount);
$totalCount=$countArr[0]["postCount"];

$response=["code"=>0,"msg"=>'操作失败'];
if ($postArr) {
   $response["code"]=1;
   $response["msg"]="操作成功";
   $response["data"]=$postArr;
   $response['totalCount']=$totalCount;
}

echo json_encode($response);
?>