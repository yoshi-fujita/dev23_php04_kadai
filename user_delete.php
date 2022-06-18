<?php
require_once('funcs.php');
no_cache();
session_start();
console_log("------------------------ Entered to user_delete.php", "------------------------");
session_var_dump();

loginCheck();

console_log("------------------------ login OK,", "session_regenerate_id(true)");
session_var_dump();

if (!$_SESSION["kanri_flg"]) {
  move_page('index.php', 0); // 管理者でなかったら、index.pph に遷移
}

// db に接続
$pdo = db_conn();

if (isset($_GET['user_id'])) { // user_id が GET されていたら db から削除
  $user_id = $_GET['user_id'];
  $stmt = $pdo->prepare('DELETE FROM household_user WHERE id = :user_id');
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
  $status = $stmt->execute(); 
  if ($status === false) {
    sql_error($stmt);
  } else {
    move_page('user.php', 0);
  }
}

?>