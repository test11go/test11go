<?php 
// ヘッダー読み込み
require_once('/Applications/MAMP/htdocs/hal/header.php');

if(!$_GET['category']){
  header('Location:/hal/survey/index.php');
}

// テーブル読み込み 要編集　全て読み込む必要はない　＊の部分
// ターゲットの絞り込みも忘れずに

$statement = $db->prepare(
  'SELECT *  FROM survey
  JOIN category_survey ON survey.category_id = category_survey.id 
  JOIN users ON survey.user_id = users.id
  JOIN icon ON users.icon_id = icon.id
  WHERE survey.delete_flag = 0 AND category_survey.id=?;'
);
$statement->execute(array(
  $_GET['category']
));
$questionnaire = $statement->fetchall();

?>

<main>
<a href="create.php?category=<?php print $_GET['category'] ;?>">＋</a>
<h1>カテゴリー名</h1>

<!-- 以下スレッド一覧 -->
<?php foreach($questionnaire as $row){
  print("<span>". $row['icon'] . "</span>" );
  print("<a href=\"view.php?id={$row[0]}\">{$row['title']}</a><br>");
  print("<hr>");
}
?>
</main>
<?php require_once('/Applications/MAMP/htdocs/hal/footer.php'); ?>