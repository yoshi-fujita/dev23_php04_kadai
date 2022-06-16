<?php
require_once('funcs.php');
no_cache();
session_start();
console_log("------------------------ Entered to update.php", "------------------------");
session_var_dump();

loginCheck();

console_log("------------------------ login OK,", "session_regenerate_id(true)");
session_var_dump();

// db に接続
$pdo = db_conn();

// アイテムが POST されたら db に登録
if (isset($_POST['item_id'])) {
  $item_id = $_POST['item_id'];
  $photo = $_POST['photo'];
  $name = $_POST['title'];
  $category = $_POST['category'];
  $room = $_POST['room'];
  $description = $_POST['description'];
  $state = $_POST['state'];
  $request = $_POST['request'];
  $dest_person = $_POST['dest_person'];
  $reason = $_POST['reason'];
  $storage_period = $_POST['storage_period'];
  if(!$storage_period) { // 空欄のまま再保存しようとしたらエラーが出た。input の value を経由するとNULL以外の表現になっているのか？
    $storage_period = NULL;
  }

  echo "<pre>";
  var_dump($item_id);
  var_dump($name);
  var_dump($category);
  var_dump($room);
  var_dump($description);
  var_dump($state);
  var_dump($request);
  var_dump($dest_person);
  var_dump($reason);
  var_dump($storage_period);
  echo "</pre>";

  $stmt = $pdo->prepare('UPDATE household_list
    SET
      photo = :photo, name = :name, category = :category, room = :room, description = :description, 
      state = :state, request = :request, dest_person = :dest_person, reason = :reason, 
      storage_period = :storage_period, in_date  = sysdate()
    WHERE
      item_id = :item_id');

  $stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
  $stmt->bindValue(':photo', $photo, PDO::PARAM_STR);
  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $stmt->bindValue(':category', $category, PDO::PARAM_STR);
  $stmt->bindValue(':room', $room, PDO::PARAM_STR);
  $stmt->bindValue(':description', $description, PDO::PARAM_STR);
  $stmt->bindValue(':state', $state, PDO::PARAM_STR);
  $stmt->bindValue(':request', $request, PDO::PARAM_STR);
  $stmt->bindValue(':dest_person', $dest_person, PDO::PARAM_STR);
  $stmt->bindValue(':reason', $reason, PDO::PARAM_STR);
  $stmt->bindValue(':storage_period', $storage_period, PDO::PARAM_STR);

  $status = $stmt->execute(); 

  if ($status === false) {
    sql_error($stmt);
  } else {
    move_page('index.php', 0); // 書き込み完了したら一覧ページに移動
  }
}

?>