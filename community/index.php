<?php 
require_once('/Applications/MAMP/htdocs/hal/header.php');
// ログインしていなかった場合はログインを求める。
if(!$_SESSION['id']){
  header('Location:/hal/login/index.php');
  exit();
}
//　カテゴリーの読み込み
$categorys = $db->query('SELECT * FROM category');

?>
<!-- ヘッダー読み込み -->
<main>

  <?php foreach($categorys as $row){
    print("<a href='thread.php?category={$row['id']}'>{$row['category']}</a><br>");
  }
  ?>

</main>
<!-- フッター読み込み -->
<?php require_once('/Applications/MAMP/htdocs/hal/footer.php'); ?>