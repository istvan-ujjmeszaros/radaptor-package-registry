<?php assert(isset($this) && $this instanceof Template); ?>
<?php
$this->registerLibrary('JQUERY');
$this->registerLibrary('COMMON');
$this->registerLibrary('DATATABLES');
$this->registerLibrary('DATATABLES_FILTER');
$this->registerLibrary('QTIP');
?>
<div class="subheader">
	<h1><?= e($this->strings['ticket.widget.list.name']) ?></h1>
	<div class="tabs">
		<a class="tab<?php if (Url::pathEqualsTo(Url::getSeoUrl(ResourceTypeWebpage::findWebpageIdWithWidget(WidgetList::TICKETLIST)))) {
			echo ' active';
		} ?>" href="<?= widget_url(WidgetList::TICKETLIST); ?>"><?= e($this->strings['common.all']) ?></a>
		<a class="tab<?php if (Url::pathEqualsTo(Url::getSeoUrl(ResourceTypeWebpage::findWebpageIdWithWidget(WidgetList::TICKETLIST)) . 'nyitott/')) {
			echo ' active';
		} ?>" href="<?= widget_url(WidgetList::TICKETLIST); ?>nyitott/"><?= e($this->strings['common.open']) ?></a>
	</div>
	<br class="cleaner">
</div>
<p>
	<a href="<?= form_url(FormList::TICKET); ?>" class="controller-menu"><?= e($this->strings['ticket.form.title_create']) ?></a><span></span>
</p>


<table class="display highlight_row commonDataTable" id="dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>">
	<thead>
		<tr>
			<th width="1px" style="overflow:visible;"><?= e($this->strings['common.id']) ?></th>
			<th width="1px" style="overflow:visible;"><?= e($this->strings['ticket.field.state.label']) ?></th>
			<th width="1px" style="overflow:visible;"><?= e($this->strings['ticket.field.priority.label']) ?></th>
			<th width="1px" style="overflow:visible;"><?= e($this->strings['ticket.field.start_date.label']) ?></th>
			<th width="1px" style="overflow:visible;"><?= e($this->strings['ticket.field.end_date.label']) ?></th>
			<th width="90px" style="overflow:visible;"><?= e($this->strings['ticket.field.type.label']) ?></th>
			<th width="100px" style="overflow:visible;"><?= e($this->strings['ticket.field.project.label']) ?></th>
			<th width="150px" style="overflow:hidden;"><?= e($this->strings['ticket.field.subject.label']) ?></th>
			<th width="50px" style="overflow:visible;"><?= e($this->strings['ticket.field.contactperson.label']) ?></th>
			<th width="50px" style="overflow:visible;"><?= e($this->strings['ticket.field.assignee.label']) ?></th>
			<th style="overflow:visible;"><?= e($this->strings['ticket.field.tags.label']) ?></th>
			<th width="10px" style="overflow:visible;"><?= e($this->strings['common.actions']) ?></th>
		</tr>
		</thead>
</table>

<?php
$this->props['serialized_filtered_extraparams'] = urlencode(serialize($this->props['filtered_extraparams']));
$this->props['serialized_extraparams'] = urlencode(serialize($this->props['extraparams']));

$this->props['site_url'] = Url::getCurrentHost();
$this->props['icon_error'] = Icons::get(IconNames::STATUS_ERROR);
$this->props['icon_ok'] = Icons::get(IconNames::STATUS_OK);
$this->props['icon_edit'] = Icons::get(IconNames::EDIT, $this->strings['common.edit'], 'medium');
$this->props['icon_info'] = Icons::get(IconNames::DATASHEET, $this->strings['record_action.datasheet'], 'medium');

$edit_url = Form::getSeoUrl(FormList::TICKET);
?>
<script type="text/javascript">

	function registerToolTipsTicketDescriptions() {
		$('[id^=description_]').each(function () {
			var item_id = $(this).attr('id');

			$(this).qtip(
				{
					position:
						{
							at: 'top left',
							my: 'bottom right',
							viewport: $(window),
							effect: false
						},
					content:
						{
							text: '<?= Config::PATH_AJAX_LOADER_HTML->value(); ?>',
							ajax:
								{
									url: getCurrentUrlPath() + '?context=tickets&event=ticketDescription&item_id=' + item_id,
									dataType: 'json',
									success: function (data, status) {
										this.set('content.title.text', data.title);
										this.set('content.text', data.text);
									}
								}
						},
					style: {
						classes: 'ui-tooltip-shadow'
					},
					show:
						{
							solo: true
						}
				});
		});
	}

	function registerToolTipsTicketTags() {
		$('[id^=tag_ticket_]').each(function () {
			var item_id = encodeURI($(this).text());

			$(this).qtip(
				{
					position:
						{
							at: 'top left',
							my: 'bottom right',
							viewport: $(window),
							effect: false
						},
					content:
						{
							text: '<?= Config::PATH_AJAX_LOADER_HTML->value(); ?>',
							ajax:
								{
									url: getCurrentUrlPath() + '?context=tags&event=tagDescriptionTicket&item_id=' + item_id
								}
						},
					style: {
						classes: 'ui-tooltip-shadow'
					},
					show:
						{
							solo: true
						}
				});
		});
	}

	var site_url = '<?= $this->props['site_url']; ?>',
		icon_error = '<?= $this->props['icon_error']; ?>',
		icon_ok = '<?= $this->props['icon_ok']; ?>',
		icon_edit = '<?= $this->props['icon_edit']; ?>',
		icon_info = '<?= $this->props['icon_info']; ?>',
		edit_url = '<?= $edit_url; ?>';

	var colIndex = {
		id: 0,
		state: 1,
		priority: 2,
		start_date: 3,
		end_date: 4,
		type: 5,
		project_name: 6,
		title: 7,
		contactperson: 8,
		assigned_username: 9,
		tags: 10,
		operations: 11
	};

	var basePath = '<?= $this->getPagedata('path'); ?>';
	var ticketDescUrl = '<?= Url::getSeoUrl(ResourceTypeWebpage::findWebpageIdWithWidget(WidgetList::TICKETDESCRIPTION)); ?>';

	$(document).ready(function () {
		oTable = $('#dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>').DataTable({
			columnDefs: [
				{
					targets: colIndex.id,
					orderSequence: ["desc", "asc"],
					render: function (data, type, row) {
						return '<a href="' + ticketDescUrl + parseInt(data, 10) + '/">' + data + '</a>';
					}
				},
				{
					targets: colIndex.state,
					render: function (data, type, row) {
						return '<a href="' + basePath + 'allapot--' + data + '/">' + data + '</a>';
					}
				},
				{
					targets: colIndex.priority,
					render: function (data, type, row) {
						var splitted = data.split("\0");
						return '<a href="' + basePath + 'prioritas--' + splitted[1] + '/">' + splitted[0] + splitted[1] + '</a>';
					}
				},
				{
					targets: colIndex.start_date,
					render: function (data) {
						return '<span style="white-space:nowrap;">' + data + '</span>';
					}
				},
				{
					targets: colIndex.end_date,
					render: function (data) {
						return '<span style="white-space:nowrap;">' + data + '</span>';
					}
				},
				{
					targets: colIndex.type,
					render: function (data, type, row) {
						return '<a href="' + basePath + 'tipus--' + data + '/">' + data + '</a>';
					}
				},
				{
					targets: colIndex.project_name,
					render: function (data, type, row) {
						return '<a href="' + basePath + 'projekt--' + data + '/">' + data + '</a>';
					}
				},
				{
					targets: colIndex.title,
					orderSequence: ["desc", "asc"],
					render: function (data, type, row) {
						var idText = $('<span>' + row[colIndex.id] + '</span>').text();
						return '<a href="' + ticketDescUrl + parseInt(idText, 10) + '/">' + data + '</a>';
					}
				},
				{
					targets: colIndex.contactperson,
					render: function (data, type, row) {
						return '<a href="' + basePath + 'kapcsolattarto--' + data + '/" style="white-space:nowrap;">' + data + '</a>';
					}
				},
				{
					targets: colIndex.assigned_username,
					render: function (data, type, row) {
						return '<a href="' + basePath + 'felelos--' + data + '/" style="white-space:nowrap;">' + data + '</a>';
					}
				},
				{
					targets: colIndex.tags,
					render: function (data, type, row) {
						var tags = data.split(', ');
						return tags.map(function (tag, i) {
							return '<a id="tag_issue_' + (i + 1) + '" href="' + basePath + 'cimke--' + tag + '/">' + tag + '</a>';
						}).join(', ');
					}
				},
				{
					targets: colIndex.operations,
					render: function (data, type, row) {
						var icon1 = '<a class="icon" href="' + edit_url + '&amp;item_id=' + data + '">' + icon_edit + '</a>';
						var icon2 = '<a class="icon" id="description_' + data + '" href="' + ticketDescUrl + data + '/">' + icon_info + '</a>';
						return '<span style="white-space:nowrap">' + icon1 + icon2 + '</span>';
					}
				}
			],
			info: true,
			paging: true,
			processing: true,
			stateSave: true,
			ajax: {
				url: getCurrentUrlPath() + "?context=tickets&event=ticketList&filtered_extraparams=<?= $this->props['serialized_filtered_extraparams']; ?>&extraparams=<?= $this->props['serialized_extraparams']; ?>",
				dataSrc: "data.aaData"
			},
			drawCallback: function () {
				registerToolTipsTicketDescriptions();
				registerToolTipsTicketTags();
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
</script>
