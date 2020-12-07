<?php
  // ヘッダー読み込み、セッションスタート
require_once('/Applications/MAMP/htdocs/hal/header.php');
  
if(htmlspecialchars($_POST['login'])){
  require_once('error_check.php');
  if(empty($error)){
    $login = $db->prepare('SELECT * FROM users WHERE mail=? AND password=?');
    $login->execute(array(
      $_POST['mail'],
      $_POST['password'],
    ));

    $user = $login->fetch();
    if($user){
      $_SESSION['name'] = $user['name'];
      $_SESSION['id'] = $user['id'];
      header('Location:/hal/index.php');
    }else{
      $error['log'] = 'false';
    }
  }
}
?>

<form action="" method="POST">

<?php if($error['log']) print('<br><span style="color:red">※ログインに失敗しました。</span><br>'); ?>
<!-- アドレス：入力フォーム -->
  <label for="mail">アドレス: </label><br>
  <input id="mail" name="mail" type="mail" placeholder="メールアドレスを入力">
  <?php if($error['mail']) print('<span style="color:red">※未入力です</span>'); ?><br>

  <!-- パスワード：入力フォーム -->
  <label for="password">パスワード: </label><br>
  <input id="password" name="password" type="password" placeholder="パスワードを入力">
  <?php if($error['password']) print('<span style="color:red">※未入力です</span>'); ?><br>
  <br>

  <!-- サブミット -->
  <input type="submit" name="login" value="ログイン">  
</form>


<?php require_once('/Applications/MAMP/htdocs/hal/footer.php'); ?>