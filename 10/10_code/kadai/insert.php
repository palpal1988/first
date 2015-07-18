<?php

include('config.php');

if(!isset($_POST["post_flg"])){
  //echo "パラメータが無いので登録処理は無し";
}else{
  $news_title = $_POST["news_title"];
  $news_detail = $_POST["news_detail"];
  $view_flg=$_POST["view_flg"];

  //1. 接続します
    try{
        $pdo = new PDO('mysql:dbname='.DBNAME.'; host='.HOST , USER, PASS);
    }catch(PDOException $e){
        exit('データベース失敗'.$e->getMessage());
    }

  //2. DB文字コードを指定
  $stmt = $pdo->query('SET NAMES utf8');

  //３．データ登録SQL作成
  $stmt = $pdo->prepare("INSERT INTO ".TABLE." (id, news_title, news_detail, view_flg, indate )VALUES(NULL, :news_title , :news_detail, :view_flg, sysdate())");
  $stmt->bindValue(':news_title', $news_title);
  $stmt->bindValue(':news_detail', $news_detail);
  $stmt->bindValue(':view_flg', $view_flg);
  $status = $stmt->execute();
  if($status==false){
    echo "SQLエラー";
    exit;
  }else{
    echo "登録完了！";
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
    <div class="navbar-header"><a class="navbar-brand" href="#">ニュース登録</a>
<a href="select.php">一覧画面へ</a>
    </div>
  </nav>
</header>
<!-- Head[End] -->
<!-- Main[Start] -->
<div class="jumbotron">
    <form method="post" action="insert.php" enctype="multipart/form-data" id="send_file">
       <div class="form-group">
       <label for="news_title">タイトル</label>
        <input type="text" name="news_title" id="news_title" class="form-control">
        </div>
         <div class="form-group">
         <label for="news_detail">内容</label>
         <textarea name="news_detail" id="news_detail" class="form-control"></textarea>
        </div>
         <div class="form-group">
            <div class="radio-inline"><label><input type="radio" name="view_flg" value="1">公開</label></div>
            <div class="radio-inline"><label><input type="radio" name="view_flg" value="0">非公開</label></div>
        </div>
        <input type="hidden" name="post_flg" value="1">
        <input type="submit" value="送信" class="btn-primary" style="margin-left:15px">
    </form>
</div>

<!-- Main[End] -->


<!-- Javascript -->
<script src="js/jquery-2.1.3.min.js"></script>
<script>
/**
* Geolocation（緯度・経度）
*/
//navigator.geolocation.getCurrentPosition( //getCurrentPosition :or: watchPosition
//  // 位置情報の取得に成功した時の処理
//  function (position) {
//    try {
//      var lat = position.coords.latitude;  //緯度
//      var lon = position.coords.longitude; //経度
//      $("#lat").val(lat);
//      $("#lon").val(lon);
//      $("#status").html("受信完了！");
//    } catch (error) {
//      console.log("getGeolocation: " + error);
//    }
//  },
//  // 位置情報の取得に失敗した場合の処理
//  function (error) {
//    var e = "";
//    if (error.code == 1) { //1＝位置情報取得が許可されてない（ブラウザの設定）
//      e = "位置情報が許可されてません";
//    }
//    if (error.code == 2) { //2＝現在地を特定できない
//      e = "現在位置を特定できません";
//    }
//    if (error.code == 3) { //3＝位置情報を取得する前にタイムアウトになった場合
//      e = "位置情報を取得する前にタイムアウトになりました";
//    }
//    $("#status").html("エラー：" + e);
//
//  }, {
//    // 位置情報取得オプション
//    enableHighAccuracy: true, //より高精度な位置を求める
//    maximumAge: 20000,        //最後の現在地情報取得が20秒以内であればその情報を再利用する設定、0はキャッシュなし
//    timeout: 10000            //10秒以内に現在地情報を取得できなければ、処理を終了
  }
);
</script>
</body>
</html>
