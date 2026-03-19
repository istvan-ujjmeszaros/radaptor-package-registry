<?php
assert(isset($this) && $this instanceof Template);

/*
 * This template is used directly by EventHelloWorldPluginView.
 *
 * It can also be reused as a subcomponent from another template when the same
 * markup is useful in more than one place. The widget demo does exactly that.
 */
?>
<section class="plugin-demo hello-world-plugin-view">
	<h1><?= e($this->props['headline'] ?? 'Hello from plugin'); ?></h1>

	<p><?= e($this->props['lead'] ?? 'This page is rendered by a plugin template.'); ?></p>

	<ul>
		<?php foreach (($this->props['points'] ?? []) as $point): ?>
			<li><?= e((string) $point); ?></li>
		<?php endforeach; ?>
	</ul>

	<?php if (!empty($this->props['footer_note'])): ?>
		<p><small><?= e((string) $this->props['footer_note']); ?></small></p>
	<?php endif; ?>
</section>
