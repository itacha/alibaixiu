<?php
require_once '../../config.php';
require_once '../../function.php';
$id=$_POST['id'];
$statusSql="select status from users where id=$id;";
$connect=connect();
$status=query($statusSql);
// echo $status[0]['status'];
$sql="delete from users where id=$id;";
$response=['code'=>0,'msg'=>'操作失败'];
if ($status[0]['status']=='activated') {
    $response['msg']='用户已激活，不能删除！';
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