/*修改表 ，在表中增加别名 ，图片，published*/
ALTER TABLE `#__zmaxcdn_item` ADD alias VARCHAR(256) NOT NULL;
ALTER TABLE `#__zmaxcdn_item` ADD image text NOT NULL;
ALTER TABLE `#__zmaxcdn_item` ADD published tinyint(1) unsigned  NOT NULL DEFAULT '1';

/*向content_type表中插入一条记录*/
INSERT INTO `#__content_types` ( `type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`) VALUES
('Zmaxcdn Item', 'com_zmaxcdn.item', '{"special":{"dbtable":"#__zmaxcdn_item","key":"id","type":"Item","prefix":"JTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}', '', '{"common":{"core_content_item_id":"id","core_title":"name","core_alias":"filename","core_state":"published","core_access":"access","core_images":"image","core_body":"description"}}', '', '');