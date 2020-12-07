<?php 
// ヘッダー読み込み
require_once('/Applications/MAMP/htdocs/hal/header.php');

// 不正リクエスト対策
if(!$_GET['thread']){
  header('Location:/hal/community/index.php');
}

// コメント投稿
if($_POST['change']){
  $statement = $db->prepare('UPDATE thread SET body=?, update_date=?
  WHERE id=? AND user_id = ?');
  $statement->execute(array(
    $_POST['change'],
    date("Y-m-d H:i:s"),
    $_GET['thread'],
    $_SESSION['id']
  ));
  header("Location:./view.php?id={$_GET['thread']}" );
}


// コメントテーブル読み込み
$statement_thread = $db->prepare(
  'SELECT * FROM thread
   JOIN users ON thread.user_id = users.id 
   JOIN icon ON users.icon_id = icon.id 
   WHERE thread.id=? AND thread.user_id = ?'
);
$statement_thread->execute(array(
  $_GET['thread'],
  $_SESSION['id']
));
$thread = $statement_thread->fetch();

?>

<main>
<form action="" method="POST">
  <label for="change">本文：</label><br>
  <textarea id="change" name="change" placeholder="本文を入力"><?php print($thread['body']) ?></textarea><br>
  <input type="submit" value="編集">
</form>
</main>

<!-- 以下フッター -->
<?php require_once('/Applications/MAMP/htdocs/hal/footer.php'); ?>