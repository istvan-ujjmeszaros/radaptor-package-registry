<?php

class WidgetProjectList extends AbstractWidget
{
	public const string ID = 'project_list';
	public const bool VISIBILITY = true;

	/**
	 * @return array<string, string>
	 */
	public static function buildStrings(): array
	{
		return [
			'project.list.title' => t('project.list.title'),
			'common.all' => t('common.all'),
			'project.form.title_create' => t('project.form.title_create'),
			'project.state.form.name' => t('project.state.form.name'),
			'common.id' => t('common.id'),
			'project.field.name.label' => t('project.field.name.label'),
			'project.field.state.label' => t('project.field.state.label'),
			'project.field.company.label' => t('project.field.company.label'),
			'company.field.shortname.label' => t('company.field.shortname.label'),
			'ticket.field.tags.label' => t('ticket.field.tags.label'),
			'project.list.ticket_tags' => t('project.list.ticket_tags'),
			'project.list.total_tickets' => t('project.list.total_tickets'),
			'project.list.open_tickets' => t('project.list.open_tickets'),
			'common.actions' => t('common.actions'),
			'datatable.loading_records' => t('datatable.loading_records'),
			'common.edit' => t('common.edit'),
			'record_action.datasheet' => t('record_action.datasheet'),
			'record_action.versions' => t('record_action.versions'),
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
			'path' => '/projects/',
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
		$filtered_extraparams = Url::getExtraParamRealValues(EntityProject::getEnabledUrlParams(), Url::getExtraParams($tree_build_context));

		$extra_params = Url::getExtraParams($tree_build_context);

		$states = EntityProject_stat::getListForSelect();

		foreach ($states as $state) {
			if (in_array($state['label'], $extra_params['standalone'])) {
				$filtered_extraparams[$state['label']] = true;
			}
		}

		return $this->createComponentTree('projectList', [
			'filtered_extraparams' => $filtered_extraparams,
			'extraparams' => $extra_params,
			'states' => $states,
		], strings: self::buildStrings());
	}
	public function canAccess(iTreeBuildContext $tree_build_context, WidgetConnection $connection): bool
	{
		return true;
	}
}
