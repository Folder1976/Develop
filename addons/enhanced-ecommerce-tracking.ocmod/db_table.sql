
CREATE TABLE IF NOT EXISTS `oc_ee_click_to_client` (
  `product_id` int(11) NOT NULL,
  `client_id` varchar(64) NOT NULL,
  PRIMARY KEY (`product_id`,`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `oc_ee_order_to_client` (
  `order_id` int(11) NOT NULL,
  `client_id` varchar(64) NOT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_id`,`client_id`,`sent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;