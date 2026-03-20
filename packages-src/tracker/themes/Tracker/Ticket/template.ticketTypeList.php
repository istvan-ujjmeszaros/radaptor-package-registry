<?php assert(isset($this) && $this instanceof Template); ?>
<?php
$this->registerLibrary('DATATABLES');
$this->registerLibrary('DATATABLES_FILTER');
?>
<div class="subheader">
	<h1><?= e($this->strings['ticket.widget.type_list.name']) ?></h1>
	<br class="cleaner">
</div>
<p>
	<a href="<?= form_url(FormList::TICKETTYPE); ?>" class="controller-menu"><?= e($this->strings['ticket.type.form.title_create']) ?></a><span></span>
	<a href="<?= widget_url(WidgetList::TICKETLIST); ?>" class="controller-menu"><?= e($this->strings['ticket.widget.list.name']) ?></a><span></span>
</p>


<table class="display highlight_row commonDataTable" id="dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>">
	<thead>
		<tr>
			<th><?= e($this->strings['ticket.type.field.name.label']) ?></th>
			<th style="width:10px"><?= e($this->strings['common.actions']) ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($this->props['issueTypeList'] as $key => $issueType): ?>
		<tr>
				<td><?= $issueType['name']; ?></td>
				<td>
					<a href="<?= form_url(FormList::TICKETTYPE, $issueType['id']); ?>"><?= Icons::get(IconNames::EDIT, $this->strings['common.edit'], 'medium'); ?></a>
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
					"sInfoFiltered": <?= json_encode($this->strings['datatable.info_filtered_html']) ?>,
					"sInfoEmpty": <?= json_encode($this->strings['datatable.info_empty']) ?>,
					"sInfo": <?= json_encode($this->strings['datatable.info_full']) ?>,
					"sEmptyTable": <?= json_encode($this->strings['datatable.empty_table']) ?>,
					"oPaginate": {
						"sFirst": <?= json_encode($this->strings['datatable.first']) ?>,
						"sLast": <?= json_encode($this->strings['datatable.last']) ?>,
						"sNext": <?= json_encode($this->strings['datatable.next']) ?>,
						"sPrevious": <?= json_encode($this->strings['datatable.previous']) ?>
					},
					"sSearch": <?= json_encode($this->strings['datatable.search'] . ':') ?>,
					"sZeroRecords": <?= json_encode($this->strings['datatable.zero_records']) ?>
				},

				"oColVis": {
					"buttonText": <?= json_encode($this->strings['datatable.displayed_columns']) ?>
				}


		});
	});

</script>
