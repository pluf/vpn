
CREATE TABLE `vpn_account_limits` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(250) NOT NULL DEFAULT '',
  `value` varchar(256) DEFAULT '',
  `account_id` mediumint(9) unsigned NOT NULL DEFAULT 0,
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vpn_account_limit_key_idx` (`tenant`,`key`,`account_id`),
  KEY `account_id_foreignkey_idx` (`account_id`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `vpn_account_limits` 
   ADD CONSTRAINT `fk__account_of_vpn_limit` 
   FOREIGN KEY (`account_id`) 
   REFERENCES `user_account` (`id`);