<?php assert(isset($this) && $this instanceof Template); ?>
<li><b><?= e($this->strings['ticket.history.change.priority']) ?></b>: <i><?= EntityTicket_priority::getName($this->props['modify']['old_value']); ?></i> =&gt;
	<i><?= EntityTicket_priority::getName($this->props['modify']['new_value']); ?></i></li>
