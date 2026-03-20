<?php assert(isset($this) && $this instanceof Template); ?>
<?php $this->setDebugType(TemplateDebugType::DEBUG_HTML); ?>
<?php
$this->registerLibrary('__ADMIN_MAINMENU');

$jstreeId = $this->props['jstree_id'];
$ajaxUrl = Url::getAjaxUrl("jstree.mainMenuAjax");
?>
<div class="box">
	<div class="dina_content" id="dina_content<?= e($jstreeId); ?>" style="width:230px;float:left;margin:0 0 30px 10px;overflow:auto;">
		<i></i>
	</div>
	<div style="float:left;overflow:auto;width:530px;height:450px;margin-left:10px;">
		<h3><i><?= e($this->strings['cms.menu.root']) ?></i></h3>
		<br/>

		<div id="<?= e($jstreeId); ?>" style="margin:15px 0;"
			 data-controller="jstree-mainmenu"
			 data-jstree-mainmenu-jstree-id-value="<?= e($jstreeId); ?>"
			 data-jstree-mainmenu-ajax-url-value="<?= e($ajaxUrl); ?>Load"
			 data-jstree-mainmenu-move-url-value="<?= e($ajaxUrl); ?>Move"
			 data-jstree-mainmenu-detail-url-value="<?= e($ajaxUrl); ?>DinaContent"
			 data-jstree-mainmenu-delete-url-value="<?= e($ajaxUrl); ?>DeleteRecursive">
		</div>
	</div>
	<br/>
	<br/>
	<div style="clear:both;"></div>
</div>
