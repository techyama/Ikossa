<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
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
            <div class="tm-welcome-container tm-fixed-header tm-fixed-header-1">
                <div class="text-center">
                    <p class="pt-5 px-3 tm-welcome-text tm-welcome-text-2 mb-1 text-white mx-auto">あなたの性格型と<br>
                        あなたと同じ性格型の福井の偉人</p>
                </div>
            </div>

            <!-- Header image -->
            <div id="tm-fixed-header-bg"></div>
        </div>

        <main>
        <div class="row">
            <div class="col-12">
                <h2 class="tm-page-title mb-4">
                    あなたの性格型
                </h2>
            </div>
        </div>
            <div class="container-fluid px-0">
                <div class="mx-auto tm-content-container mt-4 px-3">
                    <div class="row tm-catalog-item-list mb-4">
                        <div class="col-lg-11 col-md-6 col-sm-12 tm-catalog-item">
                            <div class="tm-bg-gray">
                                <h3 class="tm-text-primary mb-3">
                                    <div class="text-center">
                                        <?php
                                        echo $category, "型<br>";
                                        echo $personality;
                                        ?>
                                </h3>
                                <p>
                                    <h4>
                                        特徴
                                    </h4>
                                    <?php
                                    echo "<ul>", $chara, "</ul>";
                                    ?>
                                    <h4>
                                        傾向
                                    </h4>
                                    <?php
                                    echo $advice;
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h2 class="tm-page-title mb-4">
                        <?php if (!isset($flg)) { ?>
                            あなたと同じ性格型の福井の偉人
                    </h2>
                </div>
            </div>
            <br>

            <div class="container-fluid px-0">
                <div class="mx-auto tm-content-container mt-4 px-3">
                <p id="atent">画像をクリックすると偉人の情報が見れます</p>
                    <div class="row tm-catalog-item-list mb-4">
                        <div class="col-lg-11 col-md-6 col-sm-12 tm-catalog-item">
                            <div class="tm-bg-gray">
                                <div class="text-center">
                                    <div id="background-2">
                                        <h3>
                                            <?php
                                            echo $heading;
                                            ?>
                                        </h3>
                                    </div>
                                    <?php
                                    echo $name, "<br>";
                                    ?>

                                    <a href="introduction.php?human_id=<? echo $human_id;?>"><img src="<?php echo $img_src; ?>" class="image"></a>

                                    <br>
                                    <?php
                                    echo $living, "年<br>";
                                    ?>
                                    <p>
                                        <?php
                                        echo $intro;
                                        ?>
                                    </p>
                                <?php } else { ?>
                                    <h3>同じ性格型の偉人が見つかりませんでした。</h3>
                                <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/parallax.min.js"></script>
</body>

</html>