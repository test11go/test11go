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
  try {  
    
    // トランザクション処理の開始
    $db->beginTransaction();
    
    // アンケート本体の書き込み
    $statement_survey = $db->prepare('INSERT INTO survey SET title=?,body=?,category_id=?,user_id=?');
    $statement_survey->execute(array(
      $_POST['title'],
      $_POST['body'],
      $_GET['category'],
      $_SESSION['id']
    ));
    // surveyのIDを記録
    $survey_id = $db->lastInsertId();

    // クエスチョン＿テキストテーブルへの書き込み
    if($_POST['question_text']){
      $statement_question_text = $db->prepare('INSERT INTO question SET survey_id=?,body=?,required=?,method=?');
      $statement_question_text->execute(array(
        $survey_id,
        $_POST['question_text'][0],
        intval($_POST['quesiton_required'][0]),
        0,
      ));
    }

    // クエスチョン＿チョイステーブルへの書き込み
    if($_POST['question_choice']){
      $statement_question_choice = $db->prepare('INSERT INTO question SET survey_id=?,body=?,required=?,method=?');
      $statement_question_choice->execute(array(
        $survey_id,
        $_POST['question_choice'][0],
        1,
        1
      ));
      // question_idの記録
      $question_id = $db->lastInsertId();

      // チョイステーブルへの書き込み
      foreach($_POST['choice'] as $key=>$row){
        $statement_choice = $db->prepare('INSERT INTO choices SET question_id=?,item=?,index_number=?');
        $statement_choice->execute(array(
          $question_id,
          $row,
          $key+1
        ));
      }
    }



    // トランザクション処理の決定
    $db->commit();
    header('Location:/hal/survey/index.php');    
  } catch (Exception $e) {
    $db->rollBack();
    echo "失敗しました。" . $e->getMessage();
  }


}

?>

<main>
  <h1>Create</h1>
  <form action="" method="POST">
    <label for="title">タイトル：</label><br>
    <input type="text" id="title" name="title" placeholder="タイトルを入力">
    <br>
    <label for="body">説明文：</label><br>
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
    <br>
    <hr>
    <br>
    <label for="question_text">質問：自由記述式</label><br>
    <input type="text" id="question_text" name="question_text[]" placeholder="質問文を入力。"><br>
    <label for="question_required">必須項目：</label><br>
    on<input type="radio" id="question_required" name="question_required[]" value="1">
    <input type="radio" id="question_required" name="question_required[]" value="0">off<br>
    <label for="question_choice">質問：選択式</label><br>
    <input type="text" id="question_choice" name="question_choice[]" placeholder="質問文を入力。"><br>
    <label for="choice1">選択項目：</label>
    <input type="text" id="choice1" name="choice[]" placeholder="選択項目"><br>
    <label for="choice2">選択項目：</label>
    <input type="text" id="choice2" name="choice[]" placeholder="選択項目"><br>
    <label for="choice3">選択項目：</label>
    <input type="text" id="choice3" name="choice[]" placeholder="選択項目">
    <br>
    <br><br>
    <input type="submit" value="投稿">

  </form>
</main>

<?php require_once('/Applications/MAMP/htdocs/hal/footer.php'); ?>