<?php
require_once('funcs.php');
no_cache();
session_start();
console_log("------------------------ Entered to setting.php", "------------------------");
session_var_dump();

loginCheck();

console_log("------------------------ login OK,", "session_regenerate_id(true)");
session_var_dump();

// db に接続
$pdo = db_conn();

// member が POST されていたら db に登録
if (isset($_POST['name'])) {
  $name = sr($_POST['name']);
  $stmt = $pdo->prepare('INSERT INTO household_member(name, in_date) VALUES(:name, sysdate())');
  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $status = $stmt->execute(); 
  if ($status === false) {
    sql_error($stmt);
  } else {
    redirect('setting.php'); // POST データを消すために再読み込み
  }
}

// room が POST されていたら db に登録
if (isset($_POST['room'])) {
  $room = sr($_POST['room']);
  $stmt = $pdo->prepare('INSERT INTO household_room(room, in_date) VALUES(:room, sysdate())');
  $stmt->bindValue(':room', $room, PDO::PARAM_STR);
  $status = $stmt->execute(); 
  if ($status === false) {
    sql_error($stmt);
  } else {
    redirect('setting.php'); // POST データを消すために再読み込み
  }
}

// category が POST されていたら db に登録
if (isset($_POST['category'])) {
  $category = sr($_POST['category']);
  $stmt = $pdo->prepare('INSERT INTO household_category(category, in_date) VALUES(:category, sysdate())');
  $stmt->bindValue(':category', $category, PDO::PARAM_STR);
  $status = $stmt->execute(); 
  if ($status === false) {
    sql_error($stmt);
  } else {
    redirect('setting.php'); // POST データを消すために再読み込み
  }
}

// member 一覧を表示
$stmt = $pdo->prepare('SELECT * FROM household_member');
$status = $stmt->execute();
$member_view = '';
if ($status === false) {
    sql_error($stmt);
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $member_view .= '<p>';
        $member_view .= $result['name'];

        $member_view .= '<a href="edit_setting.php?member_id=' . $result['member_id'] . '" class="edit_link">';
        $member_view .= '編集';
        $member_view .= '</a>';
    }
}

// room 一覧を表示
$stmt = $pdo->prepare('SELECT * FROM household_room ORDER BY room ASC');
$status = $stmt->execute();
$room_view = '';
if ($status === false) {
    sql_error($stmt);
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $room_view .= '<p>';
        $room_view .= $result['room'];

        $room_view .= '<a href="edit_setting.php?room_id=' . $result['room_id'] . '" class="edit_link">';
        $room_view .= '編集';
        $room_view .= '</a>';
    }
}

// category 一覧を表示
$stmt = $pdo->prepare('SELECT * FROM household_category ORDER BY category ASC');
$status = $stmt->execute();
$category_view = '';
if ($status === false) {
    sql_error($stmt);
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $category_view .= '<p>';
        $category_view .= $result['category'];

        $category_view .= '<a href="edit_setting.php?category_id=' . $result['category_id'] . '" class="edit_link">';
        $category_view .= '編集';
        $category_view .= '</a>';
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
        <img class="icon" src="image/プラスのアイコン.png" alt="アイテム追加" title="アイテムを追加する">
      </a>
      <a href="setting.php">
        <img class="icon" src="image/設定アイコン.png" alt="設定" title="メンバーや部屋を登録する">
      </a>
      <a href="https://github.com/yoshi-fujita/dev23_php03_kadai/blob/main/README.md" target="_blank">
        <img class="icon" src="image/はてなのアイコン.png" alt="ヘルプ" title="ヘルプ">
      </a>
    </div>

    <div class="container">
      <div class="new_member">
        <form method="post">
          <input type="text" name="name" required>
          <input type="submit" value="家族を追加する">
        </form>
      </div>
      <div class="new_room">
        <form method="post">
         <input type="text" name="room" required>
         <input type="submit" value="部屋を追加する">
        </form>
      </div>
      <div class="new_category">
        <form method="post">
         <input type="text" name="category" required>
         <input type="submit" value="分類を追加する">
        </form>
      </div>

      <h3>家族</h3>
      <?= $member_view ?>

      <h3>部屋</h3>  
      <?= $room_view ?>

      <h3>分類</h3>  
      <?= $category_view ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>
