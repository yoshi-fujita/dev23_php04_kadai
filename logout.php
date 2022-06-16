<?php
require_once('funcs.php');
no_cache();
session_start();
console_log("------------------------ Entered to logout.php", "------------------------");
session_var_dump();


//SESSIONを初期化（空っぽにする）
$_SESSION = array();

console_log("------------------------", "SESSION 変数を初期化（空っぽにする）");
session_var_dump();


//Cookieに保存してあるSessionIDの保存期間を過去にして破棄
if (isset($_COOKIE[session_name()])) { //session_name()は、セッションID名を返す関数
    setcookie(session_name(), '', time()-42000, '/');
}

console_log("------------------------", "Cookie に保存してある SessionID の保存期間を過去にして破棄");
session_var_dump();


//サーバ側での、セッションIDの破棄
session_destroy();

console_log("------------------------", "session_destroy でサーバ側のセッションID の破棄");
session_var_dump();


//処理後、login.phpへリダイレクト
move_page('login.php', 0);

?>
