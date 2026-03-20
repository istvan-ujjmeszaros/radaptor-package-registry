<?php

class EventTimeTrackerAjaxTicketListAutocomplete extends AbstractEvent
{
	public function authorize(PolicyContext $policyContext): PolicyDecision
	{
		return ($policyContext->principal->hasRole(RoleList::ROLE_TIMETRACKER_ADMINISTRATOR) || $policyContext->principal->hasRole(RoleList::ROLE_TIMETRACKER_VIEWER))
			? PolicyDecision::allow()
			: PolicyDecision::deny();
	}

	public function run(): void
	{
		$term = trim(urldecode((string) Request::_GET('term')), " +");
		$filtered = Request::_GET('filtered', true) == true;

		$list = EntityTicket::getListForSelect($term, $filtered);

		echo json_encode($list);
	}
}
