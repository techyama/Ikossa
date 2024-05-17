<?php
//4タイプのパラメータ初期値
$ei = 0;
$sn = 0;
$tf = 0;
$jp = 0;
//質問数取得
$qnum = $_GET['qnum'];
//質問数ループを回す
for ($i = 1; $i <= $qnum; $i++){
    //質問のタイプ判定
    if ($_GET['q'.$i.'type'] == "ei"){
        $ei += $_GET['q'.$i];
    } else if ($_GET['q'.$i.'type'] == "sn"){
        $sn += $_GET['q'.$i];
    } else if ($_GET['q'.$i.'type'] == "tf"){
        $tf += $_GET['q'.$i];
    } else if ($_GET['q'.$i.'type'] == "jp"){
        $jp += $_GET['q'.$i];
    }
}

//4タイプのパラメータごとにどちらに傾いているか判定
if ($ei >= 1) {
    $personality1 = "E";
} else {
    $personality1 = "I";
}

if ($sn >= 1) {
    $personality2 = "S";
} else {
    $personality2 = "N";
}

if ($tf >= 1) {
    $personality3 = "T";
} else {
    $personality3 = "F";
}

if ($jp >= 1) {
    $personality4 = "J";
} else {
    $personality4 = "P";
}

$personality =  $personality1.$personality2.$personality3.$personality4;

//ここまで性格を判別する処理



require 'db_connect.php';
$sql = "SELECT * FROM historical WHERE  category = :category";  //判定した性格型のランダムな偉人一人の情報を格納
$stm = $pdo->prepare($sql);
$stm->bindParam(':category', $personality);
$stm->execute();
$result2 = $stm->fetchAll(PDO::FETCH_ASSOC);

//同じ性格型の中からランダムで偉人を選ぶ
$num = rand(0, (count($result2) - 1));

if (!empty($result2)) {
    $i = 0;
    foreach ($result2 as $row) {
        //ランダムで選ばれた偉人情報を変数に格納
        if($i == $num) {
            $human_id = $row['id'];
            $name = $row['name'];
            $heading = $row['heading'];
            $living= $row['living'];
            $intro = $row['intro'];
            $img_src = $row['img_src'];
        }
        $i++;
    }
} else {
    $flg = 1;   //  同じ性格型がいなければ
}

$sql = "SELECT * FROM personality WHERE category = (:category)";  //判定した性格型情報を格納
$stm = $pdo->prepare($sql);
$stm->bindParam(':category', $personality);
$stm->execute();
$result = $stm->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $id = $row['id'];
    $category = $row['category'];
    $personality = $row['personality'];
    $chara = $row['chara'];
    $advice = $row['advice'];
}

            
require 'result.php';
