<?php 
// ヘッダー読み込み
require_once('/Applications/MAMP/htdocs/hal/header.php');

// 不正リクエスト対策
if(!$_GET['id']){
  header('Location:/hal/community/index.php');
}

// コメント削除
if($_POST['delete_comment']){
  $statement_delete_comment = $db->prepare('UPDATE comment SET delete_flag =1 WHERE id=? AND user_id=?');
  $statement_delete_comment->execute(array(
    $_POST['delete_comment'],
    $_SESSION['id']
  ));
  header("Location: " . $_SERVER['PHP_SELF']."?id=".$_GET['id'] );

}

// スレッド削除
if($_POST['delete_thread']){
  $statement_delete_thread = $db->prepare('UPDATE thread SET delete_flag =1 WHERE id=? AND user_id=?');
  $statement_delete_thread->execute(array(
    $_POST['delete_thread'],
    $_SESSION['id']
  ));
  header('Location:/hal/community/index.php');
}

// コメント投稿
if($_POST['comment']){
  $statement_post = $db->prepare('INSERT INTO comment SET thread_id=?,body=?, user_id=?');
  $statement_post->execute(array(
    $_GET['id'],
    $_POST['comment'],
    $_SESSION['id']
  ));
  header("Location: " . $_SERVER['PHP_SELF']."?id=".$_GET['id'] );
}


// スレッドテーブル読み込み
$statement_thread = $db->prepare(
  'SELECT * ,thread.user_id AS thread_user_id,thread.post_date AS thread_post_date,thread.update_date AS thread_update_date,thread.body AS thread_body , COUNT(comment.id) AS comment_count  FROM thread
  JOIN users ON thread.user_id = users.id 
  JOIN icon ON users.icon_id = icon.id 
  LEFT JOIN comment ON thread.id = comment.thread_id
  WHERE  thread.delete_flag = 0 AND thread.id=?'
);
$statement_thread->execute(array(
  $_GET['id']
));
$thread = $statement_thread->fetch();

// 誤ったID番号は飛ばす
if(!$thread[0])
{
  header('Location:/hal/community/index.php');
}

// コメントテーブル読み込み
$statement_comment = $db->prepare(
  'SELECT *,comment.user_id AS comment_user_id ,comment.body AS comment_body, COUNT(subcomment.id) AS comment_count  FROM comment
  JOIN users ON comment.user_id = users.id 
  JOIN icon ON users.icon_id = icon.id
  LEFT JOIN subcomment ON comment.id = subcomment.comment_id
  WHERE comment.delete_flag = 0 AND comment.thread_id = ?
  GROUP BY comment.id'
);

$statement_comment->execute(array(
  $_GET['id']
));
$comments = $statement_comment->fetchall();

// ターゲットの絞り込みも。

?>

<main>


<h1><?php print($thread['title']); ?></h1>
投稿日時：<?php print($thread['thread_post_date']); ?><br>
編集日時：<?php print($thread['thread_update_date']); ?><br>
本文：<?php print($thread['thread_body']); ?><br>
投稿者：<?php print($thread['name']); ?><br>
アイコン：<?php print($thread['icon']); ?><br>
コメント数：<?php print($thread['comment_count']); ?><br>
<?php 
if($_SESSION['id'] === $thread['thread_user_id']){
  print("<a href=\"./preferences.php?thread={$thread[0]}\">編集</a>");
  print("
    <form action='' method='POST'>
      <input type='hidden' name='delete_thread' value='{$thread[0]}'>
      <input type='submit' value='削除'>
    </form>");
}
?>
<hr>

<form action="" method="POST">
  <label for="comment">コメント：</label><br>
  <textarea id="comment" name="comment" placeholder="本文を入力"></textarea><br>

  <input type="submit" value="コメントを送信">
</form>
<hr>
<!-- 以下コメント一覧 -->
<?php foreach($comments as $row){
  print("<span>". $row['icon'] . "</span>" );
  print(" <span>". $row['name'] . "</span>" );
  print("<p>{$row['comment_body']}</p>");
  print("<a href=\"./comment.php?comment={$row[0]}\">返信</a>");
  print("[{$row['comment_count']}]");
  if($_SESSION['id'] === $row['comment_user_id']){
    print("<a href=\"./change.php?comment={$row[0]}\">編集</a>");
    print("
    <form action='' method='POST'>
      <input type='hidden' name='delete_comment' value='{$row[0]}'>
      <input type='submit' value='削除'>
    </form>");
  }
  print("<hr>");
}
?>
</main>

<!-- 以下フッター -->
<?php require_once('/Applications/MAMP/htdocs/hal/footer.php'); ?>