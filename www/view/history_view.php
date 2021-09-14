<?php
  // クリックジャッキング対策
  header('X-FRAME-OPTIONS: DENY');
?>
<DOCTYPE html>
<html lang="ja">
<head>
<?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(h(STYLESHEET_PATH . 'cart.css')); ?>">
</head>

<body>
　<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴</h1>
  <div class="container">

  <?php include VIEW_PATH . 'templates/messages.php'; ?>

  <?php if(count($histories) > 0){ ?>
  <table class="table table-bordered">
    <thead class="thead-light">
      <tr>
        <th>注文番号</th>
        <th>購入日時</th>
        <th>合計金額</th>
        <th>購入明細</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($histories as $history){ ?>
      <tr>
        <td><?php print h($history['order_id']); ?></td>
        <td><?php print h($history['created']); ?></td>
        <td><?php print h($history['total_price']); ?></td>
        <td>
          <form method="post" action="detail.php">
            <input type="submit" value="購入明細">
            <input type="hidden" name="order_id" value="<?php print(h($history['order_id'])); ?>">
            <input type="hidden" name="created" value="<?php print(h($history['created'])); ?>">
            <input type="hidden" name="total_price" value="<?php print(h($history['total_price'])); ?>">
            <input type="hidden" name="token" value="<?php print h($token); ?>">
          </form>
        </td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
  <?php }else{ ?>
  <P>購入履歴がありません</p>
  <?php } ?>
  </div>
</body>
</html>
