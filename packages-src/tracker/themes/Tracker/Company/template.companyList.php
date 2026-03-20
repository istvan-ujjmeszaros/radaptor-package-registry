<?php assert(isset($this) && $this instanceof Template); ?>
<?php
$this->registerLibrary('DATATABLES');
$this->registerLibrary('DATATABLES_FILTER');
?>
<div class="subheader">
	<h1><?= e($this->strings['company.list.title']) ?></h1>
	<div class="tabs">
		<a class="tab<?php if (Url::pathEqualsTo(Url::getSeoUrl(ResourceTypeWebpage::findWebpageIdWithWidget(WidgetList::COMPANYLIST)))) {
			echo ' active';
		} ?>" href="<?= widget_url(WidgetList::COMPANYLIST); ?>"><?= e($this->strings['company.list.title']) ?></a>
		<a class="tab<?php if (Url::pathEqualsTo(Url::getSeoUrl(ResourceTypeWebpage::findWebpageIdWithWidget(WidgetList::CONTACTPERSONLIST)))) {
			echo ' active';
		} ?>" href="<?= widget_url(WidgetList::CONTACTPERSONLIST); ?>"><?= e($this->strings['contact.list.title']) ?></a>
	</div>
	<br class="cleaner">
</div>
<p>
	<a href="<?= form_url(FormList::COMPANY); ?>" class="controller-menu"><?= e($this->strings['company.list.new']) ?></a><span></span>
</p>


<table class="display highlight_row commonDataTable" id="dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>">
	<thead>
	<tr>
		<th style="width:20px"><?= e($this->strings['common.id']) ?></th>
		<th style="width:80px"><?= e($this->strings['company.field.shortname.label']) ?></th>
		<th><?= e($this->strings['company.col.name']) ?></th>
		<th style="width:70px"><?= e($this->strings['common.actions']) ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($this->props['companyList'] as $key => $company): ?>
		<tr>
			<td>
				<a href="<?= widget_url(WidgetList::COMPANYDESCRIPTION) . "{$company['id']}/"; ?>"><?php printf('%04d', $company['id']); ?></a>
			</td>
			<td><?= $company['shortname']; ?></td>
			<td><?= $company['name']; ?></td>
			<td>
				<a href="<?= form_url(FormList::COMPANY, $company['id']); ?>"><?= Icons::get(IconNames::EDIT, $this->strings['common.edit'], 'medium'); ?></a>
				<a href="<?= widget_url(WidgetList::COMPANYDESCRIPTION) . "{$company['id']}/"; ?>"><?= Icons::get(IconNames::DATASHEET, $this->strings['record_action.datasheet'], 'medium'); ?></a>
				<?php /* Legacy contact list shortcut intentionally removed from the SDUI path. */ ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<script type="text/javascript">

	$(document).ready(function () {
		oTable = $('#dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>').dataTable({
			"sDom": 'i<"clear">RClfrtip',
			"bInfo": true,
			"bPaginate": false,
			/*		"sPaginationType": "full_numbers",*/
			/*		"bProcessing": true,*/
			"bStateSave": true,
			"oLanguage": {
				"sDom": 'C<"clear">lfrtip',
				"sInfoFiltered": <?= json_encode($this->strings['datatable.info_filtered_html']); ?>,
				"sInfoEmpty": <?= json_encode($this->strings['datatable.info_empty']); ?>,
				"sInfo": <?= json_encode($this->strings['datatable.info_full']); ?>,
				"sEmptyTable": <?= json_encode($this->strings['datatable.empty_table']); ?>,
				"oPaginate": {
					"sFirst": <?= json_encode($this->strings['datatable.first']); ?>,
					"sLast": <?= json_encode($this->strings['datatable.last']); ?>,
					"sNext": <?= json_encode($this->strings['datatable.next']); ?>,
					"sPrevious": <?= json_encode($this->strings['datatable.previous']); ?>
				},
				"sSearch": <?= json_encode($this->strings['datatable.search'] . ':'); ?>,
				"sZeroRecords": <?= json_encode($this->strings['datatable.zero_records']); ?>
			},

			"oColVis": {
				"buttonText": <?= json_encode($this->strings['datatable.column_visibility']); ?>
			}


		});
	});

</script>
