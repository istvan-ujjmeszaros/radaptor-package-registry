<?php

class EventTicketsTicketList extends AbstractEvent
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

		$ticketList = EntityTicket::getListWithExtradata($filtered_extraparams);

		$json_data = [];

		foreach ($ticketList as $row) {
			$json_data['aaData'][] = [
				sprintf('%04d', $row['id']),
				htmlspecialchars($row['state'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE),
				"<span style='display:none;'>{$row['priority_seq']}</span>\0{$row['priority']}",
				$row['start_date'],
				$row['end_date'],
				htmlspecialchars($row['type'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE),
				htmlspecialchars($row['project_name'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE),
				htmlspecialchars($row['title'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE),
				htmlspecialchars($row['contactperson'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE),
				htmlspecialchars($row['assigned_username'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE),
				$row['tags'],
				$row['id'],
				//			$row['description'],
			];
		}

		ApiResponse::renderSuccess($json_data);
	}
}
