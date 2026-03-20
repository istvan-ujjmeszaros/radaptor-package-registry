<?php assert(isset($this) && $this instanceof Template); ?>
<?= $this->getRenderer()->getLibraryDebugInfo(); ?>
<?= $this->getRenderer()->getCss(); ?>
<?= $this->getRenderer()->getJs(); ?>
<?= $this->fetchSlot('content'); ?>
