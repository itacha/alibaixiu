<?php
require_once '../../config.php';
require_once '../../function.php';
$connect=connect();
$id=$_POST['id'];
$oldpwd=$_POST['oldpwd'];
$pwd=$_POST['newpwd'];
$sqlConfirm="select password from users where id=$id;";
$result=query($sqlConfirm);
$response=['code'=>0,'msg'=>'操作失败'];
header("content-type:application/json;charset=utf-8");
if ($result[0]['password']!=$oldpwd) {
    $response['msg']='旧密码输入错误，如忘记密码可联系管理员';
    echo json_encode($response);
    return;
}
$sql="update users set password=$pwd where id=$id";
$res=mysqli_query($connect,$sql);
if ($res) {
    $response['code']=1;
    $response['msg']='操作成功';
}
echo json_encode($response);
?>