<?php
require_once('funcs.php');
no_cache();
session_start();
console_log("------------------------ Entered to capture.php", "------------------------");
session_var_dump();

loginCheck();

console_log("------------------------ login OK,", "session_regenerate_id(true)");
session_var_dump();

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
      <div class="image_capture">
          <video id="video" width="1024" height="768" playsinline muted autoplay style="transform: scaleX(-1)"></video>
          <button id="capture">撮影（再撮影）</button>
      </div>

      <form method="post" class="image_post" action="entry.php" id="captured_photo">
        <canvas id="canvas" width="1024" height="768"></canvas>
        <input type="submit" value="撮影した写真を使う">
      </form>

      <form method="post" action="entry.php" enctype="multipart/form-data" id="photo_select">
        <input type="file" name="file" id="upload" accept="image/*" required>
        <div id="preview"></div>
        <button type="submit" id="photo_submit">アップロードした写真を使う</button>
      </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/capture.js"></script>
    <script src="js/photo.js"></script>
</body>

</html>
