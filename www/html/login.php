<?php
//定数ファイルを読み込み
require_once '../conf/const.php';
//関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

//ログインチェックを行うため、セッションを開始
session_start();

//ログインチェック用関数を利用
if(is_logined() === true){
  //ログインしてない場合はホームにリダイレクト
  redirect_to(HOME_URL);
}

//ビューを読み込み
include_once VIEW_PATH . 'login_view.php';