<?php assert(isset($this) && $this instanceof Template); ?>
<?php
$this->registerLibrary('QTIP');
$this->registerLibrary('JQUERY_COLOR');
?>
<div class="subheader">
	<h1><?= e($this->strings['ticket.description.heading']) ?></h1>
	<br class="cleaner">
</div>
<p>
	<a href="<?= form_url(FormList::TICKET, $this->props['ticketData']['id']); ?>" class="controller-menu"><?= e($this->strings['ticket.form.title_edit']) ?></a><span></span>
</p>
<table class="descriptionTable">
	<tr>
		<td colspan="4"><h1>[<?= $this->props['ticketData']['state']; ?>
				] <?= $this->props['ticketData']['title'] == '' ? '<i>' . e($this->strings['ticket.no_subject']) . '</i>' : htmlspecialchars($this->props['ticketData']['title'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE); ?></h1>
		</td>
	</tr>
	<tr>
		<td style="width:20%" class="label"><?= e($this->strings['ticket.description.id_label']) ?>:</td>
		<td>#<?= $this->props['ticketData']['id']; ?></td>
		<td style="width:20%" class="label"><?= e($this->strings['record_meta.created_by']) ?>:</td>
		<td style="width:20%">
			<?php if (count($this->props['modificationsList']) > 0): ?>
				<?php $createdAtIso = DatetimeHelper::isoFromDatetime($this->props['modificationsList'][0]['meta']['triggered_datetime']) ?? ''; ?>
				<?= User::getUsername($this->props['modificationsList'][0]['meta']['user_id']); ?>,&nbsp;
				<span class="js-timeago"
					data-utc="<?= htmlspecialchars($createdAtIso, ENT_QUOTES | ENT_SUBSTITUTE); ?>"
					title="<?= htmlspecialchars($createdAtIso, ENT_QUOTES | ENT_SUBSTITUTE); ?>"
				><?= htmlspecialchars((string) $this->props['modificationsList'][0]['meta']['triggered_datetime'], ENT_QUOTES | ENT_SUBSTITUTE); ?></span>
			<?php else: ?>
				<?= e($this->strings['common.no_data']) ?>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td class="label"><?= e($this->strings['ticket.field.assignee.label']) ?>:</td>
		<td>
			<?php if ($this->props['ticketData']['assigned_user_id']): ?>
				<a href="<?= widget_url(WidgetList::TICKETLIST) . $this->props['ticketData']['assigned_user_id'] . '/'; ?>"><?php User::getUsername($this->props['ticketData']['assigned_user_id']); ?></a>
			<?php else: ?>
				<?= e($this->strings['ticket.description.no_assignee']) ?>
			<?php endif; ?>
		</td>
		<td class="label"><?= e($this->strings['record_meta.last_modified']) ?>:</td>
		<td>
			<?php if (count($this->props['modificationsList']) < 2): ?>
				<?= e($this->strings['common.never']) ?>
			<?php else: ?>
				<?php
				$lastModification = $this->props['modificationsList'][count($this->props['modificationsList']) - 1];
				$lastIso = DatetimeHelper::isoFromDatetime($lastModification['meta']['triggered_datetime']) ?? '';
				?>
				<?= User::getUsername($lastModification['meta']['user_id']); ?>,&nbsp;<span class="js-timeago"
					data-utc="<?= htmlspecialchars($lastIso, ENT_QUOTES | ENT_SUBSTITUTE); ?>"
					title="<?= htmlspecialchars($lastIso, ENT_QUOTES | ENT_SUBSTITUTE); ?>"
				><?= htmlspecialchars((string) $lastModification['meta']['triggered_datetime'], ENT_QUOTES | ENT_SUBSTITUTE); ?></span>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td class="label"><?= e($this->strings['ticket.field.contactperson.label']) ?>:</td>
		<td colspan="3">
			<a href="<?= widget_url(WidgetList::TICKETLIST) . 'kapcsolattarto--' . e($this->props['ticketData']['contactperson']) . '/'; ?>"><?= $this->props['ticketData']['contactperson']; ?></a>
		</td>
	</tr>
	<tr>
		<td class="label"><?= e($this->strings['ticket.field.project.label']) ?>:</td>
		<td colspan="3">
			<a href="<?= widget_url(WidgetList::TICKETLIST) . 'projekt--' . e($this->props['ticketData']['project_name']) . '/'; ?>"><?= $this->props['ticketData']['project_name']; ?></a>
		</td>
	</tr>
	<tr>
		<td class="label"><?= e($this->strings['ticket.field.start_date.label']) ?>:</td>
		<td colspan="3"><?= $this->props['ticketData']['start_date']; ?></td>
	</tr>
	<tr>
		<td class="label"><?= e($this->strings['ticket.field.end_date.label']) ?>:</td>
		<td colspan="3"><?= $this->props['ticketData']['end_date']; ?></td>
	</tr>
	<tr>
		<td class="label"><?= e($this->strings['ticket.field.type.label']) ?>:</td>
		<td colspan="3">
			<a href="<?= widget_url(WidgetList::TICKETLIST) . 'tipus--' . e($this->props['ticketData']['type']); ?>"><?= $this->props['ticketData']['type']; ?></a>
		</td>
	</tr>
	<tr>
		<td class="label"><?= e($this->strings['ticket.field.priority.label']) ?>:</td>
		<td colspan="3">
			<a href="<?= widget_url(WidgetList::TICKETLIST) . 'prioritas--' . e($this->props['ticketData']['priority']); ?>"><?= $this->props['ticketData']['priority']; ?></a>
		</td>
	</tr>
	<tr>
		<td class="label"><?= e($this->strings['ticket.field.tags.label']) ?>:</td>
		<td colspan="3"><?= Url::getAnchorTextWithExtraparamList("cimke", $this->props['ticketData']['tags'], ', ', widget_url(WidgetList::TICKETLIST), 'tag-ticket'); ?></td>
	</tr>
	<tr>
		<td colspan="4" class="label"><h1><?= e($this->strings['ticket.field.description.label']) ?></h1></td>
	</tr>
	<tr>
		<td colspan="4" class="description"><?= $this->props['ticketData']['description']; ?></td>
	</tr>
</table>

<?= $this->fetchSlot('history'); ?>

<script type="text/javascript">
	$(window.location.hash).animate({'backgroundColor': "#666"}, 200).animate({'backgroundColor': "#eee"}, 1000).css('border', '1px dashed black');

	$('.tag-ticket').each(function () {
		var item_id = encodeURIComponent($(this).text());

		$(this).qtip(
			{
				position:
					{
						at: 'top right', // Position the tooltip above the link
						my: 'bottom left',
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

	(function renderTicketDescriptionTimeAgo() {
		if (!window.radaptorDateTime || typeof window.radaptorDateTime.timeAgoUtc !== 'function') {
			return;
		}

		document.querySelectorAll('.js-timeago').forEach(function (el) {
			const utcValue = el.getAttribute('data-utc') || '';
			const relative = window.radaptorDateTime.timeAgoUtc(utcValue);
			if (relative) {
				el.textContent = relative;
			}
		});
	})();
</script>
