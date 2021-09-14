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

//ログインチェック用関数を利用
if(is_logined() === false){
  //ログインしてない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

//PDOを取得
$db = get_db_connect();
//PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

//オープンアイテムのデータを取得
$items = get_open_items($db);
//$rankingに上位三件まで代入する
$rankings = get_ranking($db);
$rank = 1;
$token = get_csrf_token();
//ビューを読み込み
include_once VIEW_PATH . 'index_view.php';