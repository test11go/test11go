<?php 
// ヘッダー読み込み
require_once('/Applications/MAMP/htdocs/hal/header.php');

// 不正リクエスト対策
if(!$_GET['comment']){
  header('Location:/hal/community/index.php');
}

// コメント削除
if($_POST['delete_id']){
  $statement = $db->prepare('DELETE FROM subcomment WHERE id=? AND user_id=?');
  $statement->execute(array(
    $_POST['delete_id'],
    $_SESSION['id']
  ));
  header("Location: " . $_SERVER['PHP_SELF']."?comment=".$_GET['comment'] );
}


// コメント投稿
if($_POST['subcomment']){
  $statement = $db->prepare('INSERT INTO subcomment SET comment_id=?,body=?, user_id=?');
  $statement->execute(array(
    $_GET['comment'],
    $_POST['subcomment'],
    $_SESSION['id']
  ));
  header("Location: " . $_SERVER['PHP_SELF']."?comment=".$_GET['comment'] );
}


// サブコメントテーブル読み込み
$statement_subcomment = $db->prepare(
  'SELECT * FROM subcomment
   JOIN users ON subcomment.user_id = users.id 
   JOIN icon ON users.icon_id = icon.id 
   WHERE subcomment.comment_id=?'
);
$statement_subcomment->execute(array(
  $_GET['comment']
));
$subcomments = $statement_subcomment->fetchall();

// ターゲットの絞り込みも。



?>

<main>
<form action="" method="POST">
  <label for="subcomment">コメント：</label><br>
  <textarea id="subcomment" name="subcomment" placeholder="本文を入力"></textarea><br>

  <input type="submit" value="コメントを送信">
</form>
<hr>
<!-- 以下コメント一覧 -->
<?php foreach($subcomments as $row){
  print("<span>". $row['icon'] . "</span>" );
  print(" <span>". $row['name'] . "</span>" );
  print("<p>{$row['body']}</p><br>");
  if($_SESSION['id'] === $row['user_id']){
    print("
    <form action='' method='POST'>
      <input type='hidden' name='delete_id' value='{$row[0]}'>
      <input type='submit' value='削除'>
    </form>");
  }
  print("<hr>");
}
?>
</main>

<!-- 以下フッター -->
<?php require_once('/Applications/MAMP/htdocs/hal/footer.php'); ?>