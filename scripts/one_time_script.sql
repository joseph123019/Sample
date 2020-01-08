CREATE TABLE IF NOT EXISTS `ssp_order_items` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL DEFAULT '0',
  `order_id` int NOT NULL DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  `quantity_purchased` decimal(15, 12) NOT NULL DEFAULT '0',
  `discounted_quantity` decimal(15, 12) NOT NULL DEFAULT '0',
  `discounted_value_quantity` decimal(15, 12) NOT NULL DEFAULT '0',
  `discounted_scpwd_quantity` decimal(15, 12) NOT NULL DEFAULT '0',
  `scpwd_discount` decimal(15, 12) NOT NULL DEFAULT '0',
  `item_price` decimal(15,2) NOT NULL,
  `customer_type` varchar(255) DEFAULT NULL,
  `discount_percent` decimal(15,2) NOT NULL DEFAULT '0',
  `discount_value` decimal(15, 12) NOT NULL DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `item_id` (`item_id`)
);

CREATE TABLE IF NOT EXISTS `ssp_orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `order_no` varchar(256) DEFAULT NULL,
  `order_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `store_id` int NOT NULL, #new 5/21/2018
  `client_id` int NOT NULL,
  `order_table_id` int NOT NULL,
  `pax` decimal(15, 12) NOT NULL DEFAULT '0',
  `comment` text DEFAULT NULL,
  `status` tinyint(2) NOT NULL DEFAULT 0,
  `customer_type_id` int NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `store_address` varchar(256) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_by` int DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `version_no` int NOT NULL DEFAULT 1,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ssp_categories` (
`category_id` int(10) NOT NULL AUTO_INCREMENT,
`client_id` int NOT NULL,
`store_type_id` int NOT NULL,
`offline_category_id` int(11) NOT NULL DEFAULT 0,
`name` varchar(255) NOT NULL,
`description` varchar(255) NULL,
`vat_percent` decimal(15,2) NOT NULL,
`pic_filename` varchar(255) DEFAULT NULL,
`isDeleted` tinyint(1) NOT NULL DEFAULT '0',
`createdBy` int NOT NULL,
`createdDtm` datetime NOT NULL,
`updatedBy` int DEFAULT NULL,
`updatedDtm` datetime DEFAULT NULL,
`version_no` int NOT NULL DEFAULT 1,
PRIMARY KEY (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ssp_category_stores` (
`category_store_id` int(10) NOT NULL AUTO_INCREMENT,
`store_id` int NOT NULL,
`category_id` int NOT NULL,
`version_no` int NOT NULL DEFAULT 1,
PRIMARY KEY (`category_store_id`),
KEY (`store_id`, `category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `items` (
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `offline_item_id` int(11) NOT NULL DEFAULT 0,
  `client_id`int(11) NOT NULL,
  `unit_id` int DEFAULT NULL,
  `item_number` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `cost_price` decimal(15,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `vat_percent` decimal(15,2) NOT NULL,
  `reorder_level` decimal(15,3) NOT NULL DEFAULT '0',
  `receiving_quantity` decimal(15,3) NOT NULL DEFAULT '1',
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `pic_filename` varchar(255) DEFAULT NULL,
  `allow_alt_description` tinyint(1) NOT NULL,
  `is_serialized` tinyint(1) NOT NULL,
  `is_special` tinyint(1) NOT NULL DEFAULT 0,
  `is_card_reward` tinyint(1) NOT NULL DEFAULT 0,
  `reward_quantity` decimal(15,2) NOT NULL,
  `point_added` decimal(15,2) NOT NULL,
  `stock_type` TINYINT(2) NOT NULL DEFAULT 0,
  `item_type` TINYINT(2) NOT NULL DEFAULT 0,
  `item_material` TINYINT(2) NOT NULL DEFAULT 0,
  `tax_category_id` int(10) NOT NULL DEFAULT 1,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `is_running` int(1) NOT NULL DEFAULT '0',
  `app_hide` int(1) NOT NULL DEFAULT '0',
  `custom1` VARCHAR(255) DEFAULT NULL,
  `custom2` VARCHAR(255) DEFAULT NULL,
  `custom3` VARCHAR(255) DEFAULT NULL,
  `custom4` VARCHAR(255) DEFAULT NULL,
  `custom5` VARCHAR(255) DEFAULT NULL,
  `custom6` VARCHAR(255) DEFAULT NULL,
  `custom7` VARCHAR(255) DEFAULT NULL,
  `custom8` VARCHAR(255) DEFAULT NULL,
  `custom9` VARCHAR(255) DEFAULT NULL,
  `custom10` VARCHAR(255) DEFAULT NULL,
  `createdBy` int NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL,
  `version_no` int NOT NULL DEFAULT 1,
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_number` (`item_number`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ssp_item_stores` (
`item_store_id` int(10) NOT NULL AUTO_INCREMENT,
`store_id` int NOT NULL,
`item_id` int NOT NULL,
`version_no` int NOT NULL DEFAULT 1,
PRIMARY KEY (`item_store_id`),
KEY (`store_id`, `item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ssp_item_prices` (
  `item_price_id` int(10) NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `store_id` int NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `version_no` int NOT NULL DEFAULT 1,
  PRIMARY KEY (`item_price_id`),
  #INDEX name (`item_id`,`store_id`)
  UNIQUE KEY(`item_id`,`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
