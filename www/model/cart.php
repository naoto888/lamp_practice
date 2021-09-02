<?php 
//関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
//dbファイルを読み込み
require_once MODEL_PATH . 'db.php';

//カートに追加するためのデータ
function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = :user_id
  ";
  return fetch_all_query($db, $sql,array(':user_id' => $user_id));
}
//カートに追加するためのデータ
function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = :user_id
    AND
      items.item_id = :item_id
  ";

  return fetch_query($db, $sql,array(':user_id' => $user_id,':item_id' => $item_id));

}

//カートに商品を追加
function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

//1以上の整数が有効
function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(:item_id, :user_id, :amount)
  ";

  return execute_query($db, $sql,array(':item_id' => $item_id,':user_id' => $user_id,':amount' => $amount));
}

//カートの数量変更
function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = :amount
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";
  return execute_query($db, $sql,array(':cart_id' => $cart_id,':amount' => $amount));
}

//カートの削除
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";

  return execute_query($db, $sql,array(':cart_id' => $cart_id));
}

//購入処理
function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  
  delete_user_carts($db, $carts[0]['user_id']);
}

//カートの中身を処理
function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = :user_id
  ";

  execute_query($db, $sql,array(':user_id' => $user_id));
}

//商品の金額
function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

function insert_history($db, $user_id, $total_price){
  $sql = "
  INSERT INTO
      history(
        user_id,
        total_price
      )
    VALUES(:user_id, :total_price)
  ";

  return execute_query($db, $sql,array(':user_id' => $user_id, ':total_price' => $total_price));
}

function get_details($db, $order_id){
  $sql = "
    SELECT
      details.price,
      details.amount,
      details.price,
      items.name
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

function insert_details($db,$carts,$order_id){
  
  foreach($carts as $cart){
    if(insert_detail($db,$order_id,$cart['item_id'],$cart['price'],$cart['amount']) === false){
      return false;
    }
  }
  return true;
}

function insert_detail($db, $order_id, $item_id, $price, $amount){
  $sql = "
    INSERT INTO
      details(
        order_id,
        item_id,
        price,
        amount
      )
    VALUES(:order_id, :item_id, :price, :amount)
  ";

return execute_query($db, $sql,array(':order_id' => $order_id,':item_id' => $item_id,':price' => $price,':amount' => $amount));
}