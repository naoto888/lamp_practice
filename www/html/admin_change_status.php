<?php
//定数ファイルを読み込み
require_once '../conf/const.php';
//関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
//userデータに関するファイルを読み込み
require_once MODEL_PATH . 'user.php';
//itemデータに関するファイルを読み込み
require_once MODEL_PATH . 'item.php';

//ログインチェックを行うためセッションを開始
session_start();
$token = get_post('token');
if(is_valid_csrf_token($token) === false){
  unset($_SESSION['csrf_token']);
  redirect_to(LOGIN_URL);
}
unset($_SESSION['csrf_token']);
//ログインチェック用関数を利用
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//PDOを取得
$db = get_db_connect();

//PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

//adminではなかったらログインページにリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$item_id = get_post('item_id');
$changes_to = get_post('changes_to');

//ステータスの変更
if($changes_to === 'open'){
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
}else if($changes_to === 'close'){
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
}else {
  set_error('不正なリクエストです。');
}

//adminページにリダイレクト
redirect_to(ADMIN_URL);