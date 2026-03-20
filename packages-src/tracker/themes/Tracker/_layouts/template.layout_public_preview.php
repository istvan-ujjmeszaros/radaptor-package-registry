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
	<!-- CONTENT -->
	<div class="content">
		<div class="content-full">
			<?= $this->fetchSlot('content'); ?>
		</div>
	</div>
	<!-- /CONTENT -->
	<br class="cleaner">
</div>
<?= $this->fetchSlot('page_chrome'); ?>
<script type="text/javascript">
	renderSystemMessages();
</script>
<?= $this->getRenderer()->fetchClosingHtml(); ?>
