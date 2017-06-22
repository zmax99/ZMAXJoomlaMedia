DROP TABLE IF EXISTS `#__zmaxcdn_taggroup`; /*ZMAXCDN 标签组表*/
DROP TABLE IF EXISTS `#__zmaxcdn_upload_config`; /*ZMAXCDN 上传设置表*/

CREATE TABLE IF NOT EXISTS `#__zmaxcdn_upload_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(512) NOT NULL,
  `description` varchar(512) NOT NULL,
  `config` text NOT NULL,
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__zmaxcdn_taggroup` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(512) NOT NULL,
  `description` text NOT NULL,
  `config` text NOT NULL,
  `tags` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;