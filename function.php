<?php 
function checkLogin(){
  session_start();
  if (!$_SESSION['isLogin']||$_SESSION['isLogin']!==1) {
    header("location:login.php");
  }
}

function connect(){
$connect=mysqli_connect(DB_HOST,DB_USER,DB_PWD,DB_NAME);
if ($connect) {
return $connect;
}else{
echo '数据库连接失败';
return;
}
}
function query($sql){
$result=mysqli_query(connect(),$sql);
return fetch($result);
mysqli_close(connect());
}
function fetch($result){
    $postArr=[];
while ($row=mysqli_fetch_assoc($result)) {
  $postArr[]=$row;
}
return $postArr;
}
function addData($connect,$table,$arr){
  $keys=array_keys($arr);
    $values=array_values($arr);
    $sql="insert into $table (".implode(",",$keys).") values('".implode("','",$values)."');";
    $addResult=mysqli_query($connect,$sql);
    return $addResult;
}
?>