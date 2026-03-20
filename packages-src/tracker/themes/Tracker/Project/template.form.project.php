<?php assert(isset($this) && $this instanceof Template); ?>
<script type="text/javascript">
	$(function () {
		function split(val) {
			return val.split(/,\s*/);
		}

		function extractLast(term) {
			return split(term).pop();
		}

		$("#<?= $this->form->getRowId('connected_company_id'); ?>").hide();
		$("#<?= $this->form->getRowId('connected_company'); ?>").append('<a href="<?= form_url(FormList::COMPANY, null, Url::getAjaxUrl('form.close')); ?>" target="_blank" class="controller-menu"><?= htmlspecialchars($this->strings['company.form.title_create'], ENT_QUOTES | ENT_SUBSTITUTE); ?></a><br>');

		$("#<?= $this->form->getInputId('connected_company'); ?>")
			// don't navigate away from the field on tab when selecting an item
			.bind("keydown", function (event) {
				if (event.keyCode === $.ui.keyCode.TAB &&
					$(this).data("ui-autocomplete").menu.active) {
					event.preventDefault();
				}
			})
			.bind("keyup", function (event) {
				if (event.keyCode !== $.ui.keyCode.ENTER)
					$("#<?= $this->form->getInputId('connected_company_id'); ?>").val('_' + this.value + '_')
			})
			.autocomplete({
				source: function (request, response) {
					$.getJSON("<?= Url::getAjaxUrl('companies.ajax_companyListAutocomplete'); ?>", {
						term: extractLast(request.term)
					}, response);
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
					$("#<?= $this->form->getInputId('connected_company'); ?>").val(stripTags(ui.item.label));
					$("#<?= $this->form->getInputId('connected_company_id'); ?>").val(ui.item.value);
					return false;
				}
			})
			.data("ui-autocomplete")._renderItem = function (ul, item) {
			item.label = item.label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex($.trim(this.term)) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
			return $("<li></li>")
				.data("item.autocomplete", item)
				.append("<a>" + item.label + "</a>")
				.appendTo(ul);
		};


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
					$.getJSON("<?= Url::getAjaxUrl('tags.ajax', ['tag_context' => 'tracker_project']); ?>", {
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
					$('.tooltip-ticket-tag').each(function () {
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
												url: '<?= Url::getAjaxUrl('tags.tagDescription', ['tag_context' => 'tracker_project']); ?>' + '&item_id=' + item_id
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
			.data("ui-autocomplete")._renderItem = function (ul, item) {
			plain_label = item.label;
			item.label = item.label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex($.trim(this.term)) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
			return $("<li></li>")
				.data("item.autocomplete", item)
				.append("<a>" + item.label + "<span style=\"float:right\"><img src=\"<?= Icons::path(IconNames::HELP) ?>\" class=\"tooltip-ticket-tag\" alt=\"" + plain_label + "\"></span></a>")
				.appendTo(ul);
		};
	});
</script>
