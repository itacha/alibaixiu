<?php
require_once '../../config.php';
require_once '../../function.php';
$connect=connect();
$id=$_POST['id'];
unset($_POST['id']);
$sql="update users set ";
foreach ($_POST as $key => $value) {
    $sql.="$key='$value',";
}
$sql=substr($sql,0,-1);
$sql.=" where id=$id;";
// echo $sql;
$res=mysqli_query($connect,$sql);
$response=['code'=>0,'msg'=>'操作失败'];
if ($res) {
    $response['code']=1;
    $response['msg']='操作成功';
}
header("content-type:application/json;charset=utf-8");
echo json_encode($response);
?>