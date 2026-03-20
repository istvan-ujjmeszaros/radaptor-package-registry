<?php assert(isset($this) && $this instanceof Template); ?>
<?php
$this->registerLibrary('JQUERY');
$this->registerLibrary('COMMON');
$this->registerLibrary('DATATABLES');
$this->registerLibrary('DATATABLES_FILTER');
$this->registerLibrary('QTIP');
?>
<div class="subheader">
	<h1><?= e($this->strings['project.list.title']) ?></h1>
	<div class="tabs">
		<a class="tab<?php if (Url::pathEqualsTo($this->getPagedata('path'))) {
			echo ' active';
		} ?>" href="<?= $this->getPagedata('path'); ?>"><?= e($this->strings['common.all']) ?></a>
		<?php foreach ($this->props['states'] as $state): ?>
			<a
				class="tab<?php if (Url::pathEqualsTo($this->getPagedata('path') . $state['label'] . "/")) {
					echo ' active';
				} ?>"
				href="<?= $this->getPagedata('path') . $state['label'] . "/"; ?>"
			>
				<?= $state['label']; ?>
			</a>
		<?php endforeach; ?>
	</div>
	<br class="cleaner">
</div>
<p>
	<a href="<?= form_url(FormList::PROJECT); ?>" class="controller-menu"><?= e($this->strings['project.form.title_create']) ?></a><span></span>
	<a href="<?= widget_url(WidgetList::PROJECTSTATELIST); ?>" class="controller-menu"><?= e($this->strings['project.state.form.name']) ?></a><span></span>
</p>


<table class="display highlight_row commonDataTable" id="dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>">
	<thead>
		<tr>
			<th style="width:50px"><?= e($this->strings['common.id']) ?></th>
			<th style="width:100px"><?= e($this->strings['project.field.name.label']) ?></th>
			<th style="width:150px"><?= e($this->strings['project.field.state.label']) ?></th>
			<th style="width:170px"><?= e($this->strings['project.field.company.label']) ?></th>
			<th style="width:50px"><?= e($this->strings['company.field.shortname.label']) ?></th>
			<th style="width:auto"><?= e($this->strings['ticket.field.tags.label']) ?></th>
			<th style="width:auto"><?= e($this->strings['project.list.ticket_tags']) ?></th>
			<th style="width:50px"><?= e($this->strings['project.list.total_tickets']) ?></th>
			<th style="width:50px"><?= e($this->strings['project.list.open_tickets']) ?></th>
			<th style="width:10px"><?= e($this->strings['common.actions']) ?></th>
		</tr>
	</thead>
		<tbody>
		<tr>
			<td colspan="10" class="dataTables_empty"><?= e($this->strings['datatable.loading_records']) ?></td>
		</tr>
		</tbody>
</table>

<?php
$this->props['serialized_filtered_extraparams'] = urlencode(serialize($this->props['filtered_extraparams']));
$this->props['serialized_extraparams'] = urlencode(serialize($this->props['extraparams']));

$this->props['site_url'] = Url::getCurrentHost();
$this->props['icon_error'] = Icons::get(IconNames::STATUS_ERROR);
$this->props['icon_ok'] = Icons::get(IconNames::STATUS_OK);
$this->props['icon_edit'] = Icons::get(IconNames::EDIT, $this->strings['common.edit'], 'medium');
$this->props['icon_info'] = Icons::get(IconNames::DATASHEET, $this->strings['record_action.datasheet'], 'medium');
$this->props['icon_versions'] = Icons::get(IconNames::VERSIONS, $this->strings['record_action.versions'], 'medium');

$this->props['edit_url'] = Form::getSeoUrl(FormList::PROJECT);
$this->props['versions_url'] = Form::getSeoUrl(FormList::PROJECT);
?>

<script type="text/javascript">

	var site_url = '<?= $this->props['site_url']; ?>',
		icon_error = '<?= $this->props['icon_error']; ?>',
		icon_ok = '<?= $this->props['icon_ok']; ?>',
		icon_edit = '<?= $this->props['icon_edit']; ?>',
		icon_info = '<?= $this->props['icon_info']; ?>',
		icon_versions = '<?= $this->props['icon_versions']; ?>',
		edit_url = '<?= $this->props['edit_url']; ?>';
	versions_url = '<?= $this->props['versions_url']; ?>';

	var colIndex = {
		id: 0,
		name: 1,
		project_state: 2,
		company_name: 3,
		shortname: 4,
		tags: 5,
		tags_tickets: 6,
		all_tickets: 7,
		open_tickets: 8,
		operations: 9
	};

	var basePath = '<?= $this->getPagedata('path'); ?>';
	var ticketListUrl = '<?= Url::getSeoUrl(ResourceTypeWebpage::findWebpageIdWithWidget(WidgetList::TICKETLIST)); ?>';

	$(document).ready(function () {
		oTable = $('#dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>').DataTable({
			columnDefs: [
				{
					targets: colIndex.name,
					render: function (data, type, row) {
						return '<a href="' + basePath + 'nev--' + data + '/">' + data + '</a>';
					}
				},
				{
					targets: colIndex.project_state,
					render: function (data, type, row) {
						return '<a href="' + basePath + 'allapot--' + data + '/">' + data + '</a>';
					}
				},
				{
					targets: colIndex.company_name,
					render: function (data, type, row) {
						return '<a href="' + basePath + 'ceg--' + data + '/">' + data + '</a>';
					}
				},
				{
					targets: colIndex.shortname,
					render: function (data, type, row) {
						return '<a href="' + basePath + 'rovidnev--' + data + '/">' + data + '</a>';
					}
				},
				{
					targets: colIndex.tags,
					render: function (data, type, row) {
						var tags = data.split(', ');
						return tags.map(function (tag, i) {
							return '<a id="tag_' + i + '" href="' + basePath + 'cimke--' + tag + '/">' + tag + '</a>';
						}).join(', ');
					}
				},
				{
					targets: colIndex.tags_tickets,
					render: function (data, type, row) {
						var tags = data.split(', ');
						return tags.map(function (tag, i) {
							return '<a id="tag_ticket_' + (i + 1) + '" href="' + basePath + 'cimke--' + tag + '/">' + tag + '</a>';
						}).join(', ');
					}
				},
				{
					targets: colIndex.all_tickets,
					render: function (data, type, row) {
						var name = $('<span>' + row[colIndex.name] + '</span>').text();
						return '<a href="' + ticketListUrl + 'projekt--' + name + '/">' + data + '</a>';
					}
				},
				{
					targets: colIndex.open_tickets,
					render: function (data, type, row) {
						var name = $('<span>' + row[colIndex.name] + '</span>').text();
						return '<a href="' + ticketListUrl + 'nyitott/projekt--' + name + '/">' + data + '</a>';
					}
				},
				{
					targets: colIndex.operations,
					render: function (data, type, row) {
						var icon1 = '<a class="icon" href="' + edit_url + '&amp;item_id=' + data + '">' + icon_edit + '</a>';
						var icon2 = '<a class="icon" href="' + versions_url + '&amp;item_id=' + data + '">' + icon_versions + '</a>';
						return '<span style="white-space:nowrap">' + icon1 + icon2 + '</span>';
					}
				}
			],
			info: true,
			paging: true,
			processing: true,
			stateSave: true,
			ajax: {
				url: getCurrentUrlPath() + "?context=projects&event=projectList&filtered_extraparams=<?= $this->props['serialized_filtered_extraparams']; ?>&extraparams=<?= $this->props['serialized_extraparams']; ?>",
				dataSrc: "data.aaData"
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
				},
			scrollY: "450px",
			scrollX: true
		});
	});

	$(document).ready(function () {
		resizeDataTable("#dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>");
	});

	$(window).resize(function () {
		resizeDataTable("#dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>");
	});

</script>
