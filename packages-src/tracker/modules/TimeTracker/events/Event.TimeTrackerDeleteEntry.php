<?php

class EventTimeTrackerDeleteEntry extends AbstractEvent
{
	public function authorize(PolicyContext $policyContext): PolicyDecision
	{
		return $policyContext->principal->hasRole(RoleList::ROLE_TIMETRACKER_ADMINISTRATOR)
			? PolicyDecision::allow()
			: PolicyDecision::deny();
	}

	public function run(): void
	{
		$item_id = Request::_GET('item_id', Request::DEFAULT_ERROR);

		if (EntityTimetracker::deleteEntry($item_id)) {
			SystemMessages::_ok(t('timetracker.deleted'));
		} else {
			SystemMessages::_error('Error: Unable to delete time entry');
		}

		Kernel::redirectToReferer();
	}
}
