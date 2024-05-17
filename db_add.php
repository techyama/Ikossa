<?php
session_start();
?>
<?php

//追加する偉人の性格型設定
if($_POST['ei'] <= 2) {
    $ei = "E";
} else {
    $ei = "I";
}

if($_POST['sn'] <= 2) {
    $sn = "S";
} else {
    $sn = "N";
}

if($_POST['tf'] <= 2) {
    $tf = "T";
} else {
    $tf = "F";
}

if($_POST['jp'] <= 2) {
    $jp = "J";
} else {
    $jp = "P";
}

$personality =  $ei.$sn.$tf.$jp;

//未入力なら????で初期設定
if (empty($_POST['living1'])  || empty($_POST['living2'])) {
    $living = "????～????";
} else {
    $living = $_POST['living1']."～".$_POST['living2'];
}
$message = "メッセージ準備中";

//顔写真のアップロード処理
if(!empty($_FILES['photo'])) {
    $his_name = $_POST['name'];
    $photo = "historical";  //フォルダ内のプロフィール用写真
    $ext = ".png";     //拡張子
    $save_dir = "img/".$his_name."/".$photo.$ext;    //アップロード先

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
    move_uploaded_file($_FILES['photo']['tmp_name'], $save_dir);
    $img = $save_dir;
} else {
    $img = "img/noimage1.png";
}


//追加する偉人情報をDBに挿入
require 'db_connect.php';
$sql="INSERT INTO historical (id, name, category, heading, living, intro, img_src) VALUES (NULL, :name, :category, :heading, :living, :message, :img)";
$stm = $pdo->prepare($sql);
$stm->bindParam(':name', $_POST['name']);
$stm->bindParam(':category', $personality);
$stm->bindParam(':heading', $_POST['heading']);
$stm->bindParam(':living', $living);
$stm->bindParam(':message', $message);
$stm->bindParam(':img', $img);
$stm->execute();
echo "追加が完了しました。次にエピソードを入力してください。";

//追加した偉人idを取得
$sql="SELECT id FROM historical ORDER BY id DESC LIMIT 1 ";
$stm = $pdo->prepare($sql);
$stm->execute();
$result = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $h_id = $row['id'];
    }

require 'add_epi.php';
?>