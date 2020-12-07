<?php
$error = array();
// 未入力エラー
 if($_POST['user_name'] == ""){ 
   $error['user_name'] = 'blank';
}
if($_POST['mail'] == ""){ 
  $error['mail'] = 'blank';
}
if($_POST['gender'] == ""){ 
  $error['gender'] = 'blank';
}
if($_POST['date'] == ""){ 
  $error['date'] = 'blank';
}
if($_POST['icon'] == ""){ 
  $error['icon'] = 'blank';
}
if($_POST['password'] == ""){ 
  $error['password'] = 'blank';
}


?>