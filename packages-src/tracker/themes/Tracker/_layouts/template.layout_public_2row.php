<?php assert(isset($this) && $this instanceof Template); ?>
<?php $this->registerLibrary(LibrariesTracker::__WIDEWORKS_TRACK); ?>
<!DOCTYPE html>
<html lang="<?= substr(Kernel::getLocale(), 0, 2) ?>">
<head>
	<title><?= Config::APP_SITE_NAME->value(); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<?= $this->getRenderer()->getLibraryDebugInfo(); ?>
	<?= $this->getRenderer()->getCss(); ?>
	<?= $this->getRenderer()->getJs(); ?>
	<!--link rel="stylesheet" href="debug.css" type="text/css"-->

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
	<!-- HEADER -->
	<div id="header">
		<?= $this->fetchSlot('header_right'); ?>
		<div id="header-left">
			<?= $this->fetchSlot('main_menu'); ?>
		</div>
		<div id="logo"><a href="<?= Config::PATH_PUBLIC_SITE_ROOT->value(); ?>"><?= Config::APP_SITE_NAME->value(); ?></a></div>
		<div class="cleaner"></div>
	</div>
	<!-- /HEADER -->
	<!-- CONTENT -->
	<div class="content">
		<div class="content-wide">
			<?= $this->fetchSlot('content'); ?>
		</div>
		<div class="content-narrow">
			<?= $this->fetchSlot('narrow'); ?>
		</div>
	</div>
	<!-- /CONTENT -->
	<br class="cleaner">
</div>
<script type="text/javascript">
	renderSystemMessages();
</script>
<?= $this->fetchSlot('page_chrome'); ?>
<?= $this->getRenderer()->fetchClosingHtml(); ?>
