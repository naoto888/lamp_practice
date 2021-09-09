<?php 
//関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
//dbファイルを読み込み
require_once MODEL_PATH . 'db.php';

function get_detail($db, $order_id){
  $sql = "
    SELECT
      items.name,
      details.amount,
      details.price
    FROM
      details
    JOIN
      items
    ON
      details.item_id = items.item_id
    WHERE
      details.order_id = :order_id
  ";
  return fetch_all_query($db, $sql,array(':order_id' => $order_id));
}

