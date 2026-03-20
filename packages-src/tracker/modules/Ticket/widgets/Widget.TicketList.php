<?php

class WidgetTicketList extends AbstractWidget
{
	public const string ID = 'ticket_list';
	public const bool VISIBILITY = true;

	/**
	 * @return array<string, string>
	 */
	public static function buildStrings(): array
	{
		return [
			'ticket.widget.list.name' => t('ticket.widget.list.name'),
			'common.all' => t('common.all'),
			'common.open' => t('common.open'),
			'ticket.form.title_create' => t('ticket.form.title_create'),
			'common.id' => t('common.id'),
			'ticket.field.state.label' => t('ticket.field.state.label'),
			'ticket.field.priority.label' => t('ticket.field.priority.label'),
			'ticket.field.start_date.label' => t('ticket.field.start_date.label'),
			'ticket.field.end_date.label' => t('ticket.field.end_date.label'),
			'ticket.field.type.label' => t('ticket.field.type.label'),
			'ticket.field.project.label' => t('ticket.field.project.label'),
			'ticket.field.subject.label' => t('ticket.field.subject.label'),
			'ticket.field.contactperson.label' => t('ticket.field.contactperson.label'),
			'ticket.field.assignee.label' => t('ticket.field.assignee.label'),
			'ticket.field.tags.label' => t('ticket.field.tags.label'),
			'common.actions' => t('common.actions'),
			'common.edit' => t('common.edit'),
			'record_action.datasheet' => t('record_action.datasheet'),
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
			'path' => '/tickets/',
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
		$filtered_extraparams = Url::getExtraParamRealValues(EntityTicket::getEnabledUrlParams(), Url::getExtraParams($tree_build_context));
		$extra_params = Url::getExtraParams($tree_build_context);

		if (in_array('nyitott', $extra_params['standalone'])) {
			$filtered_extraparams['nyitott'] = true;
		}

		return $this->createComponentTree('ticketList', [
			'filtered_extraparams' => $filtered_extraparams,
			'extraparams' => $extra_params,
		], strings: self::buildStrings());
	}
	public function canAccess(iTreeBuildContext $tree_build_context, WidgetConnection $connection): bool
	{
		return true;
	}
}
