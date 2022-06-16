<?php
require_once('funcs.php');
no_cache();
session_start();
console_log("------------------------ Entered to index.php", "------------------------");
session_var_dump();

loginCheck();

console_log("------------------------ login OK,", "session_regenerate_id(true)");
session_var_dump();

if ($_SESSION["kanri_flg"]) {
  $user_view = '<a href="user.php"><img class="icon" src="image/ユーザーのアイコン.png" alt="設定" title="ユーザー管理"></a>';
} else {
  $user_view = "";
}

if (!isset($_SESSION['disp_mode'])) {
  $_SESSION['disp_mode'] = "all";
} // ページを更新しても disp_mode を覚えて置けるようにセッション変数にする


// db に接続
$pdo = db_conn();

// アイテムが POST されていたら db に登録
if (isset($_POST['photo'])) {
  $photo = $_POST['photo'];
  $name = $_POST['title'];
  $category = $_POST['category'];
  $room = $_POST['room'];
  $description = $_POST['description'];

  $stmt = $pdo->prepare('INSERT INTO household_list
      (photo, name, category, room, description, state, in_date)
    VALUES
      (:photo, :name, :category, :room, :description, "募集中", sysdate())');
  $stmt->bindValue(':photo', $photo, PDO::PARAM_STR);
  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $stmt->bindValue(':category', $category, PDO::PARAM_STR);
  $stmt->bindValue(':room', $room, PDO::PARAM_STR);
  $stmt->bindValue(':description', $description, PDO::PARAM_STR);
  $status = $stmt->execute(); 

  if ($status === false) {
    sql_error($stmt);
  } else {
    redirect('index.php'); // POST データを消すために再読み込み
  }
} else if (isset($_POST['select'])) { // ラジオボタンが押されたら反応
    $_SESSION['disp_mode'] = $_POST['select'];
    redirect('index.php'); // POST データを消すために再読み込み
} // all：全て表示する　open：募集中のみ表示　close：行き先決定済みのみ表示

// disp_mode の選択肢を表示、および mySQL の $stmt をセット
$select_view = "";
if ( $_SESSION['disp_mode'] === "all" ) {
  $select_view = '<div><input type="radio" name="select" value="all" id="all" checked><label for="all">すべて</label></div>';
  $select_view .= '<div><input type="radio" name="select" value="open" id="open"><label for="open">募集中</label></div>';
  $select_view .= '<div><input type="radio" name="select" value="close" id="close"><label for="close">行き先決定済み</label></div>';
  $stmt = $pdo->prepare('SELECT * FROM household_list ORDER BY item_id DESC');
} else if ( $_SESSION['disp_mode'] === "open" ) {
  $select_view = '<div><input type="radio" name="select" value="all" id="all"><label for="all">すべて</label></div>';
  $select_view .= '<div><input type="radio" name="select" value="open" id="open" checked><label for="open">募集中</label></div>';
  $select_view .= '<div><input type="radio" name="select" value="close" id="close"><label for="close">行き先決定済み</label></div>';
  $stmt = $pdo->prepare('SELECT * FROM household_list WHERE state = "募集中" ORDER BY item_id DESC');
} else if ( $_SESSION['disp_mode'] === "close" ) {
  $select_view = '<div><input type="radio" name="select" value="all" id="all"><label for="all">すべて</label></div>';
  $select_view .= '<div><input type="radio" name="select" value="open" id="open"><label for="open">募集中</label></div>';
  $select_view .= '<div><input type="radio" name="select" value="close" id="close" checked><label for="close">行き先決定済み</label></div>';
  $stmt = $pdo->prepare('SELECT * FROM household_list WHERE state = "行き先決定済み" ORDER BY item_id DESC');
}

// アイテム一覧を取得して表示
$status = $stmt->execute();
$view = '';
if ($status === false) {
    sql_error($stmt);
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $view .= '<div class="item_set">';
        $view .= '<a href="' . $result['photo'] . '" data-lightbox="group"><img src="' . $result['photo'] . '"></a>';
        $view .= '<div class="item_description">';
        $view .= '<div class="item_titlearea">';
        $view .= '<span class="item_id">' . $result['item_id'] . '</span>';    
        $view .= '<span class="item_state">' . $result['state'];
        $view .= '<a href="edit.php?item_id=' . $result['item_id']  . '">編集</a>';
        $view .= '</span>';    
        $view .= '</div>';
        $view .= '<table>';
        if ($result['name']) {
            $view .= '<tr><td class="td_a">名称</td><td>' . $result['name'] . '</td></tr>';
        }
        if ($result['category']) {
            $view .= '<tr><td class="td_a">分類</td><td>' . $result['category'] . '</td></tr>';
        }
        if ($result['room']) {
            $view .= '<tr><td class="td_a">場所</td><td>' . $result['room'] . '</td></tr>';
        }
        if ($result['description']) {
            $view .= '<tr><td class="td_a">説明</td><td>' . nl2br($result['description']) . '</td></tr>';
        }
        if ($result['request']) {
          $view .= '<tr><td class="td_a">希望</td><td>' . nl2br($result['request']) . '</td></tr>';
        }
        if ($result['dest_person']) {
          $view .= '<tr><td class="td_a">行先</td><td>' . $result['dest_person'] . '</td></tr>';
        }
        if ($result['reason']) {
          $view .= '<tr><td class="td_a">理由</td><td>' . nl2br($result['reason']) . '</td></tr>';
        }
        if ($result['storage_period']) {
          $view .= '<tr><td class="td_a">期限</td><td>' . $result['storage_period'] . '</td></tr>';
        }
        $view .= '</table>';
        $view .= '</div>';
        $view .= '</div>';
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
    <!-- この下の 3行は、Lightbox を入れるために挿入。参考： https://toretama.jp/click-big-image-floaty.html -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>
  </head>
  <body>
    <div class="title_area">
      <a href="index.php"><img class="logo" src="image/table_chabudai.png" alt="ちゃぶ台アイコン" title="アイテム一覧"></a>
      <h2>かざい博物館</h2>
      <a href="capture.php">
        <img class="icon" src="image/プラスのアイコン.png" alt="アイテム追加" title="アイテムを追加する">
      </a>
      <a href="setting.php">
        <img class="icon" src="image/設定アイコン.png" alt="設定" title="メンバーや部屋を登録する">
      </a>
      <?= $user_view ?>
      <a href="logout.php">
        <img class="icon" src="image/ログアウトのアイコン.png" alt="設定" title="ログアウトする">
      </a>
      <a href="https://github.com/yoshi-fujita/dev23_php03_kadai/blob/main/README.md" target="_blank">
        <img class="icon" src="image/はてなのアイコン.png" alt="ヘルプ" title="ヘルプ">
      </a>
    </div>
    <div class="container">

      <div class="title_and_selections">
        <h3>アイテム一覧</h3>
        <form method="post" class="selections">
        <?= $select_view ?>
        </form>
      </div>

      <div class="item_lists">
        <?= $view ?>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script> // ラジオボタンをクリックするたびに表示が切り替える
      $(function() {
        $("input").each(function() {
          $(this).on("change", function() {
            this.form.submit();
          });
        });
      });
    </script>
  </body>

</html>
