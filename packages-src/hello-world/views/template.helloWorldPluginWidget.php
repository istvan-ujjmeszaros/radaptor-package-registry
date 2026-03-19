<?php
assert(isset($this) && $this instanceof Template);

/*
 * To place this widget onto a page in the current CMS flow:
 *
 * 1. Open the resource tree and create or pick a target page, for example under /learn/.
 * 2. Switch that page into edit mode.
 * 3. Add the widget type "HelloWorldPluginDemo" into the desired slot.
 * 4. Save or reload the page so the widget tree is rendered again.
 *
 * The widget template then renders the final wrapper, while the shared
 * helloWorldPluginView template below acts like a reusable subcomponent.
 */

$subtemplate = new Template('helloWorldPluginView', $this->getRenderer(), $this->getWidgetConnection());
$subtemplate->props = [
	'headline' => $this->props['title'] ?? 'Hello widget',
	'lead' => $this->props['lead'] ?? '',
	'points' => $this->props['points'] ?? [],
	'footer_note' => $this->props['footer_note'] ?? '',
];
?>
<div class="plugin-demo hello-world-plugin-widget">
	<?= $subtemplate->fetch(); ?>
	<p><small>Widget connection id: <?= e((string) ($this->props['connectionId'] ?? 'n/a')); ?></small></p>
</div>
