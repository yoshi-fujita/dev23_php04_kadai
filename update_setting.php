<?php
require_once('funcs.php');
no_cache();
session_start();
console_log("------------------------ Entered to update_setting.php", "------------------------");
session_var_dump();

loginCheck();

console_log("------------------------ login OK,", "session_regenerate_id(true)");
session_var_dump();

// db に接続
$pdo = db_conn();

if (isset($_POST['member_id'])) { // name が POST されたら db に登録
  $member_id = $_POST['member_id'];
  $name = sr($_POST['name']);
  $stmt = $pdo->prepare('UPDATE household_member SET name = :name, in_date  = sysdate() WHERE member_id = :member_id');
  $stmt->bindValue(':member_id', $member_id, PDO::PARAM_STR);
  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $status = $stmt->execute(); 
  if ($status === false) {
    sql_error($stmt);
  } else {
    move_page('setting.php', 0); // 書き込み完了したら一覧ページに移動
  }
} else if (isset($_POST['room_id'])) { // room が POST されたら db に登録
  $room_id = $_POST['room_id'];
  $room = sr($_POST['room']);
  $stmt = $pdo->prepare('UPDATE household_room SET room = :room, in_date  = sysdate() WHERE room_id = :room_id');
  $stmt->bindValue(':room_id', $room_id, PDO::PARAM_STR);
  $stmt->bindValue(':room', $room, PDO::PARAM_STR);
  $status = $stmt->execute(); 
  if ($status === false) {
    sql_error($stmt);
  } else {
    move_page('setting.php', 0); // 書き込み完了したら一覧ページに移動
  }
} else if (isset($_POST['category_id'])) { // category が POST されたら db に登録
  $category_id = $_POST['category_id'];
  $category = sr($_POST['category']);
  $stmt = $pdo->prepare('UPDATE household_category SET category = :category, in_date  = sysdate() WHERE category_id = :category_id');
  $stmt->bindValue(':category_id', $category_id, PDO::PARAM_STR);
  $stmt->bindValue(':category', $category, PDO::PARAM_STR);
  $status = $stmt->execute(); 
  if ($status === false) {
    sql_error($stmt);
  } else {
    move_page('setting.php', 0); // 書き込み完了したら一覧ページに移動
  }
}

?>