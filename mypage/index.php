<!-- ヘッダー読み込み -->
<?php 
require_once('/Applications/MAMP/htdocs/hal/header.php');

$statement = $db->prepare(
  'SELECT * FROM users
  JOIN result_type ON users.result_id = result_type.id 
  JOIN icon ON users.icon_id = icon.id
  JOIN gender ON users.gender_id = gender.id 
  JOIN type ON result_type.type_id = type.id
  WHERE users.id=?;'
);
$statement->execute(array(
  $_SESSION['id']
));
$profile = $statement->fetch();


?>

<main>
  <p>名前：<?php print($profile['name']."\n"); ?></p>
  <p>アイコン：<?php print($profile['gender']."\n"); ?></p>
  <p>アイコン：<?php print($profile['icon']."\n"); ?></p>
  <p>タイプ：<?php print($profile['type']."\n"); ?></p>
  <a href="preferences.php">編集</a>
</main>
<?php require_once('/Applications/MAMP/htdocs/hal/footer.php'); ?>