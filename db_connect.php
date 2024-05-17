<?php
try {
    $user = 'fukuiohr2';
    $password = 'Fukui2021d';
    $dbName = 'fukuiohr2_historical';
    $host = 'mysql640.db.sakura.ne.jp';
    $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";

    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo '<span class="error">エラーがありました。</span><br>';
    echo $e->getMessage();
    exit();
}
?>