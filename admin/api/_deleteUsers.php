<?php
require_once '../../config.php';
require_once '../../function.php';
$connect=connect();
$ids=$_POST['ids'];
$statusSql="select status from users where id in (".implode(",",$ids).");";
$status=query($statusSql);
$response=['code'=>0,'msg'=>'操作失败'];
foreach ($status as $key => $value) {
    $bool=in_array('activated',$value);
    if ($bool) {
        $response['msg']='用户已激活，不可删除！';
        header("content-type:application/json;charset=utf-8");
        echo json_encode($response);
        return;
    }
}
// print_r($status);
// var_dump($bool);
$sql="delete from users where id in (".implode(",",$ids).");";
$res=mysqli_query($connect,$sql);
if ($res) {
    $response['code']=1;
    $response['msg']='操作成功';
}
header("content-type:application/json;charset=utf-8");
echo json_encode($response);
?>