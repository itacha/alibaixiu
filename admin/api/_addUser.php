<?php
require_once '../../config.php';
require_once '../../function.php';
$sqlCount="select count(id) as count from users where email='{$_POST['email']}';";
$count=query($sqlCount);
$response=['code'=>0,'msg'=>'操作失败'];
if ($count[0]['count']>0) {
    $response['msg']='邮箱已注册，请重新设置！';
}else{
    $sql="insert into users values(null,'{$_POST['slug']}','{$_POST['email']}','{$_POST['password']}',
    '{$_POST['nickname']}','/static/uploads/avatar.jpg',null,'{$_POST['status']}');";
    $connect=connect();
    $res=mysqli_query($connect,$sql);
    if ($res) {
        $response['code']=1;
        $response['msg']='操作成功';
        $sqlId="select id from users where email='{$_POST['email']}';";
        $id=query($sqlId);
        $response['id']=$id[0]['id'];
    }
}
header("content-type:application/json;charset=utf-8");
echo json_encode($response);
?>