<?php

print "クエスチョン";
// スレッドの読み込み
$statement_survey = $db->prepare(
  'SELECT * ,survey.user_id AS survey_user_id,survey.post_date AS survey_post_date,survey.ps_date AS survey_ps_date,COUNT(answer.id) AS answer_count FROM survey
  JOIN users ON survey.user_id = users.id 
  JOIN icon ON users.icon_id = icon.id 
  LEFT JOIN answer ON survey.id = answer.survey_id
  WHERE  survey.delete_flag = 0 AND survey.id=?'
);
$statement_survey->execute(array(
  $_GET['id']
));
$survey = $statement_survey->fetch();

// 誤ったID番号は飛ばす
if(!$survey[0])
{
  header('Location:/hal/survey/index.php');
}

// questionテーブル読み込み
$statement_question = $db->prepare(
  'SELECT question.id,question.*,choices.question_id,choices.item,choices.index_number FROM question
  LEFT JOIN choices ON question.id = choices.question_id
  WHERE question.survey_id = ?
  ORDER BY question.id,choices.index_number ASC'
);

$statement_question->execute(array(
  $_GET['id']
));
$questions = $statement_question->fetchall(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
// ターゲットの絞り込みも。

?>

<main>


<h1><?php print($survey['title']); ?></h1>
投稿日時：<?php print($survey['survey_post_date']); ?><br>
追記日時：<?php print($survey['survey_ps_date']); ?><br>
本文：<?php print($survey['body']); ?><br>
投稿者：<?php print($survey['name']); ?><br>
アイコン：<?php print($survey['icon']); ?><br>
回答数：<?php print($survey['answer_count']); ?><br>
<?php 
if($_SESSION['id'] === $survey['survey_user_id']){
  print("<a href=\"./preferences.php?survey={$survey[0]}\">編集</a>");
  // getでいいかも。
  print("
    <form action='' method='POST'>
      <input type='hidden' name='delete_survey' value='{$survey[0]}'>
      <input type='submit' value='削除'>
    </form>");
}
?>
<hr>

<!-- 質問の表示 -->
<form action="" method="POST">
  <?php foreach($questions as $index=>$question){
    print("<p>{$question[0]['body']}</p>");
    print("<p>必須＝{$question[0]['required']}</p>");
    
    if($question[0]['method'] == 0){
      print("<input type='text' name='answer_text[{$question[0]['id']}]' placeholder='テキスト入力'><br>");
    }elseif($question[0]['method'] == 1){
      foreach($question as $key=>$row){
        print("<label for='q{$row['question_id']}_{$key}'>{$row['item']}</label><input type='radio' id='q{$row['question_id']}_{$key}' name='answer_choice[{$question[0]['id']}]' value='{$row['index_number']}'><br>");
      }
    }
    print("<hr>");
    }
  ?>
  <input type="submit" value="回答">
</form>
</main>
