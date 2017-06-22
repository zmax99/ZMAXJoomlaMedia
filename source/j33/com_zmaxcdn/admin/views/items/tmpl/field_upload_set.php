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
<?php if(!$this->config->catid):?>
<div class="uploader-set-container">
	<div class="zmaxui-row">
		<div class="zmaxui-col-md-2">
			<label>请选择分类</label>
		</div>
		<div class="zmaxui-col-md-10">
			<?php $options = zmaxtreeCategory::getUserCategory();?>
			<select	name="catid" id="select_catid" class="itemcate">
				<?php echo JHtml::_('select.options',$options,'value','text',$this->category_id,true);?>
			</select>
		</div>
	</div>
</div>
<?php else:?>
<input type="hidden" name="catid" id="select_catid" value="<?php echo $this->config->catid;?>">	
<?php endif;?>
<!-- 这里也出现了extension了 -->
<input type="hidden" name="extension" id="extension" value="<?php echo $this->config->extension;?>" />
	
	

