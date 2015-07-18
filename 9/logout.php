<?php

session_start();

if(isset($_SESSION['USERID'])){
    $errorMsg="ログアウトしました";
}else{
    $errorMsg="セッションがタイムアウトしました";
}

$_SESSION=array();

session_destroy();


?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/create_account.css">
</head>

<body>
    <div class="container">
     
     <p><?=$errorMsg ?></p>
     <p><a href="login.php">ログイン画面に戻る</a></p>
      
    </div>
    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>