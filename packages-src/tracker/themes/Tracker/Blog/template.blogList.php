<?php assert(isset($this) && $this instanceof Template); ?>
<?php
$this->registerLibrary('DATATABLES');
$this->registerLibrary('DATATABLES_FILTER');
$edit_icon = Icons::get(IconNames::EDIT, $this->strings['common.edit'], 'medium');
?>

<div class="subheader">
	<h1><?= e($this->strings['blog.list.title']) ?></h1>
	<br class="cleaner">
</div>

<p>
	<a href="<?= form_url(FormList::BLOG); ?>" class="controller-menu"><?= e($this->strings['blog.list.new']) ?></a>
</p>

<table class="display highlight_row commonDataTable" id="blogTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>">
	<thead>
		<tr>
			<th style="width:50px"><?= e($this->strings['common.id']) ?></th>
			<th style="width:120px"><?= e($this->strings['blog.field.date.label']) ?></th>
			<th><?= e($this->strings['blog.field.title.label']) ?></th>
			<th><?= e($this->strings['blog.field.slug.label']) ?></th>
			<th style="width:80px"><?= e($this->strings['common.actions']) ?></th>
		</tr>
		</thead>
	<tbody>
	</tbody>
</table>

<script type="text/javascript">
$(document).ready(function() {
		const editIcon = <?= json_encode($edit_icon) ?>;
		$('#blogTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>').DataTable({
		processing: true,
		serverSide: true,
		ajax: "<?= ajax_url_raw('blogs.blogListAjaxLoad'); ?>",
		columns: [
			{data: 0},
			{data: 1},
			{data: 2},
			{data: 3},
				{
					data: 4,
					orderable: false,
					render: function(data) {
						return '<a href="<?= Form::getSeoUrl(FormList::BLOG); ?>&item_id=' + data + '">' + editIcon + '</a>';
					}
				}
			],
			order: [[0, 'desc']],
			language: {
				infoFiltered: <?= json_encode($this->strings['datatable.info_filtered_compact']) ?>,
				infoEmpty: <?= json_encode($this->strings['datatable.info_empty']) ?>,
				info: <?= json_encode($this->strings['datatable.info_compact']) ?>,
				emptyTable: <?= json_encode($this->strings['datatable.empty_table']) ?>,
				paginate: {
					first: <?= json_encode($this->strings['datatable.first']) ?>,
					last: <?= json_encode($this->strings['datatable.last']) ?>,
					next: <?= json_encode($this->strings['datatable.next']) ?>,
					previous: <?= json_encode($this->strings['datatable.previous']) ?>
				},
				search: <?= json_encode($this->strings['datatable.search']) ?>,
				zeroRecords: <?= json_encode($this->strings['datatable.zero_records']) ?>
			}
		});
	});
</script>
