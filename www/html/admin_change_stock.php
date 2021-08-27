<?php
//定数ファイルを読み込み
require_once '../conf/const.php';
//関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
//userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
//itemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';

//ログインチェックを行うため、セッションを開始
session_start();
$token = get_post('token');
if(is_valid_csrf_token($token) === false){
  unset($_SESSION['csrf_token']);
  redirect_to(LOGIN_URL);
}
unset($_SESSION['csrf_token']);
//ログインチェック用関数を利用
if(is_logined() === false){
  //ログインしてない場合はログインページにリダイレクト
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
$stock = get_post('stock');

//在庫更新
if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');
} else {
  set_error('在庫数の変更に失敗しました。');
}

//adminページにリダイレクト
redirect_to(ADMIN_URL);