<?php assert(isset($this) && $this instanceof Template); ?>
<div id="timetracker-control-content" class="stop">
	<h1><?= e($this->strings['timetracker.control.title']) ?></h1>
	<form method="post" data-controller="form-timezone" action="<?= ajax_url('timeTracker.stop'); ?>">
		<table id="timetracker-input-holder" width="100%">
			<tr>
				<td>
					<input type="text" name="description" id="timetracker-description" value="<?= $this->props['data']['description']; ?>">
				</td>
				<td width="1px">
					<input type="text" name="time" id="timetracker-time">
				</td>
				<td width="1px">
					<input type="hidden" name="timetracker_start" value="<?= $this->props['data']['timetracker_start']; ?>">
					<input type="hidden" name="server_timestamp" value="<?= time(); ?>">
					<input type="hidden" name="ticket_id">
					<button class="timetracker-stop">
						<img src="<?= Config::PATH_CDN->value(); ?>_common/media/loader4.gif"><span><?= e($this->strings['timetracker.control.stop']) ?></span></button>
				</td>
			</tr>
		</table>
		<table id="timetracker-details">
			<tr>
				<td style="text-overflow:ellipsis;width:300px;">
					<input id="timetracker_ticket_id" type="hidden" name="ticket_id">
					<input id="timetracker_ticket" style="text-overflow:ellipsis;" type="text" name="ticket" placeholder="<?= e($this->strings['timetracker.control.ticket_placeholder']) ?>">
				</td>
				<td>
					<input type="text" name="time_from" style="width:40px;">
				</td>
				<td>
					<input type="text" name="time_to" style="width:40px;" readonly="readonly">
				</td>
				<td>
					<input type="text" name="time_date" value="" style="width:70px;">
				</td>
				<td rowspan="2" style="vertical-align: middle;">
					<a href="<?= event_url('TimeTracker.cancel'); ?>" title="<?= e($this->strings['timetracker.control.cancel_title']) ?>" onclick="if (!confirm(<?= htmlspecialchars(json_encode($this->strings['timetracker.control.cancel_confirm']), ENT_QUOTES | ENT_SUBSTITUTE); ?>)) return false;"><?= Icons::get(IconNames::TRASH); ?></a>
				</td>
			</tr>
			<tr class="foot">
				<td>
					<?= e($this->strings['timetracker.field.ticket.label']) ?>
				</td>
				<td width="1px">
					<?= e($this->strings['timetracker.list.start_time']) ?>
				</td>
				<td width="1px">
					<?= e($this->strings['timetracker.list.end_time']) ?>
				</td>
				<td width="1px">
					<?= e($this->strings['timetracker.field.date.label']) ?>
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

	var server_timestamp = $('input[name=server_timestamp]').val() * 1;
	var timestamp_correction = server_timestamp - Math.round(new Date().getTime() / 1000);
	var start_timestamp = $('input[name=timetracker_start]').val() * 1;
	var date = new Date((start_timestamp - timestamp_correction) * 1000);

	function pad2(n) { return String(n).padStart(2, '0'); }

	$('input[name=time_from]').val(pad2(date.getHours()) + ':' + pad2(date.getMinutes()));

	function renderTimeTrackerTimes() {
		var now = new Date();
		$('input[name=time_to]').val(pad2(now.getHours()) + ':' + pad2(now.getMinutes()));

		var current_timestamp = Math.round(new Date().getTime() / 1000) + timestamp_correction;
		var diff = current_timestamp - start_timestamp;

		var diff_days = Math.floor(diff / 86400);
		diff -= diff_days * 86400;

		var diff_hours = pad2(Math.floor(diff / 3600));
		diff -= diff_hours * 3600;

		var diff_minutes = pad2(Math.floor(diff / 60));
		diff -= diff_minutes * 60;

		var diff_seconds = pad2(Math.floor(diff));

		$('#timetracker-time').val(diff_hours + ':' + diff_minutes + ':' + diff_seconds);
	}

	renderTimeTrackerTimes();

	setInterval(renderTimeTrackerTimes, 1000);

	(function setLocalDateForTimeEntry() {
		var now = new Date();
		var y = now.getFullYear();
		var m = pad2(now.getMonth() + 1);
		var d = pad2(now.getDate());
		$('input[name=time_date]').val(y + '-' + m + '-' + d);
	})();

</script>
