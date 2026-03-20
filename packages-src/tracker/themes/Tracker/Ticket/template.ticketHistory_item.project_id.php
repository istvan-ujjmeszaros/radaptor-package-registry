<?php assert(isset($this) && $this instanceof Template); ?>
<li><b><?= e($this->strings['ticket.history.change.project']) ?></b>: <i><?= EntityProject::getName($this->props['modify']['old_value']); ?></i> =&gt;
	<i><?= EntityProject::getName($this->props['modify']['new_value']); ?></i></li>
