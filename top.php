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
                            echo "<h4>ログイン状態：", $_SESSION['user'], "様ログイン中</h4>";
                        } else {
                            echo "<h4>ログイン状態：未ログイン</h4>";
                        }
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-5 col-md-8 ml-auto mr-0">
                            <div class="tm-site-nav">
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
                                                                                ?> </a> </li> <li class="nav-item">
                                                    <a class="nav-link tm-nav-link" href="home.php">性格診断</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link tm-nav-link" href="add.php">偉人追加</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link tm-nav-link" href="history_add.php">偉人情報編集</a>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tm-welcome-container tm-fixed-header tm-fixed-header-4">
                <div class="text-center">
                    <div class="tm-welcome-container-inner">
                        <p class="tm-welcome-text mb-1 text-white">下のリンクを押すと性格診断が始まります</p>
                        <p class="tm-welcome-text mb-5 text-white">あなたの性格に似ている福井の偉人が出てきます</p>

                    </div>
                </div>
            </div>

            <div id="tm-fixed-header-bg"></div> <!-- Header image -->
        </div>


        <div class="col-lg-11 col-md-6 col-sm-12 tm-catalog-item">
            <h2 class="tm-page-title mb-4">最近追加された偉人たち</h2>
            <input type="button" class="btn btn-primary rounded-0 d-block ml-auto tm-btn-animate tm-btn-submit tm-icon-submit" onclick="location.href='home.php'" value="性格診断を始めます"><br>
            <input type="button" class="btn btn-primary rounded-0 d-block ml-auto tm-btn-animate tm-btn-submit tm-icon-submit" onclick="location.href='add.php'" value="偉人追加"><br>
            <input type="button" class="btn btn-primary rounded-0 d-block ml-auto tm-btn-animate tm-btn-submit tm-icon-submit" onclick="location.href='16type.php'" value="性格型一覧">
        </div>
    </div>


    <div class="row tm-catalog-item-list">
        <?php
        $sql = 'SELECT name,id,img_src,heading FROM historical ORDER BY id DESC LIMIT 3';
        $prepare = $pdo->prepare($sql);
        $prepare->execute();
        $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $id = $row['id'];
        ?>



            <div class="col-lg-4 col-md-6 col-sm-12 tm-catalog-item1">
                <div class="position-relative tm-thumbnail-container">
                    <img src=<?php echo $row['img_src']; ?> alt="Image" class="img-fluid tm-catalog-item-img">
                    <a href=<?php echo "introduction.php?human_id=$id"; ?> class="position-absolute tm-img-overlay">
                        <i class="fas fa-play tm-overlay-icon"></i>
                    </a>
                    <div class="p-4 tm-bg-gray tm-catalog-item-description">
                        <h3 class="tm-text-primary mb-3 tm-catalog-item-title"><?php echo "<a href=", "introduction.php?human_id=$id", ">", $row['name'], "</a>"; ?></h3>
                        <p class="tm-catalog-item-text">
                            <?php echo $row['heading'] ?>
                        </p>
                    </div>
                </div>
            </div>

    </div>
<?php
        }
?>
<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/parallax.min.js"></script>
</body>

</html>