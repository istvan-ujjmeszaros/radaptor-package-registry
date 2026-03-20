<?php

class WidgetTicketStateList extends AbstractWidget
{
	public const string ID = 'ticket_state_list';
	public const bool VISIBILITY = true;

	/**
	 * @return array<string, string>
	 */
	public static function buildStrings(): array
	{
		return [
			'ticket.widget.state_list.name' => t('ticket.widget.state_list.name'),
			'ticket.state.form.title_create' => t('ticket.state.form.title_create'),
			'ticket.widget.list.name' => t('ticket.widget.list.name'),
			'ticket.state.field.name.label' => t('ticket.state.field.name.label'),
			'ticket.state.field.is_open.label' => t('ticket.state.field.is_open.label'),
			'common.actions' => t('common.actions'),
			'common.yes' => t('common.yes'),
			'common.no' => t('common.no'),
			'common.edit' => t('common.edit'),
			'datatable.info_filtered_html' => t('datatable.info_filtered_html'),
			'datatable.info_empty' => t('datatable.info_empty'),
			'datatable.info_full' => t('datatable.info_full'),
			'datatable.empty_table' => t('datatable.empty_table'),
			'datatable.first' => t('datatable.first'),
			'datatable.last' => t('datatable.last'),
			'datatable.next' => t('datatable.next'),
			'datatable.previous' => t('datatable.previous'),
			'datatable.search' => t('datatable.search'),
			'datatable.zero_records' => t('datatable.zero_records'),
			'datatable.displayed_columns' => t('datatable.displayed_columns'),
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
			'path' => '/ticket-states/',
			'resource_name' => 'index.html',
			'layout' => 'public_default',
		];
	}

	protected function buildAuthorizedTree(iTreeBuildContext $tree_build_context, WidgetConnection $connection, array $build_context = []): array
	{
		return $this->createComponentTree('ticketStateList', [
			'issueTypeList' => EntityTicket_stat::getList(),
		], strings: self::buildStrings());
	}
	public function canAccess(iTreeBuildContext $tree_build_context, WidgetConnection $connection): bool
	{
		return true;
	}
}
