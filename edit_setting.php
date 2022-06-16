<?php
require_once('funcs.php');
no_cache();
session_start();
console_log("------------------------ Entered to edit_setting.php", "------------------------");
session_var_dump();

loginCheck();

console_log("------------------------ login OK,", "session_regenerate_id(true)");
session_var_dump();

// db に接続
$pdo = db_conn();

// member_id が GET されたら db を編集
if (isset($_GET['member_id'])) { // member の変更の場合
  $member_id = $_GET['member_id'];

  $stmt = $pdo->prepare('SELECT * FROM household_member WHERE member_id = :member_id');
  $stmt->bindValue(':member_id', $member_id, PDO::PARAM_STR);
  $status = $stmt->execute();
  if ($status === false) {
    sql_error($stmt);
  } else {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $name = $result['name'];
    $view = '<input type="text" name="name" value="' . $name . '">';
    $view .= '<input type="hidden" name="member_id" value="' . $member_id . '" required>';
  }
} else if(isset($_GET['room_id'])) { // room の変更の場合
  $room_id = $_GET['room_id'];

  $stmt = $pdo->prepare('SELECT * FROM household_room WHERE room_id = :room_id');
  $stmt->bindValue(':room_id', $room_id, PDO::PARAM_STR);
  $status = $stmt->execute();
  if ($status === false) {
    sql_error($stmt);
  } else {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $room = $result['room'];
    $view = '<input type="text" name="room" value="' . $room . '" required>';
    $view .= '<input type="hidden" name="room_id" value="' . $room_id . '">';
  }
} else if(isset($_GET['category_id'])) { // category の変更の場合
  $category_id = $_GET['category_id'];
  $stmt = $pdo->prepare('SELECT * FROM household_category WHERE category_id = :category_id');
  $stmt->bindValue(':category_id', $category_id, PDO::PARAM_STR);
  $status = $stmt->execute();
  if ($status === false) {
    sql_error($stmt);
  } else {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $category = $result['category'];
    $view = '<input type="text" name="category" value="' . $category . '" required>';
    $view .= '<input type="hidden" name="category_id" value="' . $category_id . '">';
  }
}

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="G'sアカデミー課題 PHP" />
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="icon" type="image/png" href="image/table_chabudai.png" />
    <title>かざい博物館</title>
  </head>
  <body>
    <div class="title_area">
      <a href="index.php"><img class="logo" src="image/table_chabudai.png" alt="ちゃぶ台アイコン" title="アイテム一覧"/></a>
      <h2>かざい博物館</h2>
      <a href="capture.php">
        <img class="icon" src="image/プラスのアイコン.png" alt="アイテム追加" title="アイテムを追加する"/>
      </a>
      <a href="setting.php">
        <img class="icon" src="image/設定アイコン.png" alt="設定" title="メンバーや部屋を登録する"/>
      </a>
      <a href="https://github.com/yoshi-fujita/dev23_php03_kadai/blob/main/README.md" target="_blank">
        <img class="icon" src="image/はてなのアイコン.png" alt="ヘルプ" title="ヘルプ"/>
      </a>
    </div>

    <div class="container">
        <form action="update_setting.php" method="post">
            <?= $view ?>
            <input type="submit" value="更新する">
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>


