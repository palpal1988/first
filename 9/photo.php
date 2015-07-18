<?php
include('config.php');

session_start();
if(!isset($_SESSION['USERID'])){
    header('Location:logout.php');
    exit;
}

try{
    $pdo = new PDO('mysql:dbname='.DBNAME.'; host='.HOST, USER, PASS);
}catch(PDOException $e){
    exit('データベース失敗'.$e->getMessage());
}

//DB文字コードを指定
$stmt = $pdo->query('SET NAMES utf8');

//アップデートの場合
if(isset($_GET['updata'])){
    
    $title=$_POST['title'];
    $photographer=$_POST['photographer'];
    $koujiShu=$_POST['koujiShu'];
    $freeText=$_POST['freeText'];
    $resistDate=$_POST['resistDate'];
    
    $stmt=$pdo->prepare('UPDATE photo SET title=:title, photographer=:photographer, koujiShu=:koujiShu, freeText=:freeText, resistDate=:resistDate WHERE id=:id');
    $stmt->bindValue(':title',$title);
    $stmt->bindValue(':photographer',$photographer);
    $stmt->bindValue(':koujiShu',$koujiShu);
    $stmt->bindValue(':freeText',$freeText);
    $stmt->bindValue(':resistDate',$resistDate);
    $stmt->bindValue(':id',$_GET['pid']);
    
    $status=$stmt->execute();
    
    if($status==false){
        echo "SQLエラー";
        exit;
    }
    //２重送信の防止防止
    header("Location: photo.php?pid=".$_GET['pid']."&updated=1");
}

$stmt = $pdo->prepare('SELECT * FROM photo WHERE id=:pid');
$stmt->bindValue(':pid',$_GET['pid']);
$stats= $stmt->execute();

while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
    $title=$row['title'];
    $koujiShu=$row['koujiShu'];
    $freeText=$row['freeText'];
    $resistDate=$row['resistDate'];
    $photographer=$row['photographer'];
    $fileName=$row['fileName'];
}
    


//データベースの変更

function updataDB(){
    echo "test";
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>写真表示画面</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/photoStyle.css" rel="stylesheet">
    <!--[if lt IE 9]>
     
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>

<body>
    <div class="container">
    <!-- 全体を囲むコンテナ -->
       <div class="col-xs-12">
           <a href="index.php">戻る</a>
       </div>
        <div class="row">
            <div class="col-xs-12 col-sm-8">
                <img src="<?='photo/'.$fileName ?>" alt="<?=$title ?>" class="img-responsive" />
            </div>
            <div class="col-xs-12 col-sm-4">
                <form  method="post" action="<?='photo.php?pid='.$_GET['pid'].'&updata=1' ?>" enctype="multipart/form-data" id="send_file">
                    <div class="form-group">
                        <label for="title">写真タイトル</label>
                        <input type="text" class="form-control" name="title" id="title" value="<?=$title ?>">
                    </div>
                    <div class="form-group">
                        <label for="koujiShu">工事種類</label>
                        <input type="text" class="form-control" name="koujiShu" id="koujiShu" value="<?=$koujiShu ?>">
                    </div>
                    <div class="form-group">
                        <label for="freeText">自由欄</label>
                        <textarea rows="5" class="form-control" name="freeText" id="freeText">
<?=$freeText ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="resistDate">撮影日</label>
                        <input type="text" class="form-control" name="resistDate" id="resistDate" value="<?=$resistDate ?>">
                    </div>
                    <div class="form-group">
                        <label for="photographer">撮影者</label>
                        <input type="text" class="form-control" name="photographer" id="photographer" value="<?=$photographer ?>">
                    </div>
                    <button class="btn btn-primary" id="changeDB">変更</button>
                    </form>
            </div>
        </div>
    </div>
    <!-- 全体を囲むコンテナ -->

    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        
        var queryVal=GetQueryString();
        
        if(queryVal['updated']==1){
            alert("更新しました");
        }
        
        function GetQueryString(){
            var result = {};
            if( 1 < window.location.search.length )
            {
                // 最初の1文字 (?記号) を除いた文字列を取得する
                var query = window.location.search.substring( 1 );

                // クエリの区切り記号 (&) で文字列を配列に分割する
                var parameters = query.split( '&' );

                for( var i = 0; i < parameters.length; i++ ){
                    // パラメータ名とパラメータ値に分割する
                    var element = parameters[ i ].split( '=' );

                    var paramName = decodeURIComponent( element[ 0 ] );
                    var paramValue = decodeURIComponent( element[ 1 ] );

                    // パラメータ名をキーとして連想配列に追加する
                    result[ paramName ] = paramValue;
                }
            }
            return result;
        }
    </script>
</body>


</html>