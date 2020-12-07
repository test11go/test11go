<?php
$error = array();
// 未入力エラー
 
if($_POST['mail'] == ""){ 
  $error['mail'] = 'blank';
}
if($_POST['password'] == ""){ 
  $error['password'] = 'blank';
}


?>