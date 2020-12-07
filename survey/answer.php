<?php

print "あなたは回答済みです。";

$survey_id = $_GET['id'];

// スレッドの読み込み
$statement_survey = $db->prepare(
  'SELECT * ,survey.user_id AS survey_user_id,survey.post_date AS survey_post_date,survey.ps_date AS survey_ps_date,COUNT(answer.id) AS answer_count FROM survey
  JOIN users ON survey.user_id = users.id 
  JOIN icon ON users.icon_id = icon.id 
  LEFT JOIN answer ON survey.id = answer.survey_id
  WHERE  survey.delete_flag = 0 AND survey.id=?'
);
$statement_survey->execute(array(
  $survey_id
));
$survey = $statement_survey->fetch();

// 誤ったID番号は飛ばす
if(!$survey[0])
{
  header('Location:/hal/survey/index.php');
}

// answerテーブルの読み込み
$statement_answer = $db->prepare(
  'SELECT answer.*,users.name,icon.icon,type.type FROM answer
  JOIN users ON answer.user_id = users.id
  JOIN icon ON users.icon_id = icon.id
  JOIN result_type ON users.result_id = result_type.id
  JOIN type ON result_type.type_id = type.id
  WHERE survey_id = ?'
);
$statement_answer->execute(array(
  $survey_id
));
$answer_list = $statement_answer->fetchall();
if(!$_GET['page']){
  $page_number = 0; 
  $answer = $answer_list[$page_number];
}else{
  $page_number = intval($_GET['page']);
  $answer = $answer_list[$page_number];
}

// questionテーブル読み込み　最適化要
$statement_question = $db->prepare(
  'SELECT question.id,question.body AS question,question.required,question.method,
  answer_text.body AS answer_text,
  choices.item AS answer_item
  FROM question
    LEFT JOIN answer_text ON question.id = answer_text.question_id
    LEFT JOIN answer_choice ON question.id = answer_choice.question_id
    LEFT JOIN choices ON  choices.question_id = question.id  AND choices.index_number = answer_choice.choices_id
    JOIN answer ON answer.id = answer_choice.answer_id OR answer.id = answer_text.answer_id
    
    WHERE question.survey_id = ? AND answer.id = ?
    ORDER BY question.id ASC'
);

$statement_question->execute(array(
  $survey_id,
  $answer['id']

));
$questions = $statement_question->fetchall();
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
<!-- ユーザー情報 -->
<p>回答者： <?php print($answer['name']); ?><p>
<p>アイコン： <?php print($answer['icon']); ?><p>
<p>タイプ： <?php print($answer['type']); ?><p>
<a href="<?php print("?id=".$_GET['id']."&page=". ($page_number - 1))?>">前の回答</a>
<a href="<?php print("?id=".$_GET['id']."&page=". ($page_number + 1))?>">次の回答</a>

<hr>
<!-- 質問の表示 -->
  <?php foreach($questions as $question){
    print("<p>質問：{$question['question']}</p>");
    // print("<p>必須＝{$question[0]['required']}</p>");
    if($question['method'] == 0){
      print($question['answer_text']."<br>");
    }elseif($question['method'] == 1){
      print($question['answer_item']."<br>");
    }
    print("<hr>");
    }
  ?>
</main>
