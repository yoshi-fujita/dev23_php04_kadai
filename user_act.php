<?php
require_once('funcs.php');
no_cache();
session_start();
console_log("------------------------ Entered to user_act.php", "------------------------");
session_var_dump();

loginCheck();

console_log("------------------------ login OK,", "session_regenerate_id(true)");
session_var_dump();

if (!$_SESSION["kanri_flg"]) {
  move_page('index.php', 0); // 管理者でなかったら、index.pph に遷移
}

// db に接続
$pdo = db_conn();

// アイテムが POST されたら db に登録
if (isset($_POST['name'])) {
  $name = $_POST['name'];
  $lid = $_POST['lid'];
  $lpw = $_POST['lpw'];
  if (isset($_POST['kanri'])) {
    $kanri = 1;
  } else {
    $kanri = 0;
  }

  $stmt = $pdo->prepare('INSERT INTO household_user(name, lid, lpw, kanri_flg, life_flg, in_date) 
    VALUES(:name, :lid, :lpw, :kanri, 0, sysdate())');
  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
  $stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR);
  $stmt->bindValue(':kanri', $kanri, PDO::PARAM_STR);
  $status = $stmt->execute(); 

  if ($status === false) {
    sql_error($stmt);
  } else {
    move_page('user.php', 0); // 書き込み完了したら一覧ページに移動
  }
}

?>