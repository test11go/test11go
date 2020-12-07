<?php
// DB接続
require_once('/Applications/MAMP/htdocs/hal/dbconnect.php');
// セッションスタート
session_start(); 
$statement = $db->prepare('SELECT * FROM users WHERE users.id=?');
$statement->execute(array(
  $_SESSION['id']
));
$user_name = $statement->fetch();


?>
<!-- HTML部分 -->
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>〜</title>
  <link rel="stylesheet" href="/hal/assets/css/style.css" />
  </head>
  <body>
    <header class="container">
        <div class="header-left"><a href="/hal/index.php">みんなの気質学</a></div>
        <nav>
          <a href="/hal/personality-test/index.php">テスト</a>
          <a href="/hal/signup/index.php">サインアップ</a>
          <a href="/hal/survey/index.php">アンケート</a>
          <a href="/hal/community/index.php">掲示板</a>
        </nav>
        <div class="header-right">
          <?php 
            if($user_name['name']){
              print('<a href="/hal/mypage/index.php">'.$user_name['name'].'</a>');
              print('<a href="./logout.php">ログアウト</a>');
            }else{
              print('<a href="./login/index.php">ログイン</a>');
            }?>
        </div>
    </header>
