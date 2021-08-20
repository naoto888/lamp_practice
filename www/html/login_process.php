<?php
//定数ファイルを読み込み
require_once '../conf/const.php';
//関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
//userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

//ログインチェックを行うため、セッションを開始
session_start();

//ログインチェック用関数を利用
if(is_logined() === true){
  //ログインしてない場合はログインページにリダイレクト
  redirect_to(HOME_URL);
}


$name = get_post('name');
$password = get_post('password');

//PDOを取得
$db = get_db_connect();

//ログインデータを取得
$user = login_as($db, $name, $password);
if( $user === false){
  set_error('ログインに失敗しました。');
  //ログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  //adminページにリダイレクト
  redirect_to(ADMIN_URL);
}
//ホームにリダイレクト
redirect_to(HOME_URL);