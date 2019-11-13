<?php
require_once '../../config.php';
require_once '../../function.php';
$currentPage=$_POST['currentPage'];
$pageSize=$_POST['pageSize'];
$offset=($currentPage-1)*$pageSize;
$status=$_POST['status'];
$categoryId=$_POST['categoryId'];
$where=" where 1=1 ";
if ($status!='all') {
   $where.="and p.`status`='$status '";
}
if ($categoryId!='all') {
   $where.=" and p.category_id=$categoryId ";
}
$sql="select p.id,p.title,u.nickname,c.`name`,p.created,p.`status` from posts p INNER JOIN users u on p.user_id=u.id
INNER JOIN categories c on p.category_id=c.id ".$where."limit $offset,$pageSize;";
$totalCountSql="select count(*) as count from posts p".$where.";";
// $totalFilt="select count(*) as count from posts".$where;
// echo $totalCountSql;
$res=query($sql);
$totalCount=query($totalCountSql);
$response=['code'=>0,'msg'=>'没有数据'];
if ($res) {
    $response['code']=1;
    $response['msg']='操作成功';
    $response['data']=$res;
    $response['totalCount']=$totalCount[0]['count'];
    $response['sql']=$sql;
}
header("content-type:application/json;charset=tuf-8");
echo json_encode($response);
?>