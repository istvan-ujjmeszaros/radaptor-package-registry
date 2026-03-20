<?php assert(isset($this) && $this instanceof Template); ?>
<li><b><?= e($this->strings['ticket.history.change.contactperson']) ?></b>:
	<i><?= EntityContactperson::getName($this->props['modify']['old_value']); ?></i> =&gt;
	<i><?= EntityContactperson::getName($this->props['modify']['new_value']); ?></i></li>
