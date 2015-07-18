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
        $news_title=$row['news_title'];
        $news_detail=$row['news_detail'];
        $view_flg=$row['view_flg'];
      }
    }


    if(!isset($_POST["post_flg"])){
          //echo "パラメータが無いので登録処理は無し";
        }else{
       
          $news_title=$_POST['news_title'];
          $news_detail=$_POST['news_detail'];
          $view_flg=$_POST['view_flg'];

          //３．データ登録SQL作成
          $stmt = $pdo->prepare("UPDATE ".TABLE." SET news_title=:news_title, news_detail=:news_detail, view_flg=:view_flg WHERE id=:id");
          $stmt->bindValue(':news_title', $news_title);
          $stmt->bindValue(':news_detail', $news_detail);
          $stmt->bindValue(':view_flg', $view_flg);
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
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>

<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
    <div class="navbar-header"><a class="navbar-brand" href="#">ニュース編集画面</a>
<a href="select.php">一覧画面へ</a>
    </div>
  </nav>
</header>
<!-- Head[End] -->
<!-- Main[Start] -->
<div class="jumbotron">
   <form method="post" action="update.php?id=<?=$id ?>" enctype="multipart/form-data" id="send_file">
       <div class="form-group">
       <label for="news_title">タイトル</label>
        <input type="text" name="news_title" id="news_title" class="form-control" value="<?=$news_title ?>">
        </div>
         <div class="form-group">
         <label for="news_detail">内容</label>
         <textarea name="news_detail" id="news_detail" class="form-control"><?=$news_detail ?></textarea>
        </div>
         <div class="form-group">
            <div class="radio-inline"><label><input type="radio" name="view_flg" value="1" <?php
        if($view_flg==1){
            echo "checked";
    }?>>公開</label></div>
            <div class="radio-inline"><label><input type="radio" name="view_flg" value="0" <?php
        if($view_flg==0){
            echo "checked";
    }?>>非公開</label></div>
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
