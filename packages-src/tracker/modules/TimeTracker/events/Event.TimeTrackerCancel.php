<?php

class EventTimeTrackerCancel extends AbstractEvent
{
	public function authorize(PolicyContext $policyContext): PolicyDecision
	{
		return $policyContext->principal->hasRole(RoleList::ROLE_TIMETRACKER_ADMINISTRATOR)
			? PolicyDecision::allow()
			: PolicyDecision::deny();
	}

	public function run(): void
	{
		UserConfig::setConfig('TimeTracker', '');

		Kernel::redirectToReferer();
	}
}
