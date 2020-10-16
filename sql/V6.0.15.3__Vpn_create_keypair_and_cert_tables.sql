
CREATE TABLE `vpn_certs` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `pem` longtext DEFAULT '',
  `is_revoked` tinyint(1) NOT NULL DEFAULT 0,
  `expire_dtime` datetime DEFAULT NULL,
  `account_id` mediumint(9) unsigned NOT NULL DEFAULT 0,
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `account_id_foreignkey_idx` (`account_id`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `vpn_keypairs` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `private_pem` longtext DEFAULT '',
  `public_pem` longtext DEFAULT '',
  `account_id` mediumint(9) unsigned NOT NULL DEFAULT 0,
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `account_id_foreignkey_idx` (`account_id`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;