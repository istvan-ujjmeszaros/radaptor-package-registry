<?php assert(isset($this) && $this instanceof Template); ?>
<div class="plugin-demo hello-world-plugin-widget">
	<h2><?= e($this->props['title'] ?? 'Hello widget'); ?></h2>
	<p><?= e($this->props['body'] ?? ''); ?></p>
	<p><small>Widget connection id: <?= e((string) ($this->props['connectionId'] ?? 'n/a')); ?></small></p>
</div>
