<?php

class EventProjectsAjaxProjectListAutocomplete extends AbstractEvent
{
	public function authorize(PolicyContext $policyContext): PolicyDecision
	{
		return $policyContext->principal->hasRole(RoleList::ROLE_SYSTEM_ADMINISTRATOR)
			? PolicyDecision::allow()
			: PolicyDecision::deny();
	}

	public function run(): void
	{
		$term = trim(urldecode((string) Request::_GET('term')), " +");

		$list = EntityProject::getListForSelect(['name LIKE' => "%{$term}%"]);

		echo json_encode($list);
	}
}
