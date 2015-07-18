<?php

//データーベース情報を定義
define('DBNAME','photoruction');
define('HOST','localhost');
define('USER','root');
define('PASS','');
define('TABLE','photo');

//フォルダ
define('PHOTO_FOLDER', 'photo');
define('THUMNAIL_FOLDER', 'photoS');


function connectDB(){
    //DBに接続
    try{
        $pdo = new PDO('mysql:dbname='.DBNAME.'; host='.HOST, USER, PASS);
    }catch(PDOException $e){
        exit('データベース失敗'.$e->getMessage());
    }

    //DB文字コードを指定
    $stmt = $pdo->query('SET NAMES utf8');
    
    return $stmt;
}

?>