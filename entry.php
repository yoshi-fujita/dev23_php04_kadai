<?php
require_once('funcs.php');
no_cache();
session_start();
console_log("------------------------ Entered to entry.php", "------------------------");
session_var_dump();

loginCheck();

console_log("------------------------ login OK,", "session_regenerate_id(true)");
session_var_dump();

if(isset($_POST['photo'])) { // キャプチャして POST された画像の存在確認
    $photo = $_POST['photo'];
} else if(isset($_POST['up_photo'])) { // アップロードして POST された画像の存在確認
    $photo = $_POST['up_photo'];
} else {
    move_page('capture.php', 0);

}

// db接続
$pdo = db_conn();

// db からカテゴリを取得し、カテゴリの選択肢を作成
$stmt = $pdo->prepare('SELECT * FROM household_category ORDER BY category ASC');
$status = $stmt->execute();

$category_selection = '';
if ($status === false) {
    sql_error($stmt);
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $category_selection .= '<option value=' . $result['category'] . '>' . $result['category'] . '</option>';
    }
}

// db から場所を取得し、場所の選択肢を作成
$stmt = $pdo->prepare('SELECT * FROM household_room ORDER BY room ASC');
$status = $stmt->execute();

$room_selection = '';
if ($status === false) {
    sql_error($stmt);
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $room_selection .= '<option value=' . $result['room'] . '>' . $result['room'] . '</option>';
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
        <form action="index.php" method="post">
            <div class="form_entry">
                <p>写真</p>
                <img src="<?= $photo ?>" width="232" alt="登録するアイテムの画像">
                <input type="hidden" name="photo" value="<?= $photo ?>">
            </div>
            <div class="form_entry">
                <p>名称</p>
                <input type="text" name="title">
            </div>
            <div class="form_entry">
                <p>分類</p>
                <select name="category">
                    <option value=""></option>
                    <?= $category_selection ?>
                </select>
            </div>
            <div class="form_entry">
                <p>場所</p>
                <select name="room">
                    <option value=""></option>
                    <?= $room_selection ?>
                </select>
            </div>
            <div class="form_entry">
                <p>説明</p>
                <textarea name="description" id="description" cols="30" rows="4"></textarea>
            </div>
            <input type="submit" value="登録する">
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>
