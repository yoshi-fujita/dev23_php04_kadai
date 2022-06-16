<?php
// キャッシュを効かせないおまじない
function no_cache() {
    header('Expires: Tue, 1 Jan 2019 00:00:00 GMT');
    header('Last-Modified:'. gmdate( 'D, d M Y H:i:s' ). 'GMT');
    header('Cache-Control:no-cache,no-store,must-revalidate,max-age=0');
    header('Cache-Control:pre-check=0,post-check=0',false);
    header('Pragma:no-cache');
}

// Console に表示する
function console_log($label, $data)
{
    echo '<script>';
    echo 'console.log(' . json_encode($label) . ',' . json_encode($data) .')';
    echo '</script>';
}

// html に表示する
function show_page($html)
{
    echo $html;
}

// ページを遷移する
function move_page($url, $delay)
{
    echo '<script type="text/javascript">window.setTimeout(function() { window.location.href="';
    echo $url;
    echo '"; }, ';
    echo $delay;
    echo ');</script>';
}

// セッションID やセッション変数をダンプ表示する
function session_var_dump()
{
    console_log("PHP session_id():", session_id());
    console_log("MAMP session_id:", glob('/Applications/MAMP/tmp/php/sess_*'));
    if (isset($_COOKIE["PHPSESSID"])) { console_log("cookie session_id:", $_COOKIE["PHPSESSID"]); }
        else { console_log("cookie session_id:", "NULL"); }
    console_log("session_val:", $_SESSION);
}

// ログインチェック処理
function loginCheck()
{
    if (!isset($_SESSION['chk_ssid']) || $_SESSION['chk_ssid'] != session_id()) {
        move_page('login.php', 0);
    } else {
        session_regenerate_id(true);
        $_SESSION['chk_ssid'] = session_id();
    }
}

// XSS対応（ echo する場所で使用）
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// html の value に差し込む文字列のスペースを除く
function sr($str)
{
    return str_replace(" ", '', str_replace("　", '', $str));
}

// 改行コードを削除する
function nr($str)
{
    return str_replace(array("\r", "\n"), '', $str);
}

//DB接続関数：db_conn() 
//※ 関数を作成し、内容をreturnさせる。
//※ DBname等、今回の授業に合わせる。
function db_conn() {
    try {
        $db_name = 'gs_db';    //データベース名
        $db_id   = 'root';      //アカウント名
        $db_pw   = 'root';      //パスワード：XAMPPはパスワード無しに修正してください。
        $db_host = 'localhost'; //DBホスト
        $pdo = new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_id, $db_pw);
        return $pdo;
    } catch (PDOException $e) {
        exit('DB Connection Error:' . $e->getMessage());
    }
}

//SQLエラー関数：sql_error($stmt)
function sql_error($stmt) {
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
}

//リダイレクト関数: redirect($file_name)
function redirect($file_name) {
    header('Location: ' . $file_name);
    exit();
}

?>