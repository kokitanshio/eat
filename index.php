<?php

//データファイル読み込み
$datafile = 'answer.dat';

//エラーチェックを表示
ini_set('display_errors', 1);

//エスケープを関数化する
function h($s){
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');  //出力時にecho h()でエスケープ カッコ内に受け取る値を打ち込む
}


//CSRF対策
session_start();
function setToken(){
  $token = sha1(uniqid(mt_rand(), true));  //乱数を用意しIDを取得しハッシュする
  $token = $_SESSION['token'];  //$_SESSION['token]に代入する
}
function checkToken(){  //tokenのチェックを行う関数を用意する
  if(empty($_SESSION['token']) || $_SESSION['token'] !== $_POST['token']){
    echo "不正なアクセスです！";
    exit;
  }  //tokenがセットされてないもしくはPOSTされたtokenとsetToken関数でセットしたtokenが異なる場合、echoして終了
}
//CSRF対策終わり

//各変数を定義
$name="";
$when="";
$time="";
$food="";

//処理の条件分岐
if($_SERVER['REQUEST_METHOD'] == 'POST' //サーバーにPOSTされた時
  && isset($_POST['name']) && isset($_POST['food']) && isset($_POST['when']) && isset($_POST['time']) //各項目がセットされている場合
  ){

    checkToken();

    //POSTされた各値を変数に代入
    $name = $_POST['name'];
    $when = $_POST['when'];
    $time = $_POST['time'];
    $food = $_POST['food'];
    $data = "";
    $answerList = "";

    if($when !== '' &&  //when time food が記入済みの時のみ処理
      $time !== '' &&
      $food !== '' 
    ){
      $name = ($name === '') ? 'ななしさん' : $name; //nameが未記入の時「ななしさん」として処理 記入されてる時その値をとる

      //投稿日を$dateに代入
      $date = date('Y年m月d日');
      
      //投稿者の情報を$answerListに代入
      $answerList = $name . " " . $when . " " . $time . " " . $food . " " . $date . "\n" ;
      
      //$datafileに投稿者情報を上書きする
      $fp = fopen($datafile, "a");
      fwrite($fp, $answerList);
      fclose($fp);

        // function classWhen($when){
        //   if($when==="朝食"){
        //     return "morning";
        //   }elseif($when==="昼食"){
        //     return "lunch";
        //   }elseif($when==="おやつ"){
        //     return "snuck";
        //   }elseif($when==="夕食"){
        //     return "dinner";
        //   }elseif($when==="夜食"){
        //     return "night";
        //   }
        // }

      
    } //when time food が記入済みの時のみ処理
  }else{
    setToken();
  }

  //$datafileを$postsという配列にする
  $posts = file($datafile, FILE_IGNORE_NEW_LINES);
  $posts = array_reverse($posts);


?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- twitter card -->
  <meta name="twitter:card" content="summary" />
  <meta name="twitter:site" content="@kokitanshio73" />
  <meta property="og:url" content="記事のURL" /> 
  <meta property="og:title" content="昨日なに食べた？" /> 
  <meta property="og:description" content="昨日なに食べた？昨日のご飯をシェアしよう！シークレットを探してTwitterに投稿しよう！" /> 
  <meta property="og:image" content="twittercard.png" /> 
  <title>昨日なに食べた？</title>
  <!-- fontawesome -->
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <!-- googlefont -->
  <link href="https://fonts.googleapis.com/css?family=M+PLUS+1p:400,500,700,900&display=swap&subset=japanese,latin-ext,vietnamese" rel="stylesheet">
  <!-- css -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <div class="header_inner">
      <h1>
        <div class="click">↓click here !</div>
        <i class="fas fa-utensils" id="seacret"></i>
        <span>昨日<strong>なに</strong>食べた？</span>
        <i class="fas fa-utensils"></i>
      </h1>
      <p>昨日のご飯をシェアしよう！</p>
    </div>

      <p class="hide first">T</p>
      <p class="hide second">h</p>
      <p class="hide third">i</p>
      <p class="hide forth">s</p>
      <p class="hide fifth">i</p>
      <p class="hide sixth">s</p>
      <p class="hide seventh">た</p>
      <p class="hide eighth">ん</p>
      <p class="hide ninth">
        <a href="https://twitter.com/intent/tweet?text=昨日なに食べた？シークレットを見つけたよ！&via=kokitanshio73" target="_blank">
          し
        </a>
      </p>
      <p class="hide tenth">お</p>

  </header>
  <div class="main">
    <div class="main_inner">
      <form action="" method="POST">
        <div class="form_name">
          <label for="name">お名前：</label>
          <input id="name" type="text" name="name" placeholder="たんしお">
        </div>
        <div class="form_select">
          <select name="when" id="when" required>
            <?php $whens = ["朝食","昼食","おやつ","夕食","夜食"]; ?>
            <option selected disabled label="--"></option>
            <?php foreach($whens as $value): ?>
            <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
            <?php endforeach; ?>
          </select>
          <label for="when">として</label>
          <select name="time" id="time" required>
            <option selected disabled label="--"></option>
            <?php for($i=0; $i<=23; $i++): ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
          </select>
          <label for="time">時ごろ</label>
        </div>
        <div class="form_food">
          <label for="food">食べたもの：</label>
          <input id="food" type="text" name="food" placeholder="例：カレー" required>
        </div>
        <input id="btn" type="submit" value="投稿！">
        <input type="hidden" name="token" value="<?=h($_SESSION['token']); ?>">
      </form>
      <div class="answer">
        <div class="bar"></div>
        <h2>投稿一覧（<?php echo count($posts); ?>件）</h2>
        <ul>
          <?php if(empty($posts)): ?>
          <li>まだ投稿がありません</li>
          <?php else: ?>
            <?php foreach($posts as $post): ?>
            <?php list($name, $when, $time, $food, $date) = explode(" ", $post); ?>
            <li><i class="fas fa-utensils"></i><b><?=h($name); ?></b>は<?=h($date); ?><?=h($time); ?>時ごろ<span><?=h($when); ?></span>として<strong><?=h($food); ?></strong>を食べました。</li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
  <!-- jquery -->
  <script src="jquery.js"></script>
</body>
</html>