<?php
include('config.php');

//セッション開始
session_start();
//エラーメッセージの格納
$errorMsg='';

//ログインボタンが押された時の処理
if(isset($_POST['post_flg'])){
    //ユーザーIDをチェックする
    if(empty($_POST['userId'])){
        $errorMsg="IDが未入力です";
    }else if(empty($_POST['password'])){
        $errorMsg="パスワードが未入力です";
    }
    
    //ユーザーIDとパスワードが入力されていたら認証を開始する
    if(!empty($_POST['userId']) && !empty($_POST['password'])){
        try{
            $pdo = new PDO('mysql:dbname='.DBNAME.'; host='.HOST, USER, PASS);
        }catch(PDOException $e){
            exit('データベース失敗'.$e->getMessage());
        }
        //DB文字コードを指定
        $stmt = $pdo->query('SET NAMES utf8');
        $stmt = $pdo->prepare('SELECT * FROM user WHERE userId=:userId');
        $stmt->bindValue(':userId',$_POST['userId']);
        $status=$stmt->execute();
        
        if($status==true){
            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                $userPassword=$row['password'];
            }
            
            if(isset($userPassword)){
                if($userPassword==$_POST['password']){
                    session_regenerate_id(true);
                    $_SESSION['USERID']=$_POST['userId'];
                    header('Location:index.php');
                    exit;
                }else{
                    $errorMsg='ユーザーIDもしくはパスワードに誤りがあります';
                }
            }else{
                $errorMsg='ユーザーIDもしくはパスワードに誤りがあります';
            }
        }
    }else{
        //未入力なら何もしない
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
    <link href="css/non-responsive.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <div>
                    <img src="img/photoruction_logo.jpg" class="img-responsive">
                </div>
                <div style="text-align:center; margin-top:20px;">
                <p><?=$errorMsg ?></p>
                </div>
                <form method="post" action="login.php">
                    <label for="userId">ID</label>
                    <input type="text" class="form-control" name="userId" id="userId">
                    <label for="password">パスワード</label>
                    <input type="password" class="form-control" name="password" id="password">
                    <input type="hidden" name="post_flg" value="1">
                    <div style="text-align:center; margin-top:20px;">
                        <button class="btn btn-primary" id="loginBtn">ログイン</button>
                        <p><a href="create_acount.php">新規登録</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>