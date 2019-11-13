<?php
require_once '../../config.php';
require_once '../../function.php';
session_start();
$userId=$_SESSION['userId'];
$sql="select nickname,avatar,email,slug,bio from users where id=$userId;";
$userAllSql="select * from users order by id;";
$userRes=query($sql);
$userAll=query($userAllSql);
$response=['code'=>0,'msg'=>'没有数据'];
if ($userRes) {
    $response['code']=1;
    $response['msg']='操作成功';
    $response['name']=$userRes[0]['nickname'];
    $response['avatar']=$userRes[0]['avatar'];
    $response['email']=$userRes[0]['email'];
    $response['slug']=$userRes[0]['slug'];
    $response['bio']=$userRes[0]['bio'];
    $response['userId']=$userId;
    $response['data']=$userAll;
}
header("content-type:application/json;charset=utf-8");
echo json_encode($response);
?>