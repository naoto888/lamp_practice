<?php
//定数ファイルを読み込み
require_once '../conf/const.php';
//関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
//userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
//itemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';
//cartデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'cart.php';

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

$carts = get_user_carts($db, $user['user_id']);

//商品が購入可能か
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 

$total_price = sum_carts($carts);

//ビューの読み込み
include_once '../view/finish_view.php';