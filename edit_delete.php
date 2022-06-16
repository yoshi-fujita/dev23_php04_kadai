<?php
require_once('funcs.php');
no_cache();
session_start();
console_log("------------------------ Entered to edit_delete.php", "------------------------");
session_var_dump();

loginCheck();

console_log("------------------------ login OK,", "session_regenerate_id(true)");
session_var_dump();

// db に接続
$pdo = db_conn();

if (isset($_POST['item_id'])) { // item_id が POST されていたら db に登録
  $item_id = $_POST['item_id'];
  $stmt = $pdo->prepare('DELETE FROM household_list WHERE item_id = :item_id');
  $stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
  $status = $stmt->execute(); 
  if ($status === false) {
    sql_error($stmt);
  } else {
    move_page('index.php', 0);
  }
}

?>