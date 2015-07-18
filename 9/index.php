<?php

include('config.php');

session_start();
if(!isset($_SESSION['USERID'])){
    header('Location:logout.php');
    exit;
}


//引数に画像パスを与えると与えられたファイル名と同じ名前（接頭詞としてsがつく）でサムネイルを作成しサムネイルフォルダへ保存
function createThumbnail($originImg){
    $thumnailExt=getExt($originImg);
    
    //拡張子によって作り方を変える
    switch($thumnailExt){
        case 'jpg':
            $sImg=imagecreatefromjpeg(PHOTO_FOLDER."/".$originImg);
            break;
        case 'jpeg':
            $sImg=imagecreatefromjpeg(PHOTO_FOLDER."/".$originImg);
            break;
        case 'png':
            $sImg=imagecreatefrompng(PHOTO_FOLDER."/".$originImg);
            break;
        case 'gif':
            $sImg=imagecreatefromgif(PHOTO_FOLDER."/".$originImg);
            break;
    }
    
    //画像の幅と高さを取得する
    $width=imagesx($sImg);
    $height=imagesy($sImg);
    if($width>$height){
        $size=$height;
        $x=floor(($width-$height) / 2);
        $y=0;
        $width=$size;
    }else{
        $side = $width;
        $y = floor( ( $height - $width ) / 2 );
        $x = 0;
        $height = $side;
    }
    
    //サムネイルの大きさを決める
    $thumbnail_width  = 200;
    $thumbnail_height = 200;
    $thumbnail = imagecreatetruecolor( $thumbnail_width, $thumbnail_height );
    
    
    imagecopyresized( $thumbnail, $sImg, 0, 0, $x, $y, $thumbnail_width, $thumbnail_height, $width, $height );
    
    switch($thumnailExt){
        case 'jpg':
            imagejpeg($thumbnail,THUMNAIL_FOLDER."/s_".$originImg);
            break;
        case 'jpeg':
            imagejpeg($thumbnail,THUMNAIL_FOLDER."/s_".$originImg);
            break;
        case 'png':
            imagepng($thumbnail,THUMNAIL_FOLDER."/s_".$originImg);
            break;
        case 'gif':
            imagegif($thumbnail,THUMNAIL_FOLDER."/s_".$originImg);
            break;
    }
}

//拡張子を返してくれる
function getExt($file){
    return end(explode('.',$file));
}



//DBに接続
try{
    $pdo = new PDO('mysql:dbname='.DBNAME.'; host='.HOST, USER, PASS);
}catch(PDOException $e){
    exit('データベース失敗'.$e->getMessage());
}

//DB文字コードを指定
$stmt = $pdo->query('SET NAMES utf8');

//データ登録
if(!isset($_POST["post_flg"])){
    //echo "パラメータが無いので登録処理は無し";
}else{
    
    //写真アップロードの処理
    if($_POST["post_flg"]==1){
    //元のファイルネーム
    $fileName=$_FILES['photoSelect']['name'];
    //一意のファイルネーム
    $uqFileName=uniqid('p').'.'.getExt($fileName);
    $tmpName=$_FILES['photoSelect']['tmp_name'];

        if(is_uploaded_file($tmpName)){
        
        if(move_uploaded_file($tmpName, "photo/".$uqFileName)){
            chmod("photo/".$uqFileName, 0644);
            $stmt = $pdo->prepare("INSERT INTO photo (id, title, koujiName, photographer, koujiShu, freeText, resistDate, fileName)VALUES(NULL, NULL, NULL, NULL, NULL, NULL, sysdate(), :fileName)");
            $stmt->bindValue(':fileName',$uqFileName);
            $status = $stmt->execute();
    
            if($status==false){
                
                echo "SQLエラー";
                exit;
                
            }
            
            createThumbnail($uqFileName);
        }
    }
    
    //２重送信の防止防止
    // header("Location: index.php");
        
    //写真削除
    }else if($_POST["post_flg"]==2){
        
        if(isset($_POST['selectedNo'])){
            $queryStr='DELETE FROM photo WHERE id IN(';
            //クエリ分に選択されたIDを足していく
                
            foreach($_POST['selectedNo'] as $val){
                $queryStr.=$val.',';
            }
            
            //最後にいらない,を削除
            $queryStr=substr($queryStr, 0, -1).")";
            var_dump($queryStr);
            //SQLの処理
            $stmt=$pdo->query($queryStr);
            
            header("Location: index.php?deleted=1");
        }
        
    }

}

//データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM photo");
//SQL実行
$flag = $stmt->execute();
//データ表示
$view="";
if($flag==false){
  $view = "SQLエラー";
}else{
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
      $view .= <<<EOS
<div class="col-xs-4 col-md-3 photo">
    <div class="checkbox"><input type="checkbox" name="selectedNo[]" value="{$result['id']}"></div>
    <a href="photo.php?pid={$result['id']}">
    <img src="photoS/s_{$result['fileName']}" class="img-responsive" />
    </a>
    <p>タイトル : {$result['title']}</p>
    <p>工事種 : {$result['koujiShu']}</p>
    <p>撮影日 : {$result['resistDate']}</p>
    <p>撮影者 : {$result['photographer']}</p>
</div>
EOS;
  }
}


//if($_SERVER["REQUEST_METHOD"]=="POST"){
//if(is_uploaded_file($_FILES["upfile"]["tmp_name"])){
//    if(move_uploaded_file($_FILES["upfile"]["tmp_name"], "files/".$_FILES["upfile"]["name"])){
//        chmod("files/".$_FILES["upfile"]["name"], 0644);
//        $name = $_POST["name"];
//        $mail = $_POST["mail"];
//        $age  = $_POST["age"];
//        $blad=$_POST["blad"];
//        $comment = $_POST["comment"];
//        $str = $_FILES["upfile"]["name"].",".$name.",".$mail.",".$age.",".$blad.",".$comment."\n";
//
//        $file = fopen("data/data.csv","a");
//        flock($file, LOCK_EX);
//        fputs($file,$str);
//        flock($file, LOCK_UN);
//        fclose($file);
//        echo "登録しました。";
//
//    }
//
//}else{
//    echo "画像が登録されていません";
//}
//}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>写真管理画面</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-select.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!--[if lt IE 9]>
     
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
    </script>
</head>

<body>
    <div class="container">
        <!-- 全体を囲むコンテナ -->
        <div class="row">
            <div id="top-memu" class="col-xs-12">
                <p id="userText">ユーザー：<?=$_SESSION['USERID'] ?></p>
                <a href="logout.php"><span class='btn btn-danger btn-sm'>ログアウト</span></a>
            </div>
            <div id="menu" class="col-xs-12">
              
<!--              追加ボタン-->
               <form method="post" action="index.php" enctype="multipart/form-data" id="send_file">
                   <input type="file" accept="image/*" capture="camera" name="photoSelect" id="photoSelect" style="display:none" onchange="$('#submitPhoto').click()">
                   <span class="glyphicon glyphicon-picture iconBtn" onclick="$('#photoSelect').click()"></span>
                   <input type="hidden" name="post_flg" value="1">
                   <input type="submit" name="submitPhoto" id="submitPhoto" style="display:none">
               </form>
               
<!--               削除ボタン-->
                   <span class="glyphicon glyphicon-trash iconBtn" onclick="$('#submitChecked').click()"></span>
<!--                   並べ替えセレクター-->
               <select name="selector" class="selectpicker">
                   <option value="1">タイトル</option>
                   <option value="2">工事種</option>
                   <option value="3">撮影日</option>
                   <option value="4">撮影者</option>
               </select>
               <span class="glyphicon glyphicon-triangle-bottom" onclick="sortChange"></span>
            </div>
        </div>

        <div class="row">
            <form method="post" action="index.php" id="photoOperation">
            
            <?=$view ?>
                
            <input type="hidden" name="post_flg" value="2">
            <input type="submit" name="submitChecked" id="submitChecked" style="display:none">
            </form>
        </div>


    </div>
    <!-- 全体を囲むコンテナ -->

    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script>
        
        var queryVal=GetQueryString();
        
        if(queryVal['deleted']==1){
            alert("削除しました");
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