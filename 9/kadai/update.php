<?php

  include('config.php');


  //1. 接続します
    try{
        $pdo = new PDO('mysql:dbname='.DBNAME.'; host='.HOST , USER, PASS);
    }catch(PDOException $e){
        exit('データベース失敗'.$e->getMessage());
    }

  //2. DB文字コードを指定
  $stmt = $pdo->query('SET NAMES utf8');

  if(isset($_GET['id'])){
    $stmt=$pdo->prepare('SELECT * FROM '.TABLE.' WHERE id=:id');
    $stmt->bindValue(':id',$_GET['id']);
    
    $stats= $stmt->execute();

    if($stats==true){
      while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        $id=$row['id'];
        $name=$row['name'];
        $email=$row['email'];
        $age=$row['age'];
        $naiyou=$row['naiyou'];
      }
    }


    if(!isset($_POST["post_flg"])){
          //echo "パラメータが無いので登録処理は無し";
        }else{
          $name = $_POST["name"];
          $email = $_POST["email"];
          $age=$_POST["age"];
          $naiyou=$_POST["naiyou"];

          //３．データ登録SQL作成
          $stmt = $pdo->prepare("UPDATE ".TABLE." SET name=:name, email=:email, age=:age, naiyou=:naiyou WHERE id=:id");
          $stmt->bindValue(':name', $name);
          $stmt->bindValue(':email', $email);
          $stmt->bindValue(':age', $age);
          $stmt->bindValue(':naiyou', $naiyou);
          $stmt->bindValue(':id', $id);
          $status = $stmt->execute();
          if($status==false){
            echo "SQLエラー";
            exit;
          }else{
            echo "登録完了！";
          }
          header("Location: update.php?id=$id");
    }
   
  }


    
    
?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>POSTデータ登録</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>

<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
    <div class="navbar-header"><a class="navbar-brand" href="#">アンケート</a>
<a href="select.php">一覧画面へ</a>
    </div>
  </nav>
</header>
<!-- Head[End] -->
<!-- Main[Start] -->
<div class="jumbotron">
    <form method="post" action="update.php?id=<?=$id ?>" enctype="multipart/form-data" id="send_file">
       <div class="form-group">
       <label for="name">お名前</label>
        <input type="text" name="name" id="name" class="form-control" value="<?=$name ?>">
        </div>
         <div class="form-group">
         <label for="email">メール</label>
        <input type="text" name="email" id="email" class="form-control" value="<?=$email ?>">
        </div>
         <div class="form-group">
         <label for="age">年齢</label>
        <input type="text" name="age" id="age" class="form-control" value="<?=$age ?>">
        </div>
         <div class="form-group">
         <label for="naiyou">内容</label>
             <textarea type="text" name="naiyou" id="naiyou" class="form-control"><?=$naiyou ?></textarea>
        </div>
        <input type="hidden" name="post_flg" value="1">
        <input type="submit" value="送信" class="btn-primary" style="margin-left:15px">
    </form>
</div>

<!-- Main[End] -->


<!-- Javascript -->
<script src="js/jquery-2.1.3.min.js"></script>
</body>
</html>
