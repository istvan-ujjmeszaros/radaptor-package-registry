<?php assert(isset($this) && $this instanceof Template); ?>
<?php $this->registerLibrary('__ADMIN_BLOGLIST'); ?>

<script type="text/javascript">
	$(document).ready(function () {
		widgettype.blogList.init({
			"ajaxBaseUrl": "<?= Url::getAjaxUrl("blogs.blogListAjax"); ?>",
			"url_edit": "<?= Form::getSeoUrl(FormList::BLOG); ?>",
			"blogListTableId": "bloglist-<?= $this->getWidgetConnection()->connection_id; ?>",
			"icon_edit": '<?= Icons::get(IconNames::EDIT, $this->strings["common.edit"], "medium") ?>',
			"operation_edit": <?= Roles::hasRole(RoleList::ROLE_BLOG_ADMIN) ? 'true' : 'false' ?>
		});
	});
</script>

<div class="subheader">
	<h1><?= e($this->strings['blog.list.title']) ?></h1>
	<br class="cleaner">
</div>
<p>
	<a href="<?= form_url(FormList::BLOG); ?>" class="controller-menu"><?= e($this->strings['blog.list.new']) ?></a><span></span>
</p>


<table class="display highlight_row commonDataTable" id="bloglist-<?= $this->getWidgetConnection()->connection_id; ?>">
	<thead>
	<tr>
		<th width="1px"><?= e($this->strings['common.id']) ?></th>
		<th width="1px"><?= e($this->strings['blog.field.date.label']) ?></th>
		<th><?= e($this->strings['blog.field.title.label']) ?></th>
		<th width="10px"><?= e($this->strings['common.operations']) ?></th>
	</tr>
	</thead>
</table>
