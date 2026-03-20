<?php assert(isset($this) && $this instanceof Template); ?>
<div id="topmenu-right">
	<?php if (User::getCurrentUserUsername()): ?>
		<b><?= User::getCurrentUserUsername(); ?></b><span class="separator">|</span>
		<a href="<?= modify_url([
			'context' => 'user',
			'event' => 'logout',
			'referer' => Url::getCurrentUrlForReferer(),
		]); ?>"><?= e($this->strings['common.logout']) ?></a>
	<?php else: ?>
		<span class="separator">|</span>
		<a href="<?= form_url(FormList::USERLOGIN, null, null, ['loginreferer' => Url::getCurrentUrlForReferer()]); ?>"><?= e($this->strings['user.login.title']) ?></a>
	<?php endif; ?>
</div>
<div id="topmenu-left">
	<div class="topmenu-element">
		<a><?= e($this->strings['admin.menu.section.administration']) ?><span class="gbma"></span></a>
		<div class="menu-container">
			<ul>
				<li>
					<a href="<?= widget_url(WidgetList::PUBLICUSERLIST); ?>"><span><?= e($this->strings['user.list.title']) ?></span></a>
				</li>
				<li class="menu-separator"></li>
				<li>
					<a href="<?= widget_url(WidgetList::TIMETRACKERLIST); ?>"><span><?= e($this->strings['timetracker.list.title']) ?></span></a>
				</li>
				<li>
					<a href="<?= widget_url(WidgetList::CONTACTPERSONLIST); ?>"><span><?= e($this->strings['widget.contact_person_list.name']) ?></span></a>
				</li>
				<li>
					<a href="<?= widget_url(WidgetList::COMPANYLIST); ?>"><span><?= e($this->strings['widget.company_list.name']) ?></span></a>
				</li>
				<li class="menu-separator"></li>
				<li>
					<a href="<?= widget_url(WidgetList::TICKETLIST); ?>"><span><?= e($this->strings['widget.ticket_list.name']) ?></span></a>
				</li>
				<li>
					<a href="<?= widget_url(WidgetList::TICKETSTATELIST); ?>"><span><?= e($this->strings['widget.ticket_state_list.name']) ?></span></a>
				</li>
				<li>
					<a href="<?= widget_url(WidgetList::TICKETTYPELIST); ?>"><span><?= e($this->strings['widget.ticket_type_list.name']) ?></span></a>
				</li>
				<li>
					<a href="<?= widget_url(WidgetList::TICKETPRIORITYLIST); ?>"><span><?= e($this->strings['widget.ticket_priority_list.name']) ?></span></a>
				</li>
				<li class="menu-separator"></li>
				<li>
					<a href="<?= widget_url(WidgetList::PROJECTLIST); ?>"><span><?= e($this->strings['widget.project_list.name']) ?></span></a>
				</li>
				<li>
					<a href="<?= widget_url(WidgetList::PROJECTSTATELIST); ?>"><span><?= e($this->strings['widget.project_state_list.name']) ?></span></a>
				</li>
				<li>
					<a href="<?= widget_url(WidgetList::TAGLIST); ?>"><span><?= e($this->strings['widget.tag_list.name']) ?></span></a>
				</li>
				<!-- CustomQuery module removed - menu item deleted -->
			</ul>
		</div>
	</div><!--span class="separator">|</span-->
</div>
<script type="text/javascript">
	$(".topmenu-element").hover(
		function () {
			$(".menu-container", this).show("slide", {direction: "up"}, 150);
		},
		function () {
			$(".menu-container", this).hide("slide", {direction: "up"}, 100);
		}
	);
</script>
