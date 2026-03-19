<?php assert(isset($this) && $this instanceof Template); ?>
<section class="plugin-demo hello-world-plugin-view">
	<h1><?= e($this->props['headline'] ?? 'Hello from plugin'); ?></h1>

	<p>This page is rendered by a plugin template.</p>

	<ul>
		<?php foreach (($this->props['points'] ?? []) as $point): ?>
			<li><?= e((string) $point); ?></li>
		<?php endforeach; ?>
	</ul>
</section>
