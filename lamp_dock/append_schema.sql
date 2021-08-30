-- 購入テーブル、「注文番号」「購入日時」「該当の注文の合計金額」,userid
-- 購入明細テーブル, 「購入数」、注文番号、itemid

CREATE TABLE `history` (
  `order_id` int(11) AUTO_INCREMENT NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  primary key(order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `details` (
  `detail_id` int(11) AUTO_INCREMENT NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  primary key(detail_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;