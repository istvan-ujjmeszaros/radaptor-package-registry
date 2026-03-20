<?php

class EventProjectsProjectList extends AbstractEvent
{
	public function authorize(PolicyContext $policyContext): PolicyDecision
	{
		return $policyContext->principal->hasRole(RoleList::ROLE_SYSTEM_ADMINISTRATOR)
			? PolicyDecision::allow()
			: PolicyDecision::deny();
	}

	public function run(): void
	{
		$filtered_extraparams = unserialize(urldecode((string) Request::_GET('filtered_extraparams', Request::DEFAULT_ERROR)));

		$projectList = EntityProject::getListWithExtradata($filtered_extraparams);

		$json_data = [];

		//var_dump($projectList);
		foreach ($projectList as $row) {
			$json_data['aaData'][] = [
				sprintf('%04d', $row['id']),
				$row['name'],
				$row['project_state'],
				$row['company_name'],
				$row['shortname'],
				$row['tags'],
				$row['tags_tickets'],
				$row['all_tickets'],
				$row['open_tickets'],
				$row['id'],
			];
		}

		ApiResponse::renderSuccess($json_data);
	}
}
