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
  $stmt=$pdo->prepare("DELETE FROM ".TABLE." WHERE id=:id");
  $stmt->bindValue(":id",$_GET['id']);
  $flag = $stmt->execute();
  if($flag==false){
    echo "SQLエラー";
  }else{
    header('Location:select.php');
  }
}

//３．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM ".TABLE." ORDER BY id DESC LIMIT 5");

//４．SQL実行
$flag = $stmt->execute();

//データ表示
$view="";
if($flag==false){
  $view = "SQLエラー";
}else{
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
      $view .= <<<EOS
      <tr>
          <td>{$result['name']}</td>
          <td>{$result['email']}</td>
          <td>{$result['age']}</td>
          <td>{$result['indate']}</td>
          <td>{$result['naiyou']}</td>
          <td><a href='update.php?id={$result["id"]}'>編集</a></td>
          <td><a href='select.php?id={$result["id"]}'>削除</a></td>
      </tr>
EOS;
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>写真アップロード</title>
<link rel="stylesheet" href="css/range.css">
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>div{padding: 10px;font-size:16px;}</style>
</head>
<body id="main">
<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
    	<div class="navbar-header"><a class="navbar-brand" href="#">アンケート結果</a>
    	<a href="insert.php">登録画面へ</a>
    </div>
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<div>
    <div class="container jumbotron">
        <table class="table">
           <tr>
               <th>名前</th>
               <th>メール</th>
               <th>年齢</th>
               <th>登録日</th>
               <th>内容</th>
               <th></th>
               <th></th>
           </tr>
            <?php echo $view; ?>
        </table>
    </div>
  </div>
</div>

<!-- Main[End] -->

<!-- Javascript -->
<script src="js/jquery-2.1.3.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
