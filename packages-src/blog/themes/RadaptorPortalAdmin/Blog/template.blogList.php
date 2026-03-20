<?php assert(isset($this) && $this instanceof Template); ?>
<?php $this->registerLibrary('__RADAPTOR_PORTAL_ADMIN_DATATABLES'); ?>

<div class="subheader">
	<h1><?= e($this->strings['blog.list.title']) ?></h1>
</div>

<p>
	<a href="<?= form_url(FormList::BLOG); ?>" class="btn btn-outline-primary">
		<i class="bi bi-plus-lg me-1"></i><?= e($this->strings['blog.list.new']) ?>
	</a>
</p>

<table class="table table-striped" id="blogTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>">
		<thead>
		<tr>
			<th style="width:60px"><?= e($this->strings['common.id']) ?></th>
			<th style="width:110px"><?= e($this->strings['blog.field.date.label']) ?></th>
			<th><?= e($this->strings['blog.field.title.label']) ?></th>
			<th><?= e($this->strings['blog.field.slug.label']) ?></th>
			<th style="width:80px" class="text-end"><?= e($this->strings['common.actions']) ?></th>
		</tr>
		</thead>
	<tbody>
	</tbody>
</table>

<script>
$(document).ready(function() {
	const editTitle = <?= json_encode($this->strings['common.edit']) ?>;
	$('#blogTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>').DataTable({
		processing: true,
		serverSide: true,
		ajax: function(data, callback) {
			$.getJSON("<?= ajax_url_raw('blogs.blogListAjaxLoad'); ?>", data, function(payload) {
				if (!payload || payload.ok !== true) {
					callback({ data: [], recordsTotal: 0, recordsFiltered: 0, draw: data.draw });
					return;
				}

				var result = payload.data || {};
				callback({
					data: result.data || [],
					recordsTotal: result.recordsTotal || 0,
					recordsFiltered: result.recordsFiltered || 0,
					draw: result.draw || data.draw
				});
			}).fail(function() {
				callback({ data: [], recordsTotal: 0, recordsFiltered: 0, draw: data.draw });
			});
		},
		columns: [
			{ data: 0, width: '60px' },
			{ data: 1, width: '110px' },
			{ data: 2 },
			{ data: 3 },
				{
					data: 4,
					width: '80px',
					className: 'text-end',
					orderable: false,
					render: function(data) {
						return '<a href="<?= Form::getSeoUrl(FormList::BLOG); ?>&item_id=' + data + '" class="btn btn-sm btn-outline-secondary" title="' + editTitle + '"><i class="bi bi-pencil"></i></a>';
					}
				}
			],
			order: [[0, 'desc']],
			language: {
				processing: <?= json_encode($this->strings['datatable.processing']) ?>,
				search: <?= json_encode($this->strings['datatable.search'] . ':') ?>,
				lengthMenu: <?= json_encode($this->strings['datatable.length_menu']) ?>,
				info: <?= json_encode($this->strings['datatable.info_compact']) ?>,
				infoEmpty: <?= json_encode($this->strings['datatable.info_empty']) ?>,
				infoFiltered: <?= json_encode($this->strings['datatable.info_filtered_compact']) ?>,
				loadingRecords: <?= json_encode($this->strings['datatable.loading_records']) ?>,
				zeroRecords: <?= json_encode($this->strings['datatable.zero_records']) ?>,
				emptyTable: <?= json_encode($this->strings['datatable.empty_table']) ?>,
				paginate: {
					first: <?= json_encode($this->strings['datatable.first']) ?>,
					previous: <?= json_encode($this->strings['datatable.previous']) ?>,
					next: <?= json_encode($this->strings['datatable.next']) ?>,
					last: <?= json_encode($this->strings['datatable.last']) ?>
				}
			}
		});
});
</script>
