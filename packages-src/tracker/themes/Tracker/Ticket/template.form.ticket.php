<?php assert(isset($this) && $this instanceof Template); ?>
<script type="text/javascript">
	$(function () {
		$("#<?= $this->form->getRowId('assigned_user'); ?>").append('<a href="<?= form_url(FormList::USER, null, Url::getAjaxUrl('form.close')); ?>" target="_blank" class="controller-menu"><?= htmlspecialchars($this->strings['user.form.title_create'], ENT_QUOTES | ENT_SUBSTITUTE); ?></a><br>');

		$("#<?= $this->form->getRowId('project_name'); ?>").append('<a href="<?= form_url(FormList::PROJECT, null, Url::getAjaxUrl('form.close')); ?>" target="_blank" class="controller-menu"><?= htmlspecialchars($this->strings['project.form.title_create'], ENT_QUOTES | ENT_SUBSTITUTE); ?></a><br>');

		$("#<?= $this->form->getRowId('connected_contactperson'); ?>").append('<a href="<?= form_url(FormList::CONTACTPERSON, null, Url::getAjaxUrl('form.close')); ?>" target="_blank" class="controller-menu"><?= htmlspecialchars($this->strings['contact.form.title_create'], ENT_QUOTES | ENT_SUBSTITUTE); ?></a><br>');

		$("#<?= $this->form->getInputId('tags'); ?>")
			// don't navigate away from the field on tab when selecting an item
			.bind("keydown", function (event) {
				if (event.keyCode === $.ui.keyCode.TAB &&
					$(this).data("ui-autocomplete").menu.active) {
					event.preventDefault();
				}
			})
			.autocomplete({
				source: function (request, response) {
					$.getJSON("<?= Url::getAjaxUrl('tags.ajax', ['tag_context' => 'tracker_ticket']); ?>", {
						term: extractLast(request.term)
					}, function(payload) {
						response(payload.ok ? (payload.data || []) : []);
					});
				},
				search: function () {
					// custom minLength
					var term = extractLast(this.value);
				},
				focus: function () {
					// prevent value inserted on focus
					return false;
				},
				select: function (event, ui) {
					var terms = split(this.value);
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push(ui.item.value);
					// add placeholder to get the comma-and-space at the end
					terms.push("");
					this.value = terms.join(", ");
					return false;
				},
				open: function (event, ui) {
					$('.tooltip-issue-tag').each(function () {
						var item_id = encodeURIComponent($(this).attr('alt'));

						$(this).qtip(
							{
								position:
									{
										at: 'top right', // Position the tooltip above the link
										my: 'bottom left',
										viewport: $(window),
										effect: false
									},
								content:
									{
										text: '<?= Config::PATH_AJAX_LOADER_HTML->value(); ?>',
										ajax:
											{
												url: '<?= Url::getAjaxUrl('tags.tagDescription', ['tag_context' => 'tracker_ticket']); ?>' + '&item_id=' + item_id
											}
									},
								style: {
									classes: 'ui-tooltip-shadow'
								},
								show:
									{
										solo: true
									}
							});
					});
				}
			})
			.after('<span class="select-downarrow"><?= Icons::get(IconNames::DROPDOWN); ?></span>')
			.data("ui-autocomplete")._renderItem = function (ul, item) {
			plain_label = item.label;
			item.label = item.label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex($.trim(this.term)) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
			return $("<li></li>")
				.data("item.autocomplete", item)
				.append("<a>" + item.label + "<span style=\"float:right\"><img src=\"<?= Icons::path(IconNames::HELP) ?>\" class=\"tooltip-issue-tag\" alt=\"" + plain_label + "\"></span></a>")
				.appendTo(ul);
		};

		$('.ui-autocomplete-input').bind("autocompleteclose", function () {
			$(this).data('is_open', false);
		});

	});

</script>
