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

//３．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM ".TABLE." ORDER BY id DESC");

//４．SQL実行
$flag = $stmt->execute();

//データ表示
$view="";
if($flag==false){
  $view = "SQLエラー";
}else{
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
      
      if($result['view_flg']==1){
      $view .= <<<EOS
<li><dl><dt class="news-date clearfix"><span class="news_tags">{$result['news_title']}</span></dt>
<dd class="news-title">{$result['news_detail']}</dd></dl></li>
EOS;
  }
  }
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>チーズアカデミーTOKYO</title>
    <meta charset="UTF-8">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/jquery-2.1.0.min.js"></script>
    <script>
    $(function(){
        $(window).on("ready resize",function(){
        $(".slides").css({"width":1440,
                         "position":"relative",
                          left:-(1440-$(window).width())/2
                         });
        });
    });
    </script>
</head>
<body>
    <div class="wrapper">
        <!-- header  -->
        <header id="header">
           <div class="inner clearfix">
            <h1 class="logo"><img src="../image/logo-top.png" alt="Cheese Academy Tokyo" /></h1>
            <ul class="note_wrap">
                <li>CHEESE DEVELOPMENT</li>
                <li>GROWTH CHEESE</li>
                <li>CHEESE PERSPECTIVE</li>
                <li>CHEESE GENERATOR</li>
            </ul>
            </div>
        </header>

        <!--　nav_lower   -->
        <section class="nav_lower">
            <div class="inner">
                <nav class="global_Nav">
                    <?php include("nav.html"); ?>
            </div>
        </section>
    
        <!--news_lower    -->
        <section id="news_lower">
            <div class="news_lower_heading">
            <div class="inner clearfix">
                <div class="section-heading-wrap">
                    <h2 class="section-title white">NEWS</h2>
                    <p class="section-note">2015.04.03</p>
                </div>
            </div>
            </div>

            <div class="inner">
                <ul class="news_list clearfix">
<!--
                    <li> 
                    <dl>
	                    <dt class="news-date clearfix"><span class="news_tags">タイトル</span></dt>
	                    <dd class="news-title">詳細情報・・・・</dd>
	                    </dl>
                    </li>
-->
               <?=$view ?>
                </ul>
            </div>
        </section>
   
        <!--#entry    -->
        <section id="entry" class="contents-box">
            <div class="contents-heading bg-yellow">
                <h2 class="section-title">ENTRY</h2>
                <p class="section-note white">説明会に申し込む</p>
            </div>
            <p class="entry-summary">入学をご希望の方に向けて、学校説明会を実施しております。<br />
    フォームからお申し込みください。（無料・完全予約制）</p>
            <a class="entry-btn">
                <p class="entry-btn-title">ENTRY</p>
                <p class="entry-btn-note">説明会に申し込む</p>
            </a>
        </section>
        <!--end #entry-->
    
        <!--#information    -->
        <section id="information" class="contents-box">
            <h2 class="section-title white">INFORMATION</h2>
            <p class="section-note white">基本情報</p>
            <div class="inner">
                <ul class="footer-list-wrap clearfix">
                    <li><img src = "../image/space_img.png" alt="space_image" width="300" height="220"></li>
                    <li><iframe width="300" height="220" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.google.co.jp/maps?f=q&amp;source=s_q&amp;hl=ja&amp;geocode=&amp;q=%E3%80%92150-8510+%E6%9D%B1%E4%BA%AC%E9%83%BD%E6%B8%8B%E8%B0%B7%E5%8C%BA%E6%B8%8B%E8%B0%B72-21-1&amp;aq=&amp;sll=36.5626,136.362305&amp;sspn=50.435477,91.494141&amp;brcurrent=3,0x60188b5856d2e561:0x967b699ec614e442,0,0x60188b5851109bad:0x66009940d887780c&amp;ie=UTF8&amp;hq=&amp;hnear=%E3%80%92150-0002+%E6%9D%B1%E4%BA%AC%E9%83%BD%E6%B8%8B%E8%B0%B7%E5%8C%BA%E6%B8%8B%E8%B0%B7%EF%BC%92%E4%B8%81%E7%9B%AE%EF%BC%92%EF%BC%91%E2%88%92%EF%BC%91&amp;t=m&amp;z=14&amp;ll=35.659025,139.703473&amp;output=embed"></iframe></li>
                    <li><img src = "../image/space_img.png" alt="space_image" width="300" height="220"></li>
                </ul>
                <p class="footer-caution">※実際にはチーズアカデミーという学校は存在しません。<br />
        くれぐれも間違ってデジタルハリウッドにお問い合わせすることのないようにご注意ください。</p>
            </div>
        </section>
        <!--end #information-->
    </div>
<script>
$(".news_list_modifer").css("display","none");
$(".btn-reading").on("click",function(){
    $(".news_list_modifer").fadeIn(400);
});
</script>
</body>
</html>