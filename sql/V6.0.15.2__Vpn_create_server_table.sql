CREATE TABLE `vpn_servers` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT '',
  `domain` varchar(256) NOT NULL DEFAULT '',
  `port` int(11) NOT NULL DEFAULT 0,
  `protocol` varchar(16) NOT NULL DEFAULT 'tcp',
  `type` varchar(64) NOT NULL DEFAULT 'application/octet-stream',
  `country` varchar(64) DEFAULT '',
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `content_protocol_filter_idx` (`tenant`,`protocol`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
