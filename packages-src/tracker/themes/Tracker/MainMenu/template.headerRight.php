<?php assert(isset($this) && $this instanceof Template); ?>
<div id="header-right">
	<div class="headermenu">
		<ul>
			<li<?php if (mb_strpos((string) $this->props['currentUrl'], '/admin/') === 0): ?> class="active"<?php endif; ?>>
				<a href="/admin/"><?= e($this->strings['admin.menu.home']) ?></a></li>
		</ul>
	</div>
</div>
