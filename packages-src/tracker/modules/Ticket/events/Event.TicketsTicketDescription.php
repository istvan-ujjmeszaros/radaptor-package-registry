<?php

class EventTicketsTicketDescription extends AbstractEvent
{
	public function authorize(PolicyContext $policyContext): PolicyDecision
	{
		return $policyContext->principal->hasRole(RoleList::ROLE_SYSTEM_ADMINISTRATOR)
			? PolicyDecision::allow()
			: PolicyDecision::deny();
	}

	public function run(): void
	{
		$issue_id_parts = explode('_', (string) Request::_GET('item_id', Request::DEFAULT_ERROR));

		$issue_id = ParamValidator::validateIntegerOrAbort($issue_id_parts[1] ?? '');

		$ticketData = EntityTicket::findById($issue_id)?->data() ?? [];

		$json_data = [];

		$json_data ['title'] = $ticketData['title'] == '' ? '<i>' . t('ticket.no_subject') . '</i>' : $ticketData['title'];

		if (trim(strip_tags((string) $ticketData['description'])) == '') {
			$json_data['text'] = '<i>' . t('ticket.no_description') . '</i>';
		} else {
			$json_data['text'] = $ticketData['description'];
		}

		ApiResponse::renderSuccess($json_data);
	}
}
