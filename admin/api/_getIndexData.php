<?php
require_once '../../config.php';
require_once '../../function.php';
$sql="select count(id) as count from posts";
$res=query($sql);
$postCount=$res[0]['count'];
$sql2="select count(id) count from posts where status='drafted';";
$res2=query($sql2);
$drafted=$res2[0]['count'];
$sql3="select count(id) count from categories;";
$res3=query($sql3);
$category=$res3[0]['count'];
$sql4="select count(id) count from comments;";
$res4=query($sql4);
$comments=$res4[0]['count'];
$sql5="select count(id) count from comments where status='held';";
$res5=query($sql5);
$held=$res5[0]['count'];
$response=['code'=>0,'msg'=>'操作失败'];
if ($postCount&&$drafted&&$category&&$comments&&$held) {
    $response['code']=1;
    $response['msg']='操作成功';
    $response['postCount']=$postCount;
    $response['drafted']=$drafted;
    $response['category']=$category;
    $response['comments']=$comments;
    $response['held']=$held;
}
header("content-type:application/json;charset=utf-8");
echo json_encode($response);
?>