<?php assert(isset($this) && $this instanceof Template); ?>
<?php $this->registerLibrary('__COMMON_ADMIN'); ?>
<?php $this->registerLibrary('STIMULUS_LOADER'); ?>
<?php
$lang = (string)($this->props['lang'] ?? substr(Kernel::getLocale(), 0, 2));
$site_name = (string)($this->props['site_name'] ?? Config::APP_SITE_NAME->value());
$administration_label = (string)($this->props['administration_label'] ?? '');
$document_title = (string)($this->props['document_title'] ?? ($administration_label !== '' ? $administration_label . ' - ' . $site_name : $site_name));
?>
<!DOCTYPE html>
<html lang="<?= e($lang) ?>">
<head>
<!-- TRACKER THEME OVERRIDE -->
	<meta charset="utf-8">
	<title><?= e($document_title) ?></title>
	<meta name="robots" content="NOINDEX, NOFOLLOW">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<meta name="viewport" content="width=device-width">
	<?= $this->getRenderer()->getLibraryDebugInfo(); ?>
	<?= $this->getRenderer()->getCss(); ?>
	<?= $this->getRenderer()->getJsTop(); ?>

</head>
<body>
<?= $this->getRenderer()->fetchInnerHtml(); ?>
<div id="container">
	<!-- TOPMENU -->
	<div id="topmenu-container">
		<div id="topmenu">
			<?= $this->fetchSlot('top_menu_admin'); ?>
		</div>
	</div>
	<!-- /TOPMENU -->
	<!-- HEADER -->
	<div id="header">
		<div id="header-left">
			<?= $this->fetchSlot('admin_menu'); ?>
		</div>
		<div id="logo"><a href="/"><?= e($site_name) ?> - <?= e($administration_label) ?></a></div>
		<div class="cleaner"></div>
	</div>
	<!-- /HEADER -->
	<!-- CONTENT -->
	<div class="content">
		<div class="content-full">
			<?= $this->fetchSlot('content'); ?>
		</div>
	</div>
	<!-- /CONTENT -->
	<br class="cleaner">
</div>
<?= $this->getRenderer()->getJs(); ?>
<?= $this->fetchSlot('page_chrome'); ?>
<script type="text/javascript">
	(function initTimezoneAndDateFormatting() {
		let clientTimezone = 'UTC';
		try {
			clientTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
		} catch (e) {
			clientTimezone = 'UTC';
		}

		window.radaptorDateTime = {
			clientTimezone: clientTimezone,
			formatUtc: function (utcIsoString, options, locale) {
				if (!utcIsoString) {
					return '';
				}
				const date = new Date(utcIsoString);
				if (Number.isNaN(date.getTime())) {
					return '';
				}
				return new Intl.DateTimeFormat(locale || undefined, options || {
					year: 'numeric',
					month: '2-digit',
					day: '2-digit',
					hour: '2-digit',
					minute: '2-digit',
					second: '2-digit',
				}).format(date);
			},
			timeAgoUtc: function (utcIsoString, locale) {
				if (!utcIsoString) {
					return '';
				}
				const date = new Date(utcIsoString);
				if (Number.isNaN(date.getTime())) {
					return '';
				}
				const diffSeconds = Math.round((date.getTime() - Date.now()) / 1000);
				const abs = Math.abs(diffSeconds);
				const rtf = new Intl.RelativeTimeFormat(locale || undefined, {numeric: 'auto'});

				if (abs < 60) return rtf.format(diffSeconds, 'second');
				if (abs < 3600) return rtf.format(Math.round(diffSeconds / 60), 'minute');
				if (abs < 86400) return rtf.format(Math.round(diffSeconds / 3600), 'hour');
				if (abs < 604800) return rtf.format(Math.round(diffSeconds / 86400), 'day');
				if (abs < 2629800) return rtf.format(Math.round(diffSeconds / 604800), 'week');
				if (abs < 31557600) return rtf.format(Math.round(diffSeconds / 2629800), 'month');
				return rtf.format(Math.round(diffSeconds / 31557600), 'year');
			}
		};
		document.dispatchEvent(new Event('radaptorDateTimeReady'));
	})();

renderSystemMessages();
</script>
<?= $this->getRenderer()->fetchClosingHtml(); ?>
