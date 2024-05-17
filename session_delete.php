<?php
session_start();
//セッション変数をクリア
$_SESSION = array();
//クッキーに登録されているセッションidの情報を削除
if (ini_get("session.use_cookies")) {
  setcookie(session_name(), '', time() - 42000, '/');
}
//セッションを破棄
session_destroy();
?>