<?php
require_once '../../config.php';
require_once '../../function.php';
$sql="select * from categories";
$categoryArr=query($sql);
$response=['code'=>0,'msg'=>'操作失败'];
if ($categoryArr) {
    $response['code']=1;
    $response['msg']='操作成功';
    $response['data']=$categoryArr;
}
header("content-type:application/json;charset=utf-8");
echo json_encode($response);
?>