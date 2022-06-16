<?php
require_once('funcs.php');
no_cache();
session_start();
console_log("------------------------ Entered to edit.php", "------------------------");
session_var_dump();

loginCheck();

console_log("------------------------ login OK,", "session_regenerate_id(true)");
session_var_dump();

if(isset($_GET['item_id'])) { // GET された item_id の存在確認
    $item_id = $_GET['item_id'];
} else {
    redirect('index.php'); // item_id がなければ index に戻る
}

// db接続
$pdo = db_conn();

// db から編集するアイテムを取得して変数に保存
$stmt = $pdo->prepare('SELECT * FROM household_list WHERE item_id = :item_id');
$stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
$status = $stmt->execute();

if ($status === false) {
    sql_error($stmt);
} else {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $photo = $result['photo'];
    $name = $result['name'];
    $category = $result['category'];
    $room = $result['room'];
    $description = $result['description'];
    $state = $result['state'];
    $request = $result['request'];
    $dest_person = $result['dest_person'];
    $reason = $result['reason'];
    $storage_period = $result['storage_period'];
    // if ($storage_period === NULL) {
    //     $storage_period = "2022-12-31";
    // }
    // var_dump($storage_period);
}

// db からメンバーを取得し、メンバーの選択肢を作成
$stmt = $pdo->prepare('SELECT * FROM household_member');
$status = $stmt->execute();

$member_selection = '';
if ($status === false) {
    sql_error($stmt);
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ( $result['name'] === $dest_person ) {
            $member_selection .= '<option value=' . $result['name'] . ' selected>' . $result['name'] . '</option>';
        } else {
            $member_selection .= '<option value=' . $result['name'] . '>' . $result['name'] . '</option>';
        }
    }
}

// db からカテゴリを取得し、カテゴリの選択肢を作成
$stmt = $pdo->prepare('SELECT * FROM household_category ORDER BY category ASC');
$status = $stmt->execute();

$category_selection = '';
if ($status === false) {
    sql_error($stmt);
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ( $result['category'] === $category) {
            $category_selection .= '<option value=' . $result['category'] . ' selected>' . $result['category'] . '</option>';
        } else {
            $category_selection .= '<option value=' . $result['category'] . '>' . $result['category'] . '</option>';
        }
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
        if ( $result['room'] === $room) {
            $room_selection .= '<option value=' . $result['room'] . ' selected>' . $result['room'] . '</option>';
        } else {
            $room_selection .= '<option value=' . $result['room'] . '>' . $result['room'] . '</option>';
        }
    }
}

// state の選択肢を作成
$state_selection = '';
if ( $state === "募集中" ) {
    $state_selection .= '<option value="募集中" selected>募集中</option>';
    $state_selection .= '<option value="行き先決定済み">行き先決定済み</option>';
} else {
    $state_selection .= '<option value="募集中">募集中</option>';
    $state_selection .= '<option value="行き先決定済み" selected>行き先決定済み</option>';
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
        <form action="update.php" method="post">
            <input type="hidden" name="item_id" value="<?= $item_id ?>">
            <div class="form_entry">
                <p>写真</p>
                <img src="<?= $photo ?>" width="232" alt="登録するアイテムの画像">
                <input type="hidden" name="photo" value="<?= $photo ?>">
            </div>
            <div class="form_entry">
                <p>名称</p>
                <input type="text" name="title" value="<?= $name ?>">
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
                <textarea name="description" id="description" cols="30" rows="4"><?= $description ?></textarea>
            </div>
            <div class="form_entry">
                <p>状態</p>
                <select name="state">
                    <?= $state_selection ?>
                </select>
            </div>
            <div class="form_entry">
                <p>希望</p>
                <textarea name="request" id="request" cols="30" rows="2"><?= $request ?></textarea>
            </div>
            <div class="form_entry">
                <p>引き取り人</p>
                <select name="dest_person">
                    <option value=""></option>
                    <?= $member_selection ?>
                </select>
            </div>
            <div class="form_entry">
                <p>決定理由</p>
                <input type="text" name="reason" value="<?= $reason ?>">
            </div>
            <div class="form_entry">
                <p>保管期限</p>
                <input type="date" name="storage_period" value="<?= $storage_period ?>"/>
            </div>
            <input type="submit" value="更新する">
        </form>
        <form method="POST" action="edit_delete.php" onsubmit="return confirm_delete()" class="edit_delete">
            <input type="hidden" name="item_id" value="<?= $item_id ?>">
            <input type="submit" value="削除する">
        </form>
        </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function confirm_delete() {
            let select = confirm("本当に削除しますか？");
            return select;
        }
</script>
</body>

</html>
