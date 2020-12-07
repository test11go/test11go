<?php 
require_once('header.php');
$test[30] = "どうでしょうか";

if($_POST){
  print_r($_POST);
  print_r($test);
}

?>
<!-- ヘッダー読み込み -->
<main>
  <form action="" method="POST">
  <input type="hidden" name="text['test'][]" value="10">
  <input type="hidden" name="text['test'][]" value="20">
  <input type="hidden" name="text['test'][]" value="30">
  <input type="hidden" name="text['test'][]" value="40">
    <input type="submit" value="送信">
  </form>
</main>
<?php require_once('footer.php'); ?>