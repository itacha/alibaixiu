<?php
require_once '../../config.php';
require_once '../../function.php';
$id=$_POST['id'];
$countPostSql="select count(*) as count from posts where category_id=$id;";
$connect=connect();
$countPost=query($countPostSql);
$sql="delete from categories where id=$id;";
// print_r($countPost);
$response=['code'=>0,'msg'=>'操作失败'];
if ($countPost[0]['count']>0) {
    $response['msg']='该分类下有文章，不能删除！';
}else{
    $res=mysqli_query($connect,$sql);
    if ($res) {
        $response['code']=1;
        $response['msg']='操作成功';
    }
}
header("content-type:application/json;charset=utf-8");
echo json_encode($response);
?>