<?php
require_once('funcs.php');
no_cache();
session_start();
console_log("------------------------ Entered to login_act.php", "------------------------");
session_var_dump();


//POST値
$lid = $_POST['lid'];
$lpw = $_POST['lpw'];

console_log("------------------------", "ID, PW が入力された");
console_log("lid:", $lid);
console_log("lpw:", $lpw);

//1.  DB接続します
$pdo = db_conn();

//2. データ登録SQL作成
$stmt = $pdo->prepare('SELECT * FROM household_user WHERE lid = :lid AND lpw = :lpw');
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR);
$status = $stmt->execute();

//3. SQL実行時にエラーがある場合STOP
if ($status == false) {
    sql_error($stmt);
}

//4. 抽出データ数を取得
$val = $stmt->fetch();         //1レコードだけ取得する方法

//$count = $stmt->fetchColumn(); //SELECT COUNT(*)で使用可能()
//5. 該当レコードがあればSESSIONに値を代入
//if(password_verify($lpw, $val['lpw'])){ //* PasswordがHash化の場合はこっちのIFを使う
if ($val) {
    //Login成功時
    $_SESSION['chk_ssid']  = session_id();
    $_SESSION['kanri_flg'] = $val['kanri_flg'];
    $_SESSION['name']      = $val['name'];
    console_log("------------------------", "ログイン成功");
    session_var_dump();
    move_page('index.php', 0);
} else {
    //Login失敗時(Logout経由)
    console_log("val:", $val);
    show_page('ログインに失敗しました（5秒間表示してからページ遷移）');
    move_page('login.php', 5000);
}

?>
