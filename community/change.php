<?php 
// ヘッダー読み込み
require_once('/Applications/MAMP/htdocs/hal/header.php');

// 不正リクエスト対策
if(!$_GET['comment']){
  header('Location:/hal/community/index.php');
}

// コメント投稿
if($_POST['change']){
  $statement = $db->prepare('UPDATE comment SET body=?, update_date=?
  WHERE id=? AND user_id = ?');
  $statement->execute(array(
    $_POST['change'],
    date("Y-m-d H:i:s"),
    $_GET['comment'],
    $_SESSION['id']
  ));
  header("Location:./view.php?id={$_POST['thread']}" );
}


// コメントテーブル読み込み
$statement_comment = $db->prepare(
  'SELECT * FROM comment
   JOIN users ON comment.user_id = users.id 
   JOIN icon ON users.icon_id = icon.id 
   WHERE comment.id=? AND comment.user_id = ?'
);
$statement_comment->execute(array(
  $_GET['comment'],
  $_SESSION['id']
));
$comment = $statement_comment->fetch();


?>

<main>
<form action="" method="POST">
  <label for="change">コメント：</label><br>
  <textarea id="change" name="change" placeholder="本文を入力"><?php print($comment['body']) ?></textarea><br>
  <input type="hidden" name="thread" value="<?php print($comment['thread_id']) ?>">
  <input type="submit" value="編集">
</form>
</main>

<!-- 以下フッター -->
<?php require_once('/Applications/MAMP/htdocs/hal/footer.php'); ?>