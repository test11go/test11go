<?php 
// ヘッダー読み込み
require_once('/Applications/MAMP/htdocs/hal/header.php');

// 不正リクエスト対策
if(!$_GET['id']){
  header('Location:/hal/survey/index.php');
}

// スレッド削除
// if($_POST['delete_thread']){
//   $statement_delete_thread = $db->prepare('UPDATE thread SET delete_flag =1 WHERE id=? AND user_id=?');
//   $statement_delete_thread->execute(array(
//     $_POST['delete_thread'],
//     $_SESSION['id']
//   ));
//   header('Location:/hal/community/index.php');
// }

// １．postがあった場合！！回答の保存
if($_POST['answer_text'] || $_POST['answer_choices']){
  print($_POST['id'][0]);
  try {  
    // トランザクション処理の開始
    $db->beginTransaction();
    
    // 回答本体の書き込み
    $statement_answer = $db->prepare('INSERT INTO answer SET survey_id=?,user_id=?');
    $statement_answer->execute(array(
      $_GET['id'],
      $_SESSION['id']
    ));
    // アンサーIDを記録
    $answer_id = $db->lastInsertId();

    // アンサー＿テキストテーブルへの書き込み
    foreach($_POST['answer_text'] as $key=>$row){
      $statement_answer_text = $db->prepare('INSERT INTO answer_text SET answer_id=?,question_id=?,body=?');
      $statement_answer_text->execute(array(
        $answer_id,
        intval($key),
        $row
      ));
    }
    // アンサー＿チョイステーブルへの書き込み
    foreach($_POST['answer_choice'] as $key=>$row){
      $statement_answer_choice = $db->prepare('INSERT INTO answer_choice SET answer_id=?,question_id=?,choices_id=?');
      $statement_answer_choice->execute(array(
        $answer_id,
        intval($key),
        intval($row)
      ));
    }
    // トランザクション処理の決定
    $db->commit();
    header('Location:/hal/survey/index.php');    
  } catch (Exception $e) {
    $db->rollBack();
    echo "失敗しました。" . $e->getMessage();
  }
}

// ２．回答済みか判定
$statement_answer = $db->prepare('SELECT user_id FROM answer WHERE survey_id=? AND user_id=?');
$statement_answer->execute(array(
  $_GET['id'],
  $_SESSION['id']
));
if($statement_answer->fetch()){
  // あった場合の表示
  require_once('/Applications/MAMP/htdocs/hal/survey/answer.php');
}else{
  // なかった場合の表示
  require_once('/Applications/MAMP/htdocs/hal/survey/question.php');
}

// フッター
require_once('/Applications/MAMP/htdocs/hal/footer.php');
?>