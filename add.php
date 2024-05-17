<?php
session_start();
//ログインしていなければ促すページへ
if(!isset($_SESSION['user'])) {
    header('location: alert.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
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
            <div class="tm-welcome-container tm-fixed-header tm-fixed-header-5">
                <div class="text-center">
                    <p class="pt-5 px-3 tm-welcome-text tm-welcome-text-2 mb-1 mt-lg-0 mt-5 text-white mx-auto">
                        偉人追加画面<br>歴史に詳しい方など情報追加に協力していただけると幸いです
                    </p>
                </div>
            </div>

            <div id="tm-fixed-header-bg"></div> <!-- Header image -->
        </div>



        <div class="text-center">
            <div class="col-12">
                <div class="mx-auto tm-about-text-container ">
                    <p class="mb-4">
                        <form class="form" name="profile" method="POST" action="db_add.php" onsubmit="check(this);return false;" enctype="multipart/form-data">
                            <div class="row tm-catalog-item-list ">
                                <div class="col-lg-11 col-md-6 col-sm-12 tm-catalog-item">
                                    <div class="tm-bg-gray">
                                        <h3 class="tm-text-primary mb-3">
                                            <ol>偉人の顔写真（未入力可）</ol>
                                        </h3>
                                        <input type="file" name="photo" accept=".png, .jpg, .jpeg"><br><br>
                                    </div>
                                </div>
                            </div>

                            <div class="row tm-catalog-item-list ">
                                <div class="col-lg-11 col-md-6 col-sm-12 tm-catalog-item">
                                    <div class="tm-bg-gray">
                                        <h3 class="tm-text-primary mb-3">
                                            <ol>偉人の名前</ol>
                                        </h3>
                                        <input type="text" name="name" placeholder="フルネーム"><br><br>
                                    </div>
                                </div>
                            </div>

                            <div class="row tm-catalog-item-list ">
                                <div class="col-lg-11 col-md-6 col-sm-12 tm-catalog-item">
                                    <div class="tm-bg-gray">
                                        <h3 class="tm-text-primary mb-3">
                                            <ol>偉人の生きていた西暦（未入力可）</ol>
                                        </h3>
                                        <input id="year" type="text" name="living1">
                                        年～
                                        <input id="year" type="text" name="living2">
                                        年
                                        <br><br>
                                    </div>
                                </div>
                            </div>

                            <div class="row tm-catalog-item-list ">
                                <div class="col-lg-11 col-md-6 col-sm-12 tm-catalog-item">
                                    <div class="tm-bg-gray">
                                        <h3 class="tm-text-primary mb-3">
                                            <ol>偉人とゆかりのある地名</ol>
                                        </h3>
                                        <select name="area">
                                            <optgroup label="嶺北">
                                                <option value="福井市">福井市</option>
                                                <option value="永平寺町">永平寺町</option>
                                                <option value="坂井市">坂井市</option>
                                                <option value="あわら市">あわら市</option>
                                                <option value="鯖江市">鯖江市</option>
                                                <option value="池田町">池田町</option>
                                                <option value="越前市">越前市</option>
                                                <option value="越前町">越前町</option>
                                                <option value="南越前町">南越前町</option>
                                                <option value="大野市">大野市</option>
                                                <option value="勝山市">勝山市</option>
                                            </optgroup>

                                            <optgroup label="嶺南">
                                                <option value="敦賀市">敦賀市</option>
                                                <option value="美浜町">美浜町</option>
                                                <option value="若狭町">若狭町</option>
                                                <option value="小浜市">小浜町</option>
                                                <option value="おおい町">おおい町</option>
                                                <option value="高浜町">高浜町</option>
                                            </optgroup>
                                        </select><br><br>
                                    </div>
                                </div>
                            </div>

                            <div class="row tm-catalog-item-list ">
                                <div class="col-lg-11 col-md-6 col-sm-12 tm-catalog-item">
                                    <div class="tm-bg-gray">
                                        <h3 class="tm-text-primary mb-3">
                                            <ol>その人を一言で表すなら？</ol>
                                        </h3>
                                        <input type="text" name="heading" placeholder="例：洪水の危機から福井を救った英雄">
                                        <br><br><br>
                                    </div>
                                </div>
                            </div>

                            <div class="row tm-catalog-item-list ">
                                <div class="col-lg-11 col-md-6 tm-catalog-item">
                                    <div class="tm-bg-gray">
                                        <h3 class="tm-text-primary mb-3">
                                            <ol>その人に合う選択肢を選んでください</ol><br>
                                        </h3>

                                        <ul class="nav tm-paging-links">
                                            <div class="d-flex flex-column bd-highlight mb-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <li class="nav-item">
                                                    外向的
                                                </li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="ei" id="emax" value="1"><label for="emax"></label></li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="ei" id="emin" value="2"><label for="emin"></label></li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="ei" id="imin" value="3"><label for="imin"></label></li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="ei" id="imax" value="4"><label for="imax"></label></li>
                                                <?php  //度合の入力

                                                ?>
                                                <li class="nav-item">
                                                    内向的
                                                </li>
                                            </div>
                                                <br><br><br>

                                                <div class="d-flex align-items-center justify-content-center">
                                                <li class="nav-item">
                                                    現実的
                                                </li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="sn" id="smax" value="1"><label for="smax"></label></li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="sn" id="smin" value="2"><label for="smin"></label></li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="sn" id="nmin" value="3"><label for="nmin"></label></li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="sn" id="nmax" value="4"><label for="nmax"></label></li>
                                                <?php  //度合の入力

                                                ?>
                                                <li class="nav-item">
                                                    夢想家
                                                </li>
                                                </div>
                                                <br><br><br>

                                                <div class="d-flex align-items-center justify-content-center">
                                                <li class="nav-item">
                                                    論理優位
                                                </li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="tf" id="tmax" value="1"><label for="tmax"></label></li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="tf" id="tmin" value="2"><label for="tmin"></label></li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="tf" id="fmin" value="3"><label for="fmin"></label></li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="tf" id="fmax" value="4"><label for="fmax"></label></li>
                                                <?php  //度合の入力

                                                ?>
                                                <li class="nav-item">
                                                    感情優位
                                                </li>
                                                </div>
                                                <br><br><br>

                                                <div class="d-flex align-items-center justify-content-center">
                                                <li class="nav-item">
                                                    安定を求める
                                                </li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="jp" id="jmax" value="1"><label for="jmax"></label></li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="jp" id="jmin" value="2"><label for="jmin"></label></li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="jp" id="pmin" value="3"><label for="pmin"></label></li>
                                                <li class="nav-item"><input class="nav-link tm-paging-link visually-hidden" type="radio" name="jp" id="pmax" value="4"><label for="pmax"></label></li>
                                                <?php  //度合の入力

                                                ?>
                                                <li class="nav-item">
                                                    変革を求める
                                                </li>
                                                </div>
                                                <br><br><br>
                                            </div>
                                        </ul>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary rounded-0 d-block ml-auto tm-btn-animate tm-btn-submit tm-icon-submit" value="この情報で追加">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    //フォーム送信時の入力チェック
    function check(question){
        var errors = [];
        var name = question.name.value;
        var heading = question.heading.value;
        var ei = question.ei.value;
        var sn = question.sn.value;
        var tf = question.tf.value;
        var jp = question.jp.value;

        //名前が未入力
        if (name == '') { 
            errors.push("名前");
        }

        //偉人の一言が未入力
        if (heading == '') { 
            errors.push("偉人の一言");
        }

        //偉人の性格が未入力
        if (ei == '' || sn == '' || tf == '' || jp == '') { 
            errors.push("偉人の性格");
        }
        console.log("EI:"+ei+"SN:"+sn+"TF:"+tf+"JP:"+jp);

        //エラーが1つでもあれば
        if (errors.length) { 
            alert(errors+"が未回答です。");
            return false;

        //エラーがなければsubmit
        } else {
            document.profile.submit();            
        }
    }
    </script>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/parallax.min.js"></script>

</body>

</html>