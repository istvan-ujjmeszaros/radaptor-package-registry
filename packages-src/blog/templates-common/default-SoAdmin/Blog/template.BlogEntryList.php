<?php assert(isset($this) && $this instanceof Template); ?>
<?php //$this->getView()->registerLibrary('_GAVICK');?>

<div class="module-content">
	<div class="nspMainPortalMode3 nspFs100">
		<div class="nspTitles">

			<?php foreach ($this->props['data'] as $blogEntry): ?>

				<div class="nspTitleBlock opened">
					<div class="nspTitleTab">
						<div class="nspDate"><?= $blogEntry['date']; ?></div>
						<div class="nspTitle">
							<a href="<?= $blogEntry['url']; ?>"><?= $blogEntry['title']; ?></a></div>
					</div>
					<div class="nspArtMore " id="nsp-nsp_173-tab-0" style="margin: 0 0 0 96px;opacity: 1; ">
						<div class="nspArtMain">
							<p class="nspText tleft fleft">
								<a href="<?= $blogEntry['url']; ?>"><?= $blogEntry['__description']; ?></a>
							</p>
							<a class="readon  fleft" href="<?= $blogEntry['url']; ?>"><?= e($this->strings['common.read_more']) ?></a>
						</div>
					</div>
				</div>

			<?php endforeach; ?>

		</div>
	</div>
</div>
