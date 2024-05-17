<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>偉人しろっさ</title>
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
      <div class="tm-welcome-container tm-fixed-header tm-fixed-header-9">
        <div class="text-center">
          <div class="tm-welcome-container-inner">
            <p class="tm-welcome-text mb-1 text-white">ログイン画面</p>
            <p class="tm-welcome-text mb-5 text-white">ユーザー名とパスワードを入力してください。</p>

          </div>
        </div>
      </div>

      <div id="tm-fixed-header-bg"></div> <!-- Header image -->
    </div>
  </div>
  <div id="center">
  <form method="POST" action="confirm_login.php">
    <p>ユーザー名：<input type="text" name="name" required></p>
    <p>パスワード：<input type="password" name="password" required></p>
    <p><input type="submit" value="ログイン"></p>
  </from>
  </div>
    <?php  //エラー文表示
    if (!empty($_SESSION['error'])) {
      echo $_SESSION['error'];
      require 'session_delete.php';
    }
    ?>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/parallax.min.js"></script>
</body>
</html>