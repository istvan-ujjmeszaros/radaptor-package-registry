<?php assert(isset($this) && $this instanceof Template); ?>
<?php
$this->registerLibrary('DATATABLES');
$this->registerLibrary('DATATABLES_FILTER');

$this->props['edit_icon'] = Icons::get(IconNames::EDIT, $this->strings['common.edit'], 'medium');
?>

<div class="subheader">
	<h1><?= e($this->strings['tags.list.title']) ?></h1>
	<br class="cleaner">
</div>

<p>
	<a href="<?= form_url(FormList::TAG); ?>" class="controller-menu"><?= e($this->strings['tags.form.title_create']) ?></a><span></span>
</p>

<table class="display highlight_row commonDataTable" id="dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>">
		<thead>
		<tr>
			<th style="width:100px"><?= e($this->strings['tags.field.context.label']) ?></th>
			<th><?= e($this->strings['tags.field.name.label']) ?></th>
			<th><?= e($this->strings['tags.field.description.label']) ?></th>
			<th style="width:10px"><?= e($this->strings['common.actions']) ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($this->props['tagList'] as $key => $tag): ?>
		<tr>
			<?php if (isset($this->props['tagList'][0]['context'])): ?>
				<td>
					<?=
						match ($tag['context']) {
							'tracker_project' => $this->strings['project.form.name'],
							'tracker_ticket' => $this->strings['ticket.form.name'],
							default => $this->strings['common.unknown'],
						};
				?>
					</td>
			<?php endif; ?>
			<td><?= e($tag['display_name'] ?? $tag['name']); ?></td>
				<td><?= HtmlProcessor::Html2Text($tag['description']); ?></td>
				<td>
					<a href="<?= form_url(FormList::TAG, $tag['id']); ?>"><?= Icons::get(IconNames::EDIT, $this->strings['common.edit'], 'medium'); ?></a>
				</td>
			</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<script type="text/javascript">
	$(document).ready(function () {
		oTable = $('#dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>').dataTable({
			"sDom": 'i<"clear">RClfrtp',
			"bInfo": true,
			"bPaginate": true,
			"sPaginationType": "full_numbers",
			"bProcessing": true,
			"bStateSave": true,
				"oLanguage": {
					"sDom": 'C<"clear">lfrtp',
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
				},
			"sScrollY": "450px"
		});
	});

	$(document).ready(function () {
		resizeDataTable("#dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>");
	});

	$(window).resize(function () {
		resizeDataTable("#dataTable-<?= $this->getWidgetConnection()->getConnectionId(); ?>");
	});
</script>
