<?php
require_once '../../config.php';
require_once '../../function.php';
// print_r($_POST);
$connect=connect();
$sql="insert into posts values(null,'{$_POST['slug']}','{$_POST['title']}',
'{$_POST['feature']}','{$_POST['created']}','{$_POST['content']}',
0,0,'{$_POST['status']}',{$_POST['userId']},{$_POST['category']}); ";
// echo $sql;
$res=mysqli_query($connect,$sql);
$response=['code'=>0,'msg'=>'操作失败'];
if ($res) {
   $response['code']=1;
   $response['msg']='操作成功';
//    $response['sql']=$sql;
}
header("content-type:application/json;charset=utf-8");
echo json_encode($response);
?>