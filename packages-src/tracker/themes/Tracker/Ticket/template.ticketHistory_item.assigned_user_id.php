<?php assert(isset($this) && $this instanceof Template); ?>
<li><b><?= e($this->strings['ticket.history.change.assignee']) ?></b>: <i><?= User::getUsername($this->props['modify']['old_value']); ?></i> =&gt;
	<i><?= User::getUsername($this->props['modify']['new_value']); ?></i></li>
