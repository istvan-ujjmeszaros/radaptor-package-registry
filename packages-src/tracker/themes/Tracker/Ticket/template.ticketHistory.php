<?php assert(isset($this) && $this instanceof Template); ?>
<div class="subheader">
	<h2><?= e($this->strings['ticket.history.title']) ?></h2>
	<br class="cleaner">
</div>

<?php foreach ($this->props['modificationsList'] as $key => $modification): ?>
	<?php if ($key == 0) {
		continue;
	} ?>

	<div class="change" id="comment-<?= $key; ?>">
		<h1><?= User::getUsername($modification['meta']['user_id']); ?>,
			<span class="js-timeago" data-utc="<?= htmlspecialchars((string) (DatetimeHelper::isoFromDatetime($modification['meta']['triggered_datetime']) ?? ''), ENT_QUOTES | ENT_SUBSTITUTE); ?>" title="<?= htmlspecialchars((string) (DatetimeHelper::isoFromDatetime($modification['meta']['triggered_datetime']) ?? ''), ENT_QUOTES | ENT_SUBSTITUTE); ?>">
				<?= htmlspecialchars((string) $modification['meta']['triggered_datetime'], ENT_QUOTES | ENT_SUBSTITUTE); ?>
			</span><a href="<?= Url::getCurrentUrl(); ?>#comment-<?= $modification['meta']['comment_id']; ?>" class="comment-id"><?= e($this->strings['ticket.history.comment_prefix']) ?>: <?= $modification['meta']['comment_id']; ?></a>
		</h1>

		<?php if ($modification['meta']['has_modifies']): ?>

			<ul class="changelist">
				<?php if (isset($modification['modify_data']['modifications'])): ?>
					<?= $this->fetchSlot('changes_' . $key); ?>
				<?php endif; ?>

				<?php if ($modification['modify_data']['deleted_tags']): ?>
					<li><b><?= e($this->strings['ticket.history.tags_removed']) ?></b>:
						<i class="strike"><?= Url::getAnchorTextWithExtraparamList("cimke", $modification['modify_data']['deleted_tags'], ', ', Url::getCurrentHost(), 'tag-ticket'); ?></i>
					</li>
				<?php endif; ?>

				<?php if ($modification['modify_data']['added_tags']): ?>
					<li><b><?= e($this->strings['ticket.history.tags_added']) ?></b>:
						<i><?= Url::getAnchorTextWithExtraparamList("cimke", $modification['modify_data']['added_tags'], ', ', Url::getCurrentHost(), 'tag-ticket'); ?></i>
					</li>
				<?php endif; ?>
			</ul>

		<?php endif; ?>

		<?php if (isset($modification['modify_data']['comments'][0])): ?>
			<div class="comment">
				<?= $modification['modify_data']['comments'][0]['last_data']['comment']; ?>
			</div>
		<?php endif; ?>

	</div>
<?php endforeach; ?>

<script type="text/javascript">
	(function initTicketHistoryTimeAgo() {
		function renderTimeAgo() {
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
		}

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', renderTimeAgo, {once: true});
		} else {
			renderTimeAgo();
		}

		document.addEventListener('radaptorDateTimeReady', renderTimeAgo);
	})();
</script>
