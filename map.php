<?php
session_start();
$id = $_GET['id'];
require 'db_connect.php';
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
              echo "<h4>ログイン状態：", $_SESSION['user'], "様ログイン中</h4>";
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
                                                  ?> </a>
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

      <div class="tm-welcome-container tm-fixed-header tm-fixed-header-7">
        <div class="text-center">
          <div class="tm-welcome-container-inner">
            <p class="tm-welcome-text mb-1 text-white">下のボタンを押すと性格診断が始まります</p>
            <p class="tm-welcome-text mb-5 text-white">あなたの性格に似ている福井の偉人が出てきます</p>
          </div>
        </div>
      </div>

      <div id="tm-fixed-header-bg"></div> <!-- Header image -->
    </div>
  </div>

  <?php
  $sql1 = 'SELECT id,heading,episode FROM episode WHERE id = :id';  //受け取ったIDのエピソードを抽出
  $stm1 = $pdo->prepare($sql1);
  $stm1->bindValue(':id', $id, PDO::PARAM_INT);
  $stm1->execute();
  $result = $stm1->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <div class="tm-page-wrap mx-auto">
    <div class="container-fluid position-relative">
      <div class="mx-auto tm-content-container">
        <div class="row mt-3 mb-5 pb-3">
          <div class="col-12">
            <div class="mx-auto tm-about-text-container px-3">

              <?php
              if (!empty($result)) {
                foreach ($result as $rows) {
              ?>

                  <div class="episode">
                    <h2 class="tm-page-title mb-4 tm-text-primary">
                      <?php echo $rows['heading']; ?>
                    </h2>
                  </div>
                  <?php
                  $sql2 = 'SELECT img_src FROM image WHERE epi_id = :id';  //エピソードごとの写真を抽出
                  $stm2 = $pdo->prepare($sql2);
                  $stm2->bindValue(':id', $rows['id'], PDO::PARAM_INT);
                  $stm2->execute();
                  $image = $stm2->fetchAll(PDO::FETCH_ASSOC);
                  ?>

                  <?php
                  echo $rows['episode'];
                }
                  ?>
            </div>
          </div>
        </div>
        <div id="center">
          <?php
                foreach ($image as $row) {
          ?>
            <img src="<?php echo $row['img_src']; ?>" class="image">
          <?php } ?>
        </div>
      </div>
  <?php
              } else {
                echo "エピソード準備中";
              }
  ?>

    </div>
  </div>

  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/parallax.min.js"></script>
</body>

</html>