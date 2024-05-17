<?php
session_start();
require "db_connect.php";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>偉人知ろっさ</title>
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome/css/all.min.css"> <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

</head>
<body>
<div class="tm-page-wrap mx-auto">
    <div class="position-relative">
      <div class="potition-absolute tm-site-header">
      <div class="container-fluid position-relative">
          <div class="status">
                <?php
                if (isset($_SESSION['user'])) {
                    echo "<h4>ログイン状態：",$_SESSION['user'],"様ログイン中</h4>";
                } else {
                    echo "<h4>ログイン状態：未ログイン</h4>";
                }
                ?>
          </div>
          <nav class="navbar navbar-expand-lg mr-0 ml-auto" id="tm-main-nav">
                  <button class="navbar-toggler tm-bg-black py-2 px-3 mr-0 ml-auto collapsed" type="button" data-toggle="collapse" data-target="#navbar-nav" aria-controls="navbar-nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span>
                      <i class="fas fa-bars tm-menu-closed-icon"></i>
                      <i class="fas fa-times tm-menu-opened-icon"></i>
                    </span>
                  </button>
                  <div class="collapse navbar-collapse tm-nav" id="navbar-nav">
                    <ul class="navbar-nav text-uppercase">
                      <li class="nav-item">
                        <a class="nav-link tm-nav-link" href="top.php">ホーム</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link tm-nav-link" <?php
                         //ログイン後は表示されないように処理
                          if (!(isset($_SESSION['user']))) {
                            echo "href='login.php'>ログイン";
                          } //ログイン前は表示されないように処理
                          if (isset($_SESSION['user'])) {
                            echo "href='logout.php'>ログアウト";
                          }
                          ?>
                          </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link tm-nav-link" href="home.php">性格診断</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link tm-nav-link" href="add.php">偉人追加</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link tm-nav-link" href="history_add.php">偉人情報編集</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link tm-nav-link" href="user_remind.php">新規ユーザー登録</a>
                      </li>
                    </ul>
                </div>
          </nav>
        </div>
      </div>
      <div class="tm-welcome-container tm-fixed-header tm-fixed-header-6">
        <div class="text-center">
          <p class="pt-5 px-3 tm-welcome-text tm-welcome-text-2 mb-1 mt-lg-0 mt-5 text-white mx-auto">偉人の登録が完了しました。<br>ご協力ありがとうございました</p>
        </div>
      </div>
      <div id="tm-fixed-header-bg"></div> <!-- Header image -->
    </div>
  </div>
      <?php
      $his_id = $_POST['h_id'];
      $heading = $_POST['heading'];
      $episode = $_POST['episode'];

      //エピソード登録
      $sql = "INSERT INTO episode (id,his_id,heading,episode) VALUES (NULL,:his_id,:heading,:episode)";
      $stm = $pdo->prepare($sql);
      $stm->bindParam(':his_id',$his_id);
      $stm->bindParam(':heading',$heading);
      $stm->bindParam(':episode',$episode);
      $stm->execute();

      //登録したepi_idの抽出
      $sql = "SELECT id FROM episode ORDER BY id DESC LIMIT 1 ";
      $stm = $pdo->prepare($sql);
      $stm->execute();
      $result = $stm->fetchAll(PDO::FETCH_ASSOC);
      foreach ($result as $row) {
        $epi_id = $row['id'];
      }

      $lat = $_POST['lat'];
      $lng = $_POST['lng'];
      //座標登録
      $sql = "INSERT INTO relation_area (id,his_id,epi_id,lat,lng) VALUES (NULL,:his_id,:epi_id,:lat,:lng)";
      $stm = $pdo->prepare($sql);
      $stm->bindParam(':his_id',$his_id);
      $stm->bindParam(':epi_id', $epi_id);
      $stm->bindParam(':lat',$lat);
      $stm->bindParam(':lng',$lng);
      $stm->execute();

      //画像を登録する偉人名の抽出
      $sql = "SELECT name FROM historical WHERE id=:his_id";
      $stm = $pdo->prepare($sql);
      $stm->bindParam(':his_id',$his_id);
      $stm->execute();
      $result = $stm->fetchAll(PDO::FETCH_ASSOC);
      foreach ($result as $row) {
        $his_name = $row['name'];
      }

      //ファイルのアップロード処理
      if($_FILES['upfile']) {
        //アップロード先
        $ext = ".png";
        $save_dir = "img/".$his_name."/".mt_rand(1,100).$ext;

        //フォルダが存在しなければ新規作成
        if(file_exists('img/'.$his_name)) {
          goto end;
        } else {
          //作成が失敗したらエラー文表示
          if (mkdir('img/'.$his_name, 0700)) {
            goto end;
          } else {
            echo "フォルダの作成に失敗しました。";
          }
        }
        end:

        //作成したフォルダに画像をアップロード
        move_uploaded_file($_FILES['upfile']['tmp_name'], $save_dir);
        //画像登録、アップロード処理
        $sql = "INSERT INTO image (id,epi_id,img_src) VALUES (NULL,:epi_id,:img_src)";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':epi_id', $epi_id);
        $stm->bindParam(':img_src',$save_dir);
        $stm->execute();
      }
      
      ?>
    <input type="button" onclick="location.href='top.php'" value="トップへ戻る">
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/parallax.min.js"></script>
</body>
</html>