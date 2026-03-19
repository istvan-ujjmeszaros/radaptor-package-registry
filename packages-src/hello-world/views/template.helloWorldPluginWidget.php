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

$strings = is_array($this->props['strings'] ?? null) ? $this->props['strings'] : [];
$subtemplate = new Template('helloWorldPluginView', $this->getRenderer(), $this->getWidgetConnection());
$subtemplate->props = [
	'strings' => $strings,
	'points' => $this->props['points'] ?? [],
];
?>
<div class="plugin-demo hello-world-plugin-widget">
	<?= $subtemplate->fetch(); ?>
	<p>
		<small>
			<?= e((string) ($strings['hello_world.widget.connection_id_label'] ?? 'Widget connection id')); ?>:
			<?= e((string) ($this->props['connectionId'] ?? 'n/a')); ?>
		</small>
	</p>
</div>
