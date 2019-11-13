<?php
require_once '../../config.php';
require_once '../../function.php';
$currentPage=$_POST['currentPage'];
$pageSize=$_POST['pageSize'];
$offset=($currentPage-1)*$pageSize;
$sql="select c.id,c.author,c.content,c.created,c.`status`,p.title 
from comments c INNER JOIN posts p on c.post_id=p.id LIMIT $offset,$pageSize;";
$res=query($sql);
$totalCountSql="select count(id) count from comments;";
$totalCount=query($totalCountSql);
$response=['code'=>0,'msg'=>'操作失败'];
if ($res) {
    $response['code']=1;
    $response['msg']='操作成功';
    $response['data']=$res;
    $response['totalPage']=$totalCount[0]['count'];
    $response['sql']=$sql;
}
header("content-type:application/json;charset=utf-8");
echo json_encode($response);
?>