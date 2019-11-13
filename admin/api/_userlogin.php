<?php
include_once '../../config.php';
include_once '../../function.php';
$email=$_POST['email'];
$pwd=$_POST['pwd'];
$sql="SELECT * from users where email='$email' and `password`='$pwd' and `status`='activated';";
$res=query($sql);
$response=['code'=>0,'msg'=>'没有数据'];
if ($res) {
   $response['code']=1;
   $response['msg']='操作成功';
   session_start();
   $_SESSION['isLogin']=1;
   $_SESSION['userId']=$res[0]['id'];
}
header("content-type:application/json;charset=utf-8");
echo json_encode($response);
?>