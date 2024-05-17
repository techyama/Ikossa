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
          <p class="pt-5 px-3 tm-welcome-text tm-welcome-text-2 mb-1 mt-lg-0 mt-5 text-white mx-auto">エピソード追加画面</p>
        </div>
      </div>
      <div id="tm-fixed-header-bg"></div> <!-- Header image -->
    </div>
  </div>
  </div>
  <main>
    <?php
    require 'db_connect.php';

    if (!isset($h_id)) {
      $h_id = $_GET['human_id'];
    }
    $sql = 'SELECT * FROM historical WHERE id = :id';  //受け取ったIDの偉人データを抽出
    $stm = $pdo->prepare($sql);
    $stm->bindValue(':id', $h_id,);
    $stm->execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
      $h_id = $row['id'];
      $name = $row['name'];
      $personality = $row['category'];
      $heading = $row['heading'];
      $living = $row['living'];
      $img_src = $row['img_src'];
    }
    ?>
    <div class="container-fluid px-0">
      <div class="mx-auto tm-content-container mt-4 px-3">
        <div class="row tm-catalog-item-list mb-4">
          <div class="col-lg-11 col-md-6 col-sm-12 tm-catalog-item">
            <div class="tm-bg-gray">
              <div class="text-center">
                <div id="background-2">
                  <h3>
                    <?php
                    echo $heading,"<br>";
                    echo "性格型：",$personality,"型";
                    ?>
                  </h3>
                </div>
                <?php
                echo $name, "<br>";
                ?>

                <img src="<?php echo $img_src; ?>" class="image">

                <br>
                <?php
                echo $living, "年<br>";
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <div class="container-fluid px-0">
    <div class="mx-auto tm-content-container">
      <p>地名を入力するか、地図上をクリックするとマーカーを移動できます。</p>
      <form onsubmit="return false;">
        <input type="text" value="福井偉人のゆかりの地" id="address">
        <button type="button" value="検索" id="map_button">検索</button>
      </form>
      <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD6HyUtVl2SvfWpmawfdO4XEii297iAFUQ&libraries=places">
      </script>

      <div class="row mt-3 mb-5 pb-3">
        <div class="col-12">
          <div class="mx-auto tm-about-text-container px-3">
            <div id="gmap_canvas"></div>
            <!--マップ表示？-->
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    var lat;
    var lng;

    var getMap = (function() {
      function codeAddress(address) {
        // google.maps.Geocoder()コンストラクタのインスタンスを生成
        var geocoder = new google.maps.Geocoder();

        // 地図表示に関するオプション
        var opt = {
          zoom: 16,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        // 地図を表示させるインスタンスを生成
        var map = new google.maps.Map(document.getElementById("gmap_canvas"), opt);

        //マーカー変数用意
        var marker;

        // geocoder.geocode()メソッドを実行 
        geocoder.geocode({
          'address': address
        }, function(results, status) {

          // ジオコーディングが成功した場合
          if (status == google.maps.GeocoderStatus.OK) {

            // 変換した緯度・経度情報を地図の中心に表示
            map.setCenter(results[0].geometry.location);

            //☆表示している地図上の緯度経度
            lat = results[0].geometry.location.lat();
            lng = results[0].geometry.location.lng();
            // マーカー設定
            marker = new google.maps.Marker({
              map: map,
              position: results[0].geometry.location
            });

            // ジオコーディングが成功しなかった場合
          } else {
            console.log('Geocode was not successful for the following reason: ' + status);
          }

        });

        // マップをクリックで位置変更
        map.addListener('click', function(e) {
          getClickLatLng(e.latLng, map);
        });

        function getClickLatLng(lat_lng, map) {

          //☆表示している地図上の緯度経度
          lat = lat_lng.lat();
          lng = lat_lng.lng();

          // マーカーを設置
          marker.setMap(null);
          marker = new google.maps.Marker({
            position: lat_lng,
            map: map
          });

          // 座標の中心をずらす
          map.panTo(lat_lng);
        }

      }

      //inputのvalueで検索して地図を表示
      return {
        getAddress: function() {
          // ボタンに指定したid要素を取得
          var button = document.getElementById("map_button");

          // ボタンが押された時の処理
          button.onclick = function() {
            // フォームに入力された住所情報を取得
            var address = document.getElementById("address").value;
            // 取得した住所を引数に指定してcodeAddress()関数を実行
            codeAddress(address);
          }

          //読み込まれたときに地図を表示
          window.onload = function() {
            // フォームに入力された住所情報を取得
            var address = document.getElementById("address").value;
            // 取得した住所を引数に指定してcodeAddress()関数を実行
            codeAddress(address);
          }
        }

      };

    })();
    getMap.getAddress();
  </script>

  <!--データ送信フォーム-->
  <div class="container-fluid px-0">
    <div class="mx-auto tm-content-container">
      <form action="db_add_epi.php" method="POST" name="jsform" onsubmit="check2(this);return false;" enctype="multipart/form-data">
        その地でのエピソードを入力してください。<br>
        見出し：<input type="text" name="heading" placeholder="例）北の京と呼ばれた場所"><br>
        エピソード：<textarea name="episode" cols="30" rows="10" placeholder="例）朝倉義景は、越前の中心地として一乗谷に本拠地を構えていました。近くには一乗滝があり、観光スポットにもなっています"></textarea><br>
        <input type="file" name="upfile" accept=".png, .jpg, .jpeg">
        <input type="hidden" name="h_id" value="<?php echo $h_id; ?>">
        <input type="hidden" name="lat" id="lat" value="">
        <input type="hidden" name="lng" id="lng" value="">
        <div class="form-group">
          <input type="submit" class="btn btn-primary rounded-0 d-block ml-auto tm-btn-animate tm-btn-submit tm-icon-submit" value="この情報で追加">
        </div>
      </form>
    </div>
  </div>
  <script>
    //フォーム未入力チェック
    function check2(question){
        var errors = [];
        var heading = question.heading.value;
        var episode = question.episode.value;

        //タイトルが未入力
        if (heading == '') { 
            errors.push("タイトル");
        }

        //エピソードが未入力
        if (episode == '') { 
            errors.push("エピソード");
        }
        //エラーが1つでもあれば
        if (errors.length) { 
            alert(errors+"が未回答です。");
            return false;

        //エラーがなければsubmit
        } else {
            submitData();           
        }
    }

    //ボタン押されたときに座標を飛ばす処理
    function submitData() {
      document.getElementById('lat').value = lat;
      document.getElementById('lng').value = lng;
      document.jsform.submit();
    }
  </script>
  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/parallax.min.js"></script>
</body>

</html>