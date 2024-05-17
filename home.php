<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>偉人知ろっさ</title>
    <link href="css/style.css" rel="stylesheet">
    <link href="css/button.css" rel="stylesheet">
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
            <div class="tm-welcome-container tm-fixed-header tm-fixed-header-2">
                <div class="text-center">
                    <p class="pt-5 px-3 tm-welcome-text tm-welcome-text-2 mb-1 mt-lg-0 mt-5 text-white mx-auto">性格診断を開始します<br>下の質問に全て答えてください</p>
                </div>
            </div>
            <div id="tm-fixed-header-bg"></div> <!-- Header image -->
        </div>

        <main>
            <div class="container-fluid px-0">
                <form name="answer" method="GET" action="test.php" onsubmit="check(this);return false;">
                    <div class="tm-content-container">
                        <div class="row mt-3 mb-5 pb-3">
                            <div class="col-12">
                                <div class="tm-about-text-container px-3">
                                    <p class="mb-4">
                                        <?php
                                        require 'db_connect.php';
                                        $sql = 'SELECT * FROM question';  //  質問をDBから抽出
                                        $stm = $pdo->prepare($sql);
                                        $stm->execute();
                                        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
                                        $qnum = 1;
                                        //DBに格納されている質問の数だけループを回す
                                        foreach ($result as $row) {
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 pt-3">
                    <div class="col-xl-8 col-lg-12 mb-4">
                        <div class="tm-bg-gray p-5 h-100">
                                <div class="que">質問<?php echo $qnum; ?>:<?php echo $row['question']; ?></div>
                                <p>
                                    <ul class="nav">
                                        <li class="nav-item"><input type="radio" class="nav-link tm-paging-link visually-hidden-home" name="q<?php echo $qnum; ?>" id="question<?php echo $qnum; ?>yes" value="1"><label for="question<?php echo $qnum; ?>yes"></label><?php echo $row['answer1']; ?></li>
                                        <li class="nav-item"><input type="radio" class="nav-link tm-paging-link visually-hidden-home" name="q<?php echo $qnum; ?>" id="question<?php echo $qnum; ?>no" value="-1"><label for="question<?php echo $qnum; ?>no"></label><?php echo $row['answer2']; ?></li>
                                        <input type="hidden" name="q<?php echo $qnum; ?>type" value="<?php echo $row['type']; ?>">
                                    </ul>
                                </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mx-auto tm-about-text-container px-3">
                            <p class="mb-4">
                            <?php
                                            $qnum++;
                                        }
                            ?>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary rounded-0 d-block ml-auto tm-btn-animate tm-btn-submit tm-icon-submit" value="診断する">
                            </div>
                        <!--質問数送信 -->
                            <input type="hidden" name="qnum" value="<?php echo ($qnum - 1); ?>">
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script>
        //フォーム送信時のラジオボタンの入力チェック
        function check(question) {
            var flg = false;

            //質問の数ループを回し、1つずつ判定
            <?php for ($i = 1; $i <= 12; $i++) { ?>
                var elem;
                elem = question.q<?php echo $i; ?>.value;

                if (elem) { //入力されていたらフラグ変更
                    flg = true;
                } else { //未入力なら
                    flg = false;
                    alert("質問<?php echo $i ?>が未回答です。全ての質問に回答してください。");
                    return false;
                }
            <?php } ?>
            //全て入力されていたらsubmit
            document.answer.submit();
        }
    </script>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/parallax.min.js"></script>


</body>

</html>