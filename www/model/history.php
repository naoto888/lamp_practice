<?php 
//関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
//dbファイルを読み込み
require_once MODEL_PATH . 'db.php';

function get_history($db, $user_id){
  $sql="
    SELECT
       total_price,
       order_id,
       created
    FROM
      history
    WHERE
    user_id = :user_id
  ";
  return fetch_all_query($db, $sql,array(':user_id' => $user_id));
}

function get_allhistory($db){
  $sql="
    SELECT
      total_price,
      order_id,
      created
    FROM
      history
  ";
  return fetch_all_query($db, $sql);
}