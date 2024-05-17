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
      <div class="tm-welcome-container tm-fixed-header tm-fixed-header-10">
        <div class="text-center">
          <p class="pt-5 px-3 tm-welcome-text tm-welcome-text-2 mb-1 mt-lg-0 mt-5 text-white mx-auto">性格型一覧<br>１６タイプの特徴と、その性格型の偉人が見れます</p>
        </div>
      </div>
      <div id="tm-fixed-header-bg"></div> <!-- Header image -->
    </div>
  </div>

  <?php
  require 'db_connect.php';
  $sql = "SELECT * FROM personality";
  $stm = $pdo->prepare($sql);
  $stm->execute();
  $result = $stm->fetchAll(PDO::FETCH_ASSOC);
  $i = 0;
  foreach ($result as $row) {
    $i++;
    $id = $row['id'];
    $category = $row['category'];
    $sql = "SELECT name, img_src FROM historical WHERE  category = (:category) ORDER BY id ASC LIMIT 1";  //判定した性格型の偉人情報を格納
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':category', $category);
    $stm->execute();
    $result2 = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result2 as $row) {
      $img_src = $row['img_src'];
      $h_name = $row['name'];
    }
    if ($i == 1) {
      echo "<ul>";
    }
  ?>
    <li>
      <div class="position-relative tm-thumbnail-container">
        <img src=<?php echo $img_src; ?> alt="Image" width="220" height="220">
        <h3><?php echo $category; ?>型</h3>
        <a href=<?php echo "type.php?id=$id"; ?> class="position-absolute tm-img-overlay">
          <i class="fas fa-play tm-overlay-icon"></i>
        </a>
      </div>
      <div class="p-4 tm-bg-gray tm-catalog-item-description">
        <h3 class="tm-text-primary mb-3 tm-catalog-item-title"><?php echo "<a href=", "type.php?id=$id", ">", "</a>"; ?></h3>
        <p class="tm-catalog-item-text">
          この性格型の偉人：<?php echo $h_name; ?>
        </p>
      </div>
    </li>
  <?php
    if ($i == 4) {
      echo "</ul>";
      $i = 0;
    }
  }
  ?>

  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/parallax.min.js"></script>
</body>

</html>