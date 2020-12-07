<?php 
// setcookie(名前,値, 有効期限)
setcookie ("type[1]", "60",0,"/");
setcookie ("type[2]", "50",0,"/");
setcookie ("type[3]", "40",0,"/");
setcookie ("type[4]", "10",0,"/");
setcookie ("type[5]", "1",0,"/");
header('Location:type1.php');
exit();
