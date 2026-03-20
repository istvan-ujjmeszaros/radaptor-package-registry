<?php

class EventTimeTrackerStart extends AbstractEvent
{
	public function authorize(PolicyContext $policyContext): PolicyDecision
	{
		return $policyContext->principal->hasRole(RoleList::ROLE_TIMETRACKER_ADMINISTRATOR)
			? PolicyDecision::allow()
			: PolicyDecision::deny();
	}

	public function run(): void
	{
		$ticket_id = trim((string) Request::_POST('ticket_id'), '_');

		if ($ticket_id !== Request::_POST('ticket_id') && !empty($ticket_id)) {
			$ticket_savedata = ['title' => $ticket_id, ];

			$ticket = EntityTicket::saveFromArray($ticket_savedata);
			$ticket_id = $ticket->pkey();

			SystemMessages::_notice("New ticket created");
		}

		$timetracker_data = [
			'description' => Request::_POST('description'),
			'connected_ticket_id' => $ticket_id == '' ? null : $ticket_id,
			'timetracker_start' => time(),
		];

		UserConfig::setConfig('TimeTracker', serialize($timetracker_data));

		Kernel::redirectToReferer();
	}
}
