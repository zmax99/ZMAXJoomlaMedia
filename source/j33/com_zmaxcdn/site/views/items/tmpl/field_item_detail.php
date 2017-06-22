<?php 
/**
 *	description:ZMAX媒体管理组件 资料列表布局文件
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2015-10-11
 * 	@license GNU General Public License version 3, or later
 *  check date:2016-07-11
 *  checker :min.zhang
 *  modified:min.zhang
 *  modify date:2017-06-21
 */
defined('_JEXEC') or die('you can not access this file!');
?>
<div class="zattach-container">
	<div class="zattach-inner">
		<div class="attach-detail-container">
			<h4>附件详情</h4>
			<div class="zattach-img-container">
				<img  class="responsive-img system-at-img"  width="100px"  src="#" alt="图片">
			</div>
			<div class="zattach-img-text">
				<div class="details">
					<div class="filename system-at-filename"></div>
					<div class="uploaded system-at-date"></div>
					<div class="file-size system-at-size"></div>
					<div class="dimensions system-at-dim"></div>				
					<a class="edit-attachment system-at-edit-link" href="#" target="_blank">编辑图像</a>
					<a class="button-link system-at-delete-link delete-attachment">永久删除</a>
				</div>
			</div>
		</div>
		<hr class="zline"/>
		<div class="attach-setting-container">
			<label class="setting" data-setting="url">
				<span class="name ">URL</span>
				<input type="text" class="system-at-set-url-input" value="" readonly="">
			</label>
			<label class="setting" data-setting="title">
				<span class="name">标题</span>
				<input type="text" class="system-at-set-title-input" value="">
			</label>	
			<label class="setting" data-setting="caption">
				<span class="name">说明</span>
				<textarea class="system-at-set-caption-input"></textarea>
			</label>	
			<label class="setting" data-setting="alt">
				<span class="name">替代文本</span>
				<input class="system-at-set-alt-input" type="text" value="">
			</label>
			<label class="setting" data-setting="description">
				<span class="name">图像描述</span>
				<textarea class="system-at-set-desc-input"></textarea>
			</label>

			<div class="attachment-display-settings">
				<h4>附件显示设置</h4>
					<label class="setting">
						<span>对齐方式</span>
						<select class="alignment" data-setting="align" data-user-setting="align">
							<option value="left">左</option>
							<option value="center">中</option>
							<option value="right">右</option>
							<option value="none" selected="">无</option>
						</select>
					</label>
				<div class="setting">
					<label>
							<span>链接到</span>
							<select class="link-to" data-setting="link" data-user-setting="urlbutton">
								<option value="none" selected="">无</option>
								<option value="file">媒体文件</option>	
								<option value="post">附件页面</option>
							</select>
					</label>
					<input type="text" class="link-to-custom" data-setting="linkUrl" readonly="">
				</div>	
				<label class="setting">
					<span>尺寸</span>
					<select class="size" name="size" data-setting="size" data-user-setting="imgsize">
						<option value="thumbnail">缩略图 – 150 × 150</option>
						<option value="medium">中等 – 300 × 298</option>
						<option value="full" selected="selected">完整尺寸 – 60 × 60</option>	
					</select>
				</label>
			</div>
		</div>
	</div>
</div>