<?php
// ヘッダー読み込み、セッションスタート
// require_once('/Applications/MAMP/htdocs/hal/header.php');
session_start();
// セッションを削除する。
$_SESSION = array();
// クライアント側のセッションクッキーの削除必要
header('Location:/hal/index.php');
?>

