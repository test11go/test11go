<?php 
require_once('/Applications/MAMP/htdocs/hal/header.php');
// ログインしていなかった場合はログインを求める。
if(!$_SESSION['id']){
  header('Location:/hal/login/index.php');
  exit();
}

$categorys = $db->query('SELECT * FROM category_survey');

?>
<!-- ヘッダー読み込み -->
<main>
  
  <?php foreach($categorys as $row){
    print("<a href='questionnaire.php?category={$row['id']}'>{$row['category']}</a>");
    print "<br>";
  }
  ?>

</main>
<!-- フッター読み込み -->
<?php require_once('/Applications/MAMP/htdocs/hal/footer.php'); ?>