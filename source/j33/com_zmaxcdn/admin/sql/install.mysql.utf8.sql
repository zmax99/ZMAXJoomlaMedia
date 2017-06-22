DROP TABLE IF EXISTS `#__zmaxcdn_item`; /*ZMAXCDN 资源表*/
DROP TABLE IF EXISTS `#__zmaxcdn_taggroup`; /*ZMAXCDN 标签组表*/
DROP TABLE IF EXISTS `#__zmaxcdn_upload_config`; /*ZMAXCDN 上传设置表*/

CREATE TABLE IF NOT EXISTS `#__zmaxcdn_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,/*记录的唯一ID*/
  `catid` int(11) NOT NULL DEFAULT '0',/*资源所在的分类*/
  `uid` int(11) NOT NULL DEFAULT '0',/*资源创建者的ID*/
  `name` varchar(512) NOT NULL DEFAULT '',/*资源在系统的名称*/
  `alias` varchar(256) NOT NULL,/*资源的别名，用于SEO方面*/
  `filename` varchar(512) NOT NULL DEFAULT '',/*资源原始的文件名称*/
  `description` varchar(512) NOT NULL,/*资源简单的描述*/
  `type` varchar(10) NOT NULL,/*文件的类型*/
  `extension` varchar(128) NOT NULL DEFAULT 'com_content',/*文件所属的扩展*/
  `size` int(10) unsigned NOT NULL DEFAULT '0',/*文件的大小*/
  `hits` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '下载的次数',
  `views` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '点击的次数',
  `length` varchar(256) NOT NULL DEFAULT '',/*文件的长度 针对视频文件*/
  `cdn_path` varchar(512) NOT NULL DEFAULT '',/*CDN上的地址*/
  `local_path` varchar(512) NOT NULL DEFAULT '',/*在本地服务器的路径*/
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',/*文件上传的时间*/
  `modify_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',/*文件最后修改时间*/
  `attr` text NOT NULL,/*其他的属性*/
  `attribs` text NOT NULL,/*用于第三方开发字段*/
  `image` text NOT NULL ,/*代表该资源的图片*/
  `access` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '发布状态',
  `hash` varchar(256) NOT NULL COMMENT '文件的哈希值',
  `other_id` varchar(256) NOT NULL COMMENT '第三方ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__zmaxcdn_upload_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(512) NOT NULL,
  `alias` varchar(256) NOT NULL,
  `description` varchar(512) NOT NULL,
  `config` text NOT NULL,
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `client` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__zmaxcdn_taggroup` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(512) NOT NULL,
  `alias` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `config` text NOT NULL,
  `tags` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*向content_type表中插入一条记录*/
INSERT INTO `#__content_types` ( `type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`) VALUES
('Zmaxcdn Item', 'com_zmaxcdn.item', '{"special":{"dbtable":"#__zmaxcdn_item","key":"id","type":"Item","prefix":"JTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}', '', '{"common":{"core_content_item_id":"id","core_title":"name","core_alias":"filename","core_state":"published","core_access":"access","core_images":"image","core_body":"description"}}', '', '');

/*为媒体管理组件插入两条默认的记录*/
INSERT INTO `#__zmaxcdn_upload_config` (`id`, `title`, `alias`, `description`, `config`, `default`, `client`) VALUES
(1, '默认后台上传设置', 'default-admin-upload-config', '<p>本配置记录为系统默认的后台上传的设置。通过修改本记录，你可以调整ZMAX媒体管理组件的一些动作。比喻说默认上传文件的大小，能够上传文件的类型。</p>\r\n<p>更多关于配置的设置。请访问 http://www.zmax99.com</p>', '{"max_size":"4","file_type":"*","show_file_list":"1","show_search_tool":"1","allow_download":"1","allow_guest_upload":"1","allow_see_other":"0","catid":"","extension":"com_content","filter_extension":"","upload_btn_text":"点击上传","drag_text":"你可以直接将文件拖动到这个区域","show_local_upload":"1","show_qiniu_upload":"1","show_title_prev":"1","show_title_category":"1","show_title_download":"1","show_title_size":"1","show_title_doctype":"1","show_title_date":"1","show_title_remark":"0","show_other_id":"0"}', 1, 'admin'),
(2, '默认前台上传设置', 'default-site-upload-config', '<p>本配置记录为系统默认的前台上传的设置。通过修改本记录，你可以调整ZMAX媒体管理组件的一些动作。比喻说默认上传文件的大小，能够上传文件的类型。</p>\r\n<p>更多关于配置的设置。请访问 http://www.zmax99.com</p>', '{"max_size":"4","file_type":"*","show_file_list":"1","show_search_tool":"1","allow_download":"1","allow_guest_upload":"1","allow_see_other":"0","catid":"","extension":"com_content","upload_btn_text":"点击上传","drag_text":"你可以直接将文件拖动到这个区域","show_local_upload":"1","show_qiniu_upload":"0","show_title_prev":"1","show_title_category":"0","show_title_download":"1","show_title_size":"0","show_title_doctype":"0","show_title_date":"0","show_title_remark":"0"}', 1, 'site');

