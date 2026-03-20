<?php assert(isset($this) && $this instanceof Template); ?>
<li><b><?= e($this->strings['ticket.history.change.date']) ?></b>: <i><?= $this->props['modify']['old_value']; ?></i> =&gt;
	<i><?= $this->props['modify']['new_value']; ?></i></li>
