/*修改表 ，在表中增加下载次数，查看次数 ,hash*/
ALTER TABLE `#__zmaxcdn_item` ADD hits int(11) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `#__zmaxcdn_item` ADD views int(11) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `#__zmaxcdn_item` ADD hash varchar(256)  NOT NULL ;
