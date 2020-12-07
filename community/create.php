<?php
// ヘッダー読み込み
require_once('/Applications/MAMP/htdocs/hal/header.php');

// 不正リクエストの防止
if(!$_GET['category']){
  header('Location:/hal/community/index.php');
}

// タイプテーブルの読み込み
$types = $db->query('SELECT * FROM type');

if($_POST){
  $statement = $db->prepare('INSERT INTO thread SET title=?,body=?,category_id=?,user_id=?');
  $statement->execute(array(
    $_POST['title'],
    $_POST['body'],
    $_GET['category'],
    $_SESSION['id']
  ));
  header('Location:/hal/community/index.php');
}

?>

<main>
  <h1>Create</h1>
  <form action="" method="POST">
    <label for="title">タイトル：</label><br>
    <input type="text" id="title" name="title" placeholder="タイトルを入力">
    <br>
    <label for="body">本文：</label><br>
    <textarea id="body" name="body" placeholder="本文を入力"></textarea><br>
    <br> 
    <label for="target">全員に公開：</label><br>
    on<input type="radio" id="target" name="target" value="on">
    <input type="radio" id="target" name="target" value="off">off
    <br>
    <span>onの場合、↓をJSで表示:</span><br>
    <?php foreach($types as $type){
      print("<input type=\"checkbox\" id=\"{$type['id']}\" name=\"{$type['id']}\" value=\"{$type['id']}\" checked>\n
      <label for=\"{$type['id']}\">{$type['type']}</label>"); 
      }
    ?>
    <br><br>
    <input type="submit" value="投稿">

  </form>
</main>

<?php require_once('/Applications/MAMP/htdocs/hal/footer.php'); ?>