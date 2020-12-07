<?php 
// もしcookieにテスト結果がなければリダイレクト
if(!$_COOKIE['type']){
  header('Location:/hal/personality-test/index.php');
  exit();
}

// ヘッダー読み込み
require_once('/Applications/MAMP/htdocs/hal/header.php');

// cookieの値を変数に格納
$type_date = $_COOKIE['type'];

// DBから性別とカテゴリーを読み込み
$gender = $db->query('SELECT * FROM gender');
$icon = $db->query('SELECT * FROM icon');

if(htmlspecialchars($_POST['signup'])){
  // エラーチェック関数の読み込み
  require_once('error_check.php');
  if(empty($error)){
    $statement = $db->prepare('INSERT INTO users SET name=?,gender_id=?,birth=?,mail=?,password=?,icon_id=?,result_id=?');
    $statement->execute(array(
      $_POST['user_name'],
      $_POST['gender'],
      $_POST['date'],
      $_POST['mail'],
      $_POST['password'],
      $_POST['icon'],
      $_COOKIE['type']['5'],
    ));
    $_POST = array();
    header('Location:/hal/signup/thx.php');
    exit();
  }
}

?>

<form action="" method="POST">
<!-- 名前：入力フォーム -->
  <label for="user_name">名前: </label><br>
  <input id="user_name" name="user_name" type="text" placeholder="ユーザー名を入力" value="<?php print(htmlspecialchars($_POST['user_name'],ENT_QUOTES)); ?>">
  <?php if($error['user_name']) print('<span style="color:red">※未入力です</span>'); ?><br>

<!-- アドレス：入力フォーム -->
  <label for="mail">アドレス: </label><br>
  <input id="mail" name="mail" type="mail" placeholder="メールアドレスを入力" value="<?php print(htmlspecialchars($_POST['mail'],ENT_QUOTES)); ?>">
  <?php if($error['mail']) print('<span style="color:red">※未入力です</span>'); ?><br>

<!-- 性別：入力フォーム -->
  <label for="gender">性別: </label><br>
  <select id="gender" name="gender">
    <option hidden value="">選択してください</option>
    <?php 
    foreach($gender as $row){
      print('<option value="'.$row['id'].'"');
      if(htmlspecialchars($_POST['gender'],ENT_QUOTES) == $row['id']){
        print " selected";
      }
      print(">".$row['gender']."</option>");
    }
    echo "\n";
    ?>
  </select>
  <?php if($error['gender']) print('<span style="color:red">※未入力です</span>'); ?><br>

<!-- 生年月日：入力フォーム -->
  <label for="date">生年月日: </label><br>
  <input id="date" name="date" type="date" value="<?php print(htmlspecialchars($_POST['date'],ENT_QUOTES)); ?>">
  <?php if($error['date']) print('<span style="color:red">※未入力です</span>'); ?><br>

<!-- アイコン：入力フォーム -->
  <label for="icon">アイコン: </label><br>
  <select id="icon" name="icon">
    <option hidden value="">選択してください</option>
    <?php 
      foreach($icon as $row){
        print('<option value="'.$row['id'].'"');
        if(htmlspecialchars($_POST['icon'],ENT_QUOTES) == $row['id']){
          print " selected";
        }
        print(">".$row['icon']."</option>");
      }
      echo "\n";
      ?>
  </select>
  <?php if($error['icon']) print('<span style="color:red">※未入力です</span>'); ?><br>

  <!-- パスワード：入力フォーム -->
  <label for="password">パスワード: </label><br>
  <input id="password" name="password" type="password" placeholder="パスワードを入力" value="<?php print(htmlspecialchars($_POST['password'],ENT_QUOTES)); ?>">
  <?php if($error['password']) print('<span style="color:red">※未入力です</span>'); ?><br>
  <br>

  <!-- サブミット -->
  <input type="submit" name="signup" value="登録">  
</form>


<?php require_once('/Applications/MAMP/htdocs/hal/footer.php'); ?>