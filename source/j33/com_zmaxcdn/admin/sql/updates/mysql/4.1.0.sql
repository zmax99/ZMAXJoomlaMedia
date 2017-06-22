/*修改#__zmaxcdn_upload_config`表 ，在表中增加 alias client*/
ALTER TABLE `#__zmaxcdn_upload_config` ADD alias varchar(256) NOT NULL;
ALTER TABLE `#__zmaxcdn_upload_config` ADD client varchar(10) NOT NULL;

/*修改#__zmaxcdn_taggroup`表 ，在表中增加 alias */
ALTER TABLE `#__zmaxcdn_taggroup` ADD alias varchar(256) NOT NULL;

/*插入两条默认的记录*/
INSERT INTO `#__zmaxcdn_upload_config` (`id`, `title`, `alias`, `description`, `config`, `default`, `client`) VALUES
(1, '默认后台上传设置', 'default-admin-upload-config', '<p>本配置记录为系统默认的后台上传的设置。通过修改本记录，你可以调整ZMAX媒体管理组件的一些动作。比喻说默认上传文件的大小，能够上传文件的类型。</p>\r\n<p>更多关于配置的设置。请访问 http://www.zmax99.com</p>', '{"max_size":"4","file_type":"*","show_file_list":"1","show_search_tool":"1","allow_download":"1","allow_guest_upload":"1","allow_see_other":"0","catid":"","extension":"com_content","upload_btn_text":"点击上传","drag_text":"你可以直接将文件拖动到这个区域","show_local_upload":"1","show_qiniu_upload":"1","show_title_prev":"1","show_title_category":"1","show_title_download":"1","show_title_size":"1","show_title_doctype":"1","show_title_date":"1","show_title_remark":"1"}', 1, 'admin'),
(2, '默认前台上传设置', 'default-site-upload-config', '<p>本配置记录为系统默认的前台上传的设置。通过修改本记录，你可以调整ZMAX媒体管理组件的一些动作。比喻说默认上传文件的大小，能够上传文件的类型。</p>\r\n<p>更多关于配置的设置。请访问 http://www.zmax99.com</p>', '{"max_size":"4","file_type":"*","show_file_list":"1","show_search_tool":"1","allow_download":"1","allow_guest_upload":"1","allow_see_other":"0","catid":"","extension":"com_content","upload_btn_text":"点击上传","drag_text":"你可以直接将文件拖动到这个区域","show_local_upload":"1","show_qiniu_upload":"0","show_title_prev":"1","show_title_category":"0","show_title_download":"1","show_title_size":"0","show_title_doctype":"0","show_title_date":"0","show_title_remark":"0"}', 1, 'site');



