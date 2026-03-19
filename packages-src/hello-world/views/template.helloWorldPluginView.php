<?php
assert(isset($this) && $this instanceof Template);

/*
 * This template is used directly by EventHelloWorldPluginView.
 *
 * It can also be reused as a subcomponent from another template when the same
 * markup is useful in more than one place. The widget demo does exactly that.
 *
 * Notice the dedicated "strings" bag: resolved translations are passed to the
 * template under their original i18n keys instead of being flattened into
 * ad-hoc aliases. That keeps the connection to the source keys obvious.
 */

$strings = is_array($this->props['strings'] ?? null) ? $this->props['strings'] : [];
?>
<section class="plugin-demo hello-world-plugin-view">
	<h1><?= e((string) ($strings['hello_world.demo.headline'] ?? 'Hello from plugin')); ?></h1>

	<p><?= e((string) ($strings['hello_world.demo.lead'] ?? 'This page is rendered by a plugin template.')); ?></p>

	<ul>
		<?php foreach (($this->props['points'] ?? []) as $point): ?>
			<li><?= e((string) $point); ?></li>
		<?php endforeach; ?>
	</ul>

	<?php if (!empty($strings['hello_world.demo.footer_note'])): ?>
		<p><small><?= e((string) $strings['hello_world.demo.footer_note']); ?></small></p>
	<?php endif; ?>
</section>
