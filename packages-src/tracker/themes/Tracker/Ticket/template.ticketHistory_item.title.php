<?php assert(isset($this) && $this instanceof Template); ?>
<li><b><?= e($this->strings['ticket.history.change.subject']) ?></b>: <i><?= htmlspecialchars($modify['old_value'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE); ?></i> =&gt;
	<i><?= htmlspecialchars($modify['new_value'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE); ?></i></li>
