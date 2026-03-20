<?php assert(isset($this) && $this instanceof Template); ?>
<?php $this->registerLibrary(LibrariesTracker::__WIDEWORKS_TRACK); ?>
<!DOCTYPE html>
<html lang="<?= substr(Kernel::getLocale(), 0, 2) ?>">
<head>
	<title><?= $this->getTitle() ?></title>
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
			<?= $this->fetchSlot('top_menu'); ?>
		</div>
	</div>
	<!-- /TOPMENU -->
	<!-- TIMETRACKERCONTROL -->
	<div id="timetracker-control">
		<?= $this->fetchSlot('time_tracker_control'); ?>
	</div>
	<!-- /TIMETRACKERCONTROL -->
	<!-- HEADER -->
	<div id="header">
		<?= $this->fetchSlot('header_right'); ?>
		<div id="header-left">
			<?= $this->fetchSlot('main_menu'); ?>
		</div>
		<div id="logo"><a href="/"><?= Config::APP_SITE_NAME->value(); ?></a></div>
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
<?= $this->getRenderer()->getJsBottom(); ?>
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
