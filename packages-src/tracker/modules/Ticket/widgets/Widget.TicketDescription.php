<?php

class WidgetTicketDescription extends AbstractWidget
{
	public const string ID = 'ticket_description';
	public const bool VISIBILITY = true;

	/**
	 * @return array<string, string>
	 */
	public static function buildStrings(int $ticket_id): array
	{
		return [
			'ticket.description.heading' => t('ticket.description.heading', ['id' => $ticket_id]),
			'ticket.form.title_edit' => t('ticket.form.title_edit'),
			'ticket.no_subject' => t('ticket.no_subject'),
			'ticket.description.id_label' => t('ticket.description.id_label'),
			'record_meta.created_by' => t('record_meta.created_by'),
			'common.no_data' => t('common.no_data'),
			'ticket.field.assignee.label' => t('ticket.field.assignee.label'),
			'ticket.description.no_assignee' => t('ticket.description.no_assignee'),
			'record_meta.last_modified' => t('record_meta.last_modified'),
			'common.never' => t('common.never'),
			'ticket.field.contactperson.label' => t('ticket.field.contactperson.label'),
			'ticket.field.project.label' => t('ticket.field.project.label'),
			'ticket.field.start_date.label' => t('ticket.field.start_date.label'),
			'ticket.field.end_date.label' => t('ticket.field.end_date.label'),
			'ticket.field.type.label' => t('ticket.field.type.label'),
			'ticket.field.priority.label' => t('ticket.field.priority.label'),
			'ticket.field.tags.label' => t('ticket.field.tags.label'),
			'ticket.field.description.label' => t('ticket.field.description.label'),
			'ticket.history.title' => t('ticket.history.title'),
			'ticket.history.comment_prefix' => t('ticket.history.comment_prefix'),
			'ticket.history.tags_removed' => t('ticket.history.tags_removed'),
			'ticket.history.tags_added' => t('ticket.history.tags_added'),
			'ticket.history.change.assignee' => t('ticket.history.change.assignee'),
			'ticket.history.change.contactperson' => t('ticket.history.change.contactperson'),
			'ticket.history.change.description' => t('ticket.history.change.description'),
			'ticket.history.change.closed_date' => t('ticket.history.change.closed_date'),
			'ticket.history.change.project' => t('ticket.history.change.project'),
			'ticket.history.change.date' => t('ticket.history.change.date'),
			'ticket.history.change.priority' => t('ticket.history.change.priority'),
			'ticket.history.change.status' => t('ticket.history.change.status'),
			'ticket.history.change.type' => t('ticket.history.change.type'),
			'ticket.history.change.subject' => t('ticket.history.change.subject'),
		];
	}

	public static function getName(): string
	{
		return t('widget.' . self::ID . '.name');
	}

	public static function getDescription(): string
	{
		return t('widget.' . self::ID . '.description');
	}

	public static function getListVisibility(): bool
	{
		return Roles::hasRole(RoleList::ROLE_SYSTEM_ADMINISTRATOR);
	}

	public static function getDefaultPathForCreation(): array
	{
		return [
			'path' => '/ticket/',
			'resource_name' => 'index.html',
			'layout' => 'public_default',
		];
	}

	public static function isCatcher(): bool
	{
		return true;
	}

	protected function buildAuthorizedTree(iTreeBuildContext $tree_build_context, WidgetConnection $connection, array $build_context = []): array
	{
		if (Request::_GET('id')) {
			$id = Request::_GET('id');
		} else {
			$extra_params = Url::getExtraParams($tree_build_context);

			if (isset($extra_params['standalone'][0])) {
				$id = $extra_params['standalone'][0];
			} else {
				return $this->buildStatusTree([
					'severity' => 'warning',
					'message' => t('ticket.error.no_id'),
				]);
			}
		}

		$ticket_data_list = EntityTicket::getListWithExtradata(['id' => $id]);

		if (count($ticket_data_list) === 0) {
			return $this->buildStatusTree([
				'severity' => 'warning',
				'message' => t('ticket.error.not_found'),
			]);
		}

		$modifications_list = EntityTicket::getAllModificationsData($id);
		$strings = self::buildStrings((int) $id);
		$history_slots = [];

		foreach ($modifications_list as $key => $modification) {
			if ($key === 0 || !isset($modification['modify_data']['modifications']['data'])) {
				continue;
			}

			$slot_name = 'changes_' . $key;
			$history_slots[$slot_name] = [];

			foreach ($modification['modify_data']['modifications']['data'] as $col => $modify) {
				$history_slots[$slot_name][] = $this->createComponentTree("ticketHistory_item.{$col}", [
					'modify' => $modify,
				], strings: $strings);
			}
		}

		return $this->createComponentTree('ticketDescription', [
			'ticketData' => $ticket_data_list[0],
			'modificationsList' => $modifications_list,
		], $strings, [
			'history' => [
				$this->createComponentTree('ticketHistory', [
					'modificationsList' => $modifications_list,
				], $strings, $history_slots),
			],
		]);
	}
	public function canAccess(iTreeBuildContext $tree_build_context, WidgetConnection $connection): bool
	{
		return true;
	}
}
