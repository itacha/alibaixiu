<?php
$name=$_POST['name'];
require_once '../../config.php';
require_once '../../function.php';
$countSql="select count(*) as count from categories where `name`='{$name}';";
$count=query($countSql);
$response=['code'=>0,'msg'=>'操作失败'];
$connect=connect();
if ($count[0]['count']>0) {
    $response['msg']='分类名称已存在';
}else{
    $addResult=addData($connect,"categories",$_POST);
    if ($addResult) {
        $response['code']=1;
        $response['msg']='操作成功';
        $newSql="select id from categories where `name`='{$name}';";
        $new=query($newSql);
        $response['id']=$new[0]['id'];
    }
}
header("content-type:application/json;charset=utf-8");
echo json_encode($response);
?>