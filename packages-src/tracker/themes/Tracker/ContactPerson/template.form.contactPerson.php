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


	});
</script>
