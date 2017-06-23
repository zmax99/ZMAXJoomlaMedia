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

$filters = $this->filterForm->getGroup('filter');
$sorts =  $this->filterForm->getGroup('list');

$searchField = $filters["filter_search"];
$filterField = $filters["filter_type"];
$sortField = $sorts["list_fullordering"];
?>

<div class="zfilter-container">
	<div class="zmaxui-row">
		<div class="zmaxui-col-md-3">
			<?php echo $filterField->input; ?>
		</div>
		<div class="zmaxui-col-md-3">
			<?php echo $sortField->input; ?>
		</div>
		<div class="zmaxui-col-md-4 text-right pull-right">	
			
			<div class="btn-wrapper input-append">
				<?php echo $searchField->input;?>
				<button type="submit" class="btn hasTooltip" title="" data-original-title="搜索">
					<span class="icon-search"></span>
				</button>
			</div>
		</div>
	</div>	
</div>


