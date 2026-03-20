<?php assert(isset($this) && $this instanceof Template); ?>

<div id="rt-mainbody">

	<h1><?= $this->props['data']['title']; ?></h1>
	<?= $this->props['data']['__content']; ?>

</div>
