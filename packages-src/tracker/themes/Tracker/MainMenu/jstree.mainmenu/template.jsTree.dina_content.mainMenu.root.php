<?php assert(isset($this) && $this instanceof Template); ?>
<?php $mainmenu = MainMenu::factory($this->props['id'][0]); ?>

<h3><?= is_array($mainmenu) ? $mainmenu['node_name'] : e($this->strings['cms.menu.root']) ?></h3>
<br/>

<div class="buttons">

	<?php if (is_array($mainmenu)): ?>
		<button class="button right_img button_new_submenu" type="button" onclick="widgettype._.location('<?= Form::getSeoUrl(FormList::MAINMENUMENUELEMENT, null, null, ['ref_id' => $mainmenu['node_id']]); ?>');"><?= Icons::get(IconNames::PLUS); ?>
			<?= e($this->strings['cms.menu.new_child']) ?>
		</button>
	<?php else: ?>
		<button class="button right_img button_new_submenu" type="button" onclick="widgettype._.location('<?= Form::getSeoUrl(FormList::MAINMENUMENUELEMENT, null, null, ['ref_id' => 0]); ?>');"><?= Icons::get(IconNames::PLUS); ?>
			<?= e($this->strings['cms.menu.new_child']) ?>
		</button>
	<?php endif; ?>
	<br/>
	<br/>

</div>

<hr>
<br/>
