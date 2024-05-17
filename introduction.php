<?php
session_start();
require "db_connect.php";
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
      <div class="tm-welcome-container tm-fixed-header tm-fixed-header-6">
        <div class="text-center">
          <p class="pt-5 px-3 tm-welcome-text tm-welcome-text-2 mb-1 mt-lg-0 mt-5 text-white mx-auto">偉人のゆかりある地<br>マップ上のマーカーを押すとエピソードが出てきます</p>
        </div>
      </div>
      <div id="tm-fixed-header-bg"></div> <!-- Header image -->
    </div>
  </div>

  <?php
  $id = $_GET["human_id"];

  $sql = 'SELECT * FROM historical WHERE id = :id';  //受け取ったIDの偉人データを抽出
  $stm = $pdo->prepare($sql);
  $stm->bindValue(':id', $id, PDO::PARAM_INT);
  $stm->execute();
  $result = $stm->fetchAll(PDO::FETCH_ASSOC);

  foreach ($result as $row) {
    $h_id = $row['id'];
    $name = $row['name'];
    $heading = $row['heading'];
    $living = $row['living'];
    $img_src = $row['img_src'];
  }
  ?>

  <?php
  $sql = 'SELECT * FROM relation_area WHERE his_id = :id';  //  偉人のIDからゆかりのある地のデータ抽出
  $stm = $pdo->prepare($sql);
  $stm->bindValue(':id', $h_id, PDO::PARAM_INT);
  $stm->execute();
  $result = $stm->fetchAll(PDO::FETCH_ASSOC);
  $i = 0;
  foreach ($result as $row) {    //JS変数dataに位置情報格納
    $_SESSION['epi_id'][$i] = $row['epi_id'];
    $lat[] = $row['lat'];
    $lng[] = $row['lng'];
    $i++;
  }
  ?>


  <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD6HyUtVl2SvfWpmawfdO4XEii297iAFUQ&callback=map_canvas"></script>
  <script>
    //座標識別変数
    var z = 0;

    function map_canvas() {
      const url = 'map.php?id=';
      //マーカーの情報
      var data = new Array();
      var id = 0
      var lat = <?php echo json_encode($lat); ?>;
      var lng = <?php echo json_encode($lng); ?>;
      for (var i = 0; i < <?php echo json_encode(count($result)); ?>; i++) {
        data.push({
          id: <?php echo json_encode($_SESSION['epi_id']); ?>[i], //エピソード識別ID
          lat: lat[i], //緯度
          lng: lng[i], //経度
          url: url //リンク先
        });
      }
      //初期位置に、上記配列の一番初めの緯度経度を格納
      var latlng = new google.maps.LatLng(data[z].lat, data[z].lng);

      var opts = {
        zoom: 15, //地図の縮尺
        center: latlng, //初期位置の変数
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };

      //地図を表示させるエリアのidを指定
      var map = new google.maps.Map(document.getElementById("gmap_canvas"), opts);

      //マーカーを配置するループ
      for (i = 0; i < data.length; i++) {
        var arufa = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];
        var markers = new google.maps.Marker({
          position: new google.maps.LatLng(data[i].lat, data[i].lng),
          map: map,
          icon: {
            fillColor: "#FF0000", //塗り潰し色
            fillOpacity: 0.8, //塗り潰し透過率
            path: google.maps.SymbolPath.CIRCLE, //円を指定
            scale: 16, //円のサイズ
            strokeColor: "#FF0000", //枠の色
            strokeWeight: 1.0 //枠の透過率
          },
          label: {
            text: arufa[i], //ラベル文字
            color: '#FFFFFF', //文字の色
            fontSize: '20px' //文字のサイズ
          }
        });
        //クリックしたら指定したurlに遷移するイベント
        google.maps.event.addListener(markers, 'click', (function(url, id) {
          return function() {
            location.href = url + id;
          };
        })(data[i].url, data[i].id));
      }
    }
    //クリックしたボタンの場所にmapの中央を合わせる
    function map_move(pointer){
      //座標識別変数を変更して再度地図描画
      z = pointer;
      map_canvas();
    }

    

    //地図描画を実行
    google.maps.event.addDomListener(window, 'load', map_canvas);
  </script>

  <?php
  $sql = 'SELECT heading FROM episode WHERE his_id = :id';  //受け取ったIDの偉人を抽出
  $stm = $pdo->prepare($sql);
  $stm->bindValue(':id', $h_id, PDO::PARAM_INT);
  $stm->execute();
  $result2 = $stm->fetchAll(PDO::FETCH_ASSOC);
  $arufa = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];
  $i = 0;
  ?>

  <div class="tm-page-wrap mx-auto">
    <div class="container-fluid px-0">
      <div class="mx-auto tm-content-container">
        <div class="mx-auto pb-3 tm-about-text-container px-3">
          <div class="row">
            <div class="col-lg-6 mb-5 mapouter">
              <div class="text-center">
                <h3><?php echo $heading; ?></h3>
                <?php echo $name, "<br>"; ?>
                <img src="<?php echo $img_src; ?>" class="image">
                <br>
                <?php echo $living, "年<br>"; ?>
                <?php
                if (!empty($result2)) {
                ?>
                <p>下のボタンをクリックすると地図の中心が動きます</p>
                <?php
                  foreach ($result2 as $row) {
                ?>
                    <input type="button" value="<?php echo $arufa[$i], "：", $row['heading']; ?>" onclick="map_move(<?php echo $i; ?>);">
                  <?php
                    $i++;
                  }
                  ?>
                <?php
                } else {
                  echo "エピソード準備中";
                }
                ?>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="text-center">
                <h4>ゆかりのある地</h4>
                <div class="mapouter mb-60">
                  <div id="gmap_canvas">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/parallax.min.js"></script>

</body>

</html>