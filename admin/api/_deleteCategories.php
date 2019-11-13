<?php
require_once '../../config.php';
require_once '../../function.php';
header("content-type:application/json;charset=utf-8");
$connect=connect();
$ids=$_POST['ids'];
$sqlCount="select count(p.id) as count from categories c INNER JOIN posts p on c.id=p.category_id 
where c.id in (".implode(",",$ids).");";
$count=query($sqlCount);
$response=['code'=>0,'msg'=>'操作失败'];
if ($count[0]['count']>0) {
    $response['msg']='分类下有文章，不能删除！';
    echo json_encode($response);
    return;
}
$sql="delete  from categories where id in (".implode(",",$ids).");";
$res=mysqli_query($connect,$sql);
if ($res) {
    $response['code']=1;
    $response['msg']='操作成功';
}
echo json_encode($response);
?>