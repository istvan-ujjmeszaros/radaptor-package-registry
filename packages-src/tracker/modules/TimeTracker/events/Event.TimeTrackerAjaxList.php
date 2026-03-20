<?php

class EventTimeTrackerAjaxList extends AbstractEvent
{
	public function authorize(PolicyContext $policyContext): PolicyDecision
	{
		return ($policyContext->principal->hasRole(RoleList::ROLE_TIMETRACKER_ADMINISTRATOR) || $policyContext->principal->hasRole(RoleList::ROLE_TIMETRACKER_VIEWER))
			? PolicyDecision::allow()
			: PolicyDecision::deny();
	}

	public function run(): void
	{
		$list = EntityTimetracker::getList();

		$json_data = [];

		foreach ($list as $row) {
			$start_time_data = explode(' ', (string) $row['start_time']);
			$end_time_data = explode(' ', (string) $row['end_time']);

			$username = '';

			if ($row['user_id'] !== null) {
				$user_data = User::getUserFromId($row['user_id']);
				$username = $user_data['username'] ?? '';
			}

			$json_data['aaData'][] = [
				$start_time_data[0],
				$row['connected_ticket_id'] == '' ? $row['description'] : '#' . $row['connected_ticket_id'] . ' - ' . $row['description'],
				mb_substr($start_time_data[1], 0, 5),
				mb_substr($end_time_data[1], 0, 5),
				'',
				$username,
				$row['id'],
			];
		}

		ApiResponse::renderSuccess($json_data);
	}
}
