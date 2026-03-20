<?php assert(isset($this) && $this instanceof Template); ?>
<?php
$mainmenu = MainMenu::factory($this->props['id'][0]);

if (is_null($mainmenu)) {
	return true;
}
?>

<h3><?= $mainmenu['node_name']; ?></h3>
<br/>

<div class="buttons">

	<button <?php if (is_null($mainmenu['page_id']) && is_null($mainmenu['url'])): ?>disabled <?php endif; ?>class="main_button button_preview" type="button" onclick="widgettype._.openWindow('<?= MainMenu::getUrl($mainmenu['node_id']); ?>');"><?= Icons::get(IconNames::LOOK); ?>
		<?= e($this->strings['common.preview']) ?>
	</button>
	<br/>
	<br/>
	<button class="button right_img button_edit" type="button" onclick="widgettype._.location('<?= Form::getSeoUrl(FormList::MAINMENUMENUELEMENT, $mainmenu['node_id']); ?>');"><?= Icons::get(IconNames::EDIT); ?>
		<?= e($this->strings['common.edit']) ?>
	</button>
	<br/>
	<br/>
	<script type="text/javascript">
		var id_json = <?= json_encode($this->props['id']); ?>;
	</script>
	<button class="button right_img button_delete" type="button" onclick="widgettype.jstree.deleteRecursive('<?= $this->props['jstree_id']; ?>', id_json, '<?= Url::getAjaxUrl('Jstree.mainMenuAjaxDeleteRecursive'); ?>');"><?= Icons::get(IconNames::DELETE); ?>
		<?= e($this->strings['common.delete']) ?>
	</button>
	<br/>
	<br/>

</div>

<hr>
<br/>

<?php if (!is_null($mainmenu['url'])): ?>
	<h4><?= e($this->strings['cms.menu.external_link']) ?></h4>
	<small><?= MainMenu::getUrl($mainmenu['node_id']); ?></small>
<?php elseif (is_null($mainmenu['page_id'])): ?>
	<h4 style="color:red;"><?= e($this->strings['cms.menu.no_link_configured']) ?></h4>
<?php else: ?>
	<h4><?= e($this->strings['cms.menu.internal_link']) ?></h4>
	<small><?= MainMenu::getUrl($mainmenu['node_id'], false); ?></small>
<?php endif; ?>

