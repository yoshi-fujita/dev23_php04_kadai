<?php
require_once('funcs.php');
no_cache();
session_start();
console_log("------------------------ Entered to user.php", "------------------------");
session_var_dump();

loginCheck();

console_log("------------------------ login OK,", "session_regenerate_id(true)");
session_var_dump();

// db に接続
$pdo = db_conn();

// user 一覧を表示
$stmt = $pdo->prepare('SELECT * FROM household_user');
$status = $stmt->execute();
$view = '';
if ($status === false) {
    sql_error($stmt);
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $view .= '<p>id: ';
        $view .= $result['id'] . ', name: ' . $result['name'];
        $view .= ', kanri: ' . $result['kanri_flg'] . ', life: ' . $result['life_flg'];
        $view .= '<a href="delete_user.php?id=' . $result['id'] . '" class="edit_link">';
        $view .= '削除';
        $view .= '</a>';
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
      <h3>ユーザ追加</h3>
        <div>
        <form name="form1" action="user__act.php" method="post" class="add_member">
          <p>name <input type="text" name="name" size="15" required/></p>
          <p>ID <input type="text" name="lid" size="15" required/></p>
          <p>PW <input type="password" name="lpw" size="15" required/></p>
          <p>管理者 <input type="checkbox" name="kanri" value="kanrir"></p>
          <p><input type="submit" value="ユーザを追加する" /></p>
        </form>
        </div>

      <h3>ユーザ一覧</h3>
      <?= $view ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>
