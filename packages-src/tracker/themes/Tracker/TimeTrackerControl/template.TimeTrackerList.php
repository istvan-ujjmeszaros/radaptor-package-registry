<?php assert(isset($this) && $this instanceof Template); ?>
<?php
$this->registerLibrary('JQUERY');
$this->registerLibrary('COMMON');
$this->registerLibrary('DATATABLES');
$this->registerLibrary('DATATABLES_FILTER');
$this->registerLibrary('QTIP');
?>
<div class="subheader">
	<h1><?= e($this->strings['timetracker.list.title']) ?></h1>
	<br class="cleaner">
</div>
<p>
	<a href="<?= form_url(FormList::TIMETRACKERENTRY); ?>" class="controller-menu"><?= e($this->strings['timetracker.form.title_create']) ?></a><span></span>
</p>


<table class="display highlight_row commonDataTable" id="dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>">
	<thead>
		<tr>
			<th style="width:1px;"><?= e($this->strings['timetracker.field.date.label']) ?></th>
			<th style="width:auto;"><?= e($this->strings['timetracker.field.description.label']) ?></th>
			<th style="width:1px"><?= e($this->strings['timetracker.list.start_time']) ?></th>
			<th style="width:1px"><?= e($this->strings['timetracker.list.end_time']) ?></th>
			<th style="width:1px"><?= e($this->strings['timetracker.list.duration']) ?></th>
			<th style="width:1px"><?= e($this->strings['common.user']) ?></th>
			<th style="width:1px"><?= e($this->strings['common.actions']) ?></th>
		</tr>
	</thead>
		<tbody>
		<tr>
			<td colspan="7" class="dataTables_empty"><?= e($this->strings['datatable.loading_records']) ?></td>
		</tr>
		</tbody>
</table>

	<?php
	$this->props['site_url'] = Url::getCurrentHost();
$this->props['icon_edit'] = Icons::get(IconNames::EDIT, $this->strings['common.edit'], 'medium');
$this->props['icon_delete'] = Icons::get(IconNames::TRASH, $this->strings['common.delete'], 'medium');

$edit_url = Form::getSeoUrl(FormList::TIMETRACKERENTRY);
$this->props['delete_url'] = Url::getUrl('TimeTracker.DeleteEntry');
?>

<!--suppress Annotator -->
<script type="text/javascript">

	var site_url = '<?= $this->props['site_url']; ?>',
		icon_edit = '<?= $this->props['icon_edit']; ?>',
		icon_delete = '<?= $this->props['icon_delete']; ?>',
		edit_url = '<?= $edit_url; ?>',
		delete_confirm = <?= json_encode(addslashes($this->strings['timetracker.delete_confirm'])) ?>;
	delete_url = '<?= $this->props['delete_url']; ?>';

	var colIndex = {
		date: 0,
		description: 1,
		time_start: 2,
		time_stop: 3,
		time_diff: 4,
		user: 5,
		operations: 6
	};

	$(document).ready(function () {
		oTable = $('#dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>').DataTable({
			columnDefs: [
				{
					targets: colIndex.date,
					visible: false
				},
				{
					targets: colIndex.operations,
						render: function (data, type, row) {
							var icon1_url = edit_url + '&amp;item_id=' + data;
							var icon1 = '<a class="icon" href="' + icon1_url + '">' + icon_edit + '</a>';
							var icon2_url = delete_url + '&amp;item_id=' + data;
							var icon2 = '<a class="icon" href="' + icon2_url + '" onclick="return confirm(\\'' + delete_confirm + '\\');">' + icon_delete + '</a>';
							return '<span style="white-space:nowrap">' + icon1 + icon2 + '</span>';
						}
				},
				{
					targets: colIndex.time_diff,
					render: function (data, type, row) {
						var dateText = row[colIndex.date] || '';
						var startText = row[colIndex.time_start] || '';
						var endText = row[colIndex.time_stop] || '';
							if (typeof getTimeDiffDisplayText === 'function') {
								return getTimeDiffDisplayText(dateText, startText, endText, {
									hour: <?= json_encode($this->strings['timetracker.duration.hour']) ?>,
									minute: <?= json_encode($this->strings['timetracker.duration.minute']) ?>,
									lessThanOneMinute: <?= json_encode($this->strings['timetracker.duration.less_than_one_minute']) ?>
								});
							}
						return '';
					}
				}
			],
			layout: {
				topStart: 'info',
				topEnd: 'search',
				bottomStart: 'pageLength',
				bottomEnd: 'paging'
			},
			info: true,
			paging: true,
			lengthChange: false,
			pageLength: 100,
			processing: true,
			stateSave: false,
			ajax: {
				url: "<?= Url::getAjaxUrl('TimeTracker.AjaxList'); ?>",
				dataSrc: "data.aaData"
			},
			orderFixed: [[colIndex.date, 'desc']],
			order: [[colIndex.time_start, 'desc']],
			drawCallback: function (settings) {
				var api = this.api();
				var rows = api.rows({page: 'current'}).nodes();
				var last = null;

				api.column(colIndex.date, {page: 'current'}).data().each(function (group, i) {
					if (last !== group) {
						$(rows).eq(i).before(
							'<tr class="group"><td colspan="' + api.columns(':visible').count() + '">' + group + '</td></tr>'
						);
						last = group;
					}
				});
			},
				language: {
					infoFiltered: <?= json_encode($this->strings['datatable.info_filtered_html']) ?>,
					infoEmpty: <?= json_encode($this->strings['datatable.info_empty']) ?>,
					info: <?= json_encode($this->strings['datatable.info_full']) ?>,
					emptyTable: <?= json_encode($this->strings['datatable.empty_table']) ?>,
					paginate: {
						first: <?= json_encode($this->strings['datatable.first']) ?>,
						last: <?= json_encode($this->strings['datatable.last']) ?>,
						next: <?= json_encode($this->strings['datatable.next']) ?>,
						previous: <?= json_encode($this->strings['datatable.previous']) ?>
					},
					search: <?= json_encode($this->strings['datatable.search'] . ':') ?>,
					zeroRecords: <?= json_encode($this->strings['datatable.zero_records']) ?>
				}
			});
	});
	/*
		$(document).ready(function() {
			resizeDataTable("#dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>");
    });

    $(window).resize(function() {
        resizeDataTable("#dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>");
    });
*/
</script>
