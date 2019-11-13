<?php
require_once '../../config.php';
require_once '../../function.php';
$response=['code'=>0,'msg'=>'没有数据'];
if ($_POST['id']) {
    $sql2="select * from users where id={$_POST['id']};";
    $user2=query($sql2);
    if ($user2) {
        $response['code']=1;
        $response['msg']='操作成功';
        $response['data']=$user2[0];
    }
}
header("content-type:application/json;charset=utf-8");
echo json_encode($response);
?>