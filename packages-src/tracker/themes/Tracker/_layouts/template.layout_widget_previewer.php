<?php assert(isset($this) && $this instanceof Template); ?>
<?php $this->registerLibrary('__COMMON_ADMIN'); ?>
<?php
$lang = (string)($this->props['lang'] ?? 'en');
$site_name = (string)($this->props['site_name'] ?? Config::APP_SITE_NAME->value());
$document_title = (string)($this->props['document_title'] ?? $site_name);
?>
<!DOCTYPE html>
<html lang="<?= e($lang) ?>">
<head>
	<meta charset="UTF-8">
	<title><?= e($document_title) ?></title>
	<meta name="robots" content="NOINDEX, NOFOLLOW">
	<meta name="viewport" content="width=device-width">
	<?= $this->getRenderer()->getLibraryDebugInfo(); ?>
	<?= $this->getRenderer()->getCss(); ?>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<?= $this->getRenderer()->getJs(); ?>
</head>
<body class="widget-preview">
	<div style="max-width: 1000px; margin: 0 auto; padding: 24px 16px;">
		<?= $this->fetchSlot('content'); ?>
	</div>
	<?= $this->fetchSlot('page_chrome'); ?>
</body>
</html>
