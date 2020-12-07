<?php 
// もしログインしていなかったらリダイレクト

// ヘッダー読み込み
require_once('/Applications/MAMP/htdocs/hal/header.php');


// DBから性別とアイコンを読み込み、メンバー情報も
$gender = $db->query('SELECT * FROM gender');
$icon = $db->query('SELECT * FROM icon');
$statement = $db->prepare('SELECT * FROM users WHERE id=?');
$statement->execute(array(
  $_SESSION['id']
));
$user = $statement->fetch();


if(htmlspecialchars($_POST['signup'])){
  // パスワードチェックも
  $login = $db->prepare('SELECT * FROM users WHERE mail=? AND password=?');
    $login->execute(array(
      $user['mail'],
      $_POST['password'],
    ));
    $succes = $login->fetch();

    if($succes){
    // エラーチェック関数の読み込み
    require_once('error_check.php');  

    // エラーがなければ
    if(empty($error)){
      $statement = $db->prepare('UPDATE users 
      SET name=?,mail=?,gender_id=?,birth=?,icon_id=?,password=? WHERE id = ?');
      $statement->execute(array(
        $_POST['user_name'],
        $_POST['mail'],
        $_POST['gender'],
        $_POST['date'],        
        $_POST['icon'],
        $_POST['new_password'], 
        $user['id']
      ));
      header('Location:/hal/mypage/index.php');
      exit();
    } 
  }else{
    $error['log'] = 'false';
  }
}
?>

<form action="" method="POST">
  <!-- ログイン失敗メッセージ -->
  <?php if($error['log']) print('<br><span style="color:red">※ログインに失敗しました。</span><br>'); ?>
<!-- 名前：入力フォーム -->
  <label for="user_name">名前: </label><br>
  <input id="user_name" name="user_name" type="text" placeholder="ユーザー名を入力" value="<?php print($user['name']); ?>">
  <?php if($error['user_name']) print('<span style="color:red">※未入力です</span>'); ?><br>

<!-- アドレス：入力フォーム -->
  <label for="mail">アドレス: </label><br>
  <input id="mail" name="mail" type="mail" placeholder="メールアドレスを入力" value="<?php print($user['mail']); ?>">
  <?php if($error['mail']) print('<span style="color:red">※未入力です</span>'); ?><br>

<!-- 性別：入力フォーム -->
  <label for="gender">性別: </label><br>
  <select id="gender" name="gender">
    <option hidden value="">選択してください</option>
    <?php 
    foreach($gender as $row){
      print('<option value="'.$row['id'].'"');
      if($user['gender_id'] == $row['id']){
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
  <input id="date" name="date" type="date" value="<?php print($user['birth']); ?>">
  <?php if($error['date']) print('<span style="color:red">※未入力です</span>'); ?><br>

<!-- アイコン：入力フォーム -->
  <label for="icon">アイコン: </label><br>
  <select id="icon" name="icon">
    <option hidden value="">選択してください</option>
    <?php 
      foreach($icon as $row){
        print('<option value="'.$row['id'].'"');
        if($user['icon_id'] == $row['id']){
          print " selected";
        }
        print(">".$row['icon']."</option>");
      }
      echo "\n";
      ?>
  </select>
  <?php if($error['icon']) print('<span style="color:red">※未入力です</span>'); ?><br>

  <!-- パスワード：入力フォーム -->
  <label for="new_password">新しいパスワード: </label><br>
  <input id="new_password" name="new_password" type="password" placeholder="パスワードを入力">
  <br>

  <!-- パスワード：入力フォーム -->
  <label for="password">古いパスワード: </label><br>
  <input id="password" name="password" type="password" placeholder="パスワードを入力">
  <?php if($error['password']) print('<span style="color:red">※未入力です</span>'); ?><br>
  <br>

  <!-- サブミット -->
  <input type="submit" name="signup" value="登録">  
</form>


<?php require_once('/Applications/MAMP/htdocs/hal/footer.php'); ?>