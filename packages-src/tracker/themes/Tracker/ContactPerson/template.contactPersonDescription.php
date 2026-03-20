<?php assert(isset($this) && $this instanceof Template); ?>
<?php
$this->registerLibrary('QTIP');
$this->registerLibrary('JQUERY_COLOR');
?>
<div class="subheader">
	<h1><?= e($this->strings['contact.description.widget_name']) ?></h1>
	<br class="cleaner">
</div>
<p>
	<a href="<?= widget_url(WidgetList::CONTACTPERSONLIST); ?>" class="controller-menu"><?= e($this->strings['contact.description.back_to_list']) ?></a><span></span>
	<a href="<?= form_url(FormList::CONTACTPERSON, $this->props['contactPersonData']['id']); ?>" class="controller-menu"><?= e($this->strings['common.edit']) ?></a><span></span>
</p>
<table class="descriptionTable">
	<tr>
		<td colspan="4"><h1><?= $this->props['contactPersonData']['name']; ?></h1></td>
	</tr>
	<tr>
		<td style="width:10%" class="label"><?= e($this->strings['common.id']) ?>:</td>
		<td><?= $this->props['contactPersonData']['id']; ?></td>
		<td style="width:20%" class="label"><?= e($this->strings['record_meta.created_by']) ?>:</td>
		<td style="width:20%">
			<?php if (count($this->props['modificationsList']) > 0): ?>
				<?php $createdAtIso = DatetimeHelper::isoFromDatetime($this->props['modificationsList'][0]['triggered_datetime']) ?? ''; ?>
				<?= User::getUsername($this->props['modificationsList'][0]['user_id']); ?>,&nbsp;
				<span class="js-timeago"
					data-utc="<?= htmlspecialchars($createdAtIso, ENT_QUOTES | ENT_SUBSTITUTE); ?>"
					title="<?= htmlspecialchars($createdAtIso, ENT_QUOTES | ENT_SUBSTITUTE); ?>"
				><?= htmlspecialchars((string) $this->props['modificationsList'][0]['triggered_datetime'], ENT_QUOTES | ENT_SUBSTITUTE); ?></span>
			<?php else: ?>
				<?= e($this->strings['common.no_data']) ?>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td><?= e($this->strings['contact.col.company']) ?>:</td>
		<td>
			<a href="<?= widget_url(WidgetList::COMPANYDESCRIPTION) . urlencode((string) $this->props['contactPersonData']['connected_company_id']); ?>/"><?= $this->props['contactPersonData']['company']; ?></a>
		</td>
		<td class="label"><?= e($this->strings['record_meta.last_modified']) ?>:</td>
		<td>
			<?php if (count($this->props['modificationsList']) < 2): ?>
				<?= e($this->strings['common.never']) ?>
			<?php else: ?>
				<?php
				$lastModification = $this->props['modificationsList'][count($this->props['modificationsList']) - 1];
				$lastIso = DatetimeHelper::isoFromDatetime($lastModification['triggered_datetime']) ?? '';
				?>
				<?= User::getUsername($lastModification['user_id']); ?>,&nbsp;<span class="js-timeago"
					data-utc="<?= htmlspecialchars($lastIso, ENT_QUOTES | ENT_SUBSTITUTE); ?>"
					title="<?= htmlspecialchars($lastIso, ENT_QUOTES | ENT_SUBSTITUTE); ?>"
				><?= htmlspecialchars((string) $lastModification['triggered_datetime'], ENT_QUOTES | ENT_SUBSTITUTE); ?></span>
			<?php endif; ?>
		</td>
	</tr>
</table>

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

	$('.tag-issue').each(function () {
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
								url: getCurrentUrlPath() + '?context=tags&event=tagDescriptionIssue&item_id=' + item_id
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

	(function renderContactPersonTimeAgo() {
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
