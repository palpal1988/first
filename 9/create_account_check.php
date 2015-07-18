<?php
    
    include('config.php');

    if(!isset($_POST['post_flg'])){
        //処理しない
    }else{
        
        $name=$_POST['name'];
        $email=$_POST['email'];
        $userId=$_POST['userId'];
        $password=$_POST['password'];
        
        try{
            $pdo = new PDO('mysql:dbname='.DBNAME.'; host='.HOST, USER, PASS);
        }catch(PDOException $e){
            exit('データベース失敗'.$e->getMessage());
        }
        //DB文字コードを指定
        $stmt = $pdo->query('SET NAMES utf8');
        //ユーザー情報を登録
        $stmt = $pdo->prepare('INSERT INTO user(id, name, email, userId, password) VALUES(NULL, :name, :email, :userId, :password)');
        $stmt->bindValue(':name',$name);
        $stmt->bindValue(':email',$email);
        $stmt->bindValue(':userId',$userId);
        $stmt->bindValue(':password',$password);
        $status=$stmt->execute();
        
        if($status==true){
            $view="登録しました。";
        }else{
            $view="登録失敗しました。お問い合わせください";
        }
    }

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
      <?=$view ?><br>
      <a href="login.php">戻る</a>
    </div>
    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>