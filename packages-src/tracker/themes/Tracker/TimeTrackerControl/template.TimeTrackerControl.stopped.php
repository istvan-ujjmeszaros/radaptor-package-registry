<?php assert(isset($this) && $this instanceof Template); ?>
<div id="timetracker-control-content" class="start">
	<h1><?= e($this->strings['timetracker.control.title']) ?></h1>
	<form method="post" data-controller="form-timezone" action="<?= ajax_url('timeTracker.start'); ?>">
		<table id="timetracker-input-holder" width="100%">
			<tr>
				<td>
					<input type="text" name="description" id="timetracker-description">
				</td>
				<td width="1px">
					<input type="hidden" name="ticket_id">
					<button class="timetracker-start"><?= e($this->strings['timetracker.control.start']) ?></button>
				</td>
			</tr>
		</table>
		<table id="timetracker-details">
			<tr>
				<td style="text-overflow:ellipsis;width:300px;">
					<input id="timetracker_ticket_id" type="hidden" name="ticket_id">
					<input id="timetracker_ticket" style="text-overflow:ellipsis;" type="text" name="ticket" placeholder="<?= e($this->strings['timetracker.control.ticket_placeholder']) ?>">
				</td>
			</tr>
			<tr class="foot">
				<td>
					<?= e($this->strings['timetracker.field.ticket.label']) ?>
				</td>
			</tr>
		</table>
	</form>
</div>

<script>
	$("#timetracker_ticket")
		// don't navigate away from the field on tab when selecting an item
		.bind("keydown", function (event) {
			if (event.keyCode === $.ui.keyCode.TAB &&
				$(this).data("ui-autocomplete").menu.active) {
				event.preventDefault();
			}
		})
		.bind("keyup", function (event) {
			if (event.keyCode !== $.ui.keyCode.ENTER)
				$("#timetracker_ticket_id").val('_' + this.value + '_')
		})
		.autocomplete({
			source: function (request, response) {
				$.getJSON("<?= Url::getAjaxUrl('timeTracker.ajax_ticketListAutocomplete'); ?>", {
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
				$("#timetracker_ticket").val(stripTags(ui.item.label));
				$("#timetracker_ticket_id").val(ui.item.value);
				return false;
			}
		})
		.after('<span class="select-downarrow"><?= Icons::get(IconNames::DROPDOWN); ?></span>')
		.data("ui-autocomplete")._renderItem = function (ul, item) {
		item.label = item.label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex($.trim(this.term)) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
		return $("<li></li>")
			.data("item.autocomplete", item)
			.append("<a>" + item.label + "</a>")
			.appendTo(ul);
	};

	$('.ui-autocomplete-input').bind("autocompleteclose", function () {
		$(this).data('is_open', false);
	});

	$('.select-downarrow').bind("click", function () {
		var input = $(this).prev();

		if ($(input).data('is_open')) {
			$(input).autocomplete("close");
			$(input).data('is_open', false);
		} else {
			$(input).autocomplete("search", " ");
			$(input).data('is_open', true);
		}
		$(input).focus();
	});

</script>
