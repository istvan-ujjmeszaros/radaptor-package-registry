<?php

class WidgetTimeTrackerList extends AbstractWidget
{
	public const string ID = 'time_tracker_list';
	public const bool VISIBILITY = true;

	/**
	 * @return array<string, string>
	 */
	public static function buildStrings(): array
	{
		return [
			'timetracker.list.title' => t('timetracker.list.title'),
			'timetracker.form.title_create' => t('timetracker.form.title_create'),
			'timetracker.field.date.label' => t('timetracker.field.date.label'),
			'timetracker.field.description.label' => t('timetracker.field.description.label'),
			'timetracker.list.start_time' => t('timetracker.list.start_time'),
			'timetracker.list.end_time' => t('timetracker.list.end_time'),
			'timetracker.list.duration' => t('timetracker.list.duration'),
			'common.user' => t('common.user'),
			'common.actions' => t('common.actions'),
			'datatable.loading_records' => t('datatable.loading_records'),
			'common.edit' => t('common.edit'),
			'common.delete' => t('common.delete'),
			'timetracker.delete_confirm' => t('timetracker.delete_confirm'),
			'timetracker.duration.hour' => t('timetracker.duration.hour'),
			'timetracker.duration.minute' => t('timetracker.duration.minute'),
			'timetracker.duration.less_than_one_minute' => t('timetracker.duration.less_than_one_minute'),
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
			'path' => '/timetracker/',
			'resource_name' => 'index.html',
			'layout' => 'public_default',
		];
	}

	protected function buildAuthorizedTree(iTreeBuildContext $tree_build_context, WidgetConnection $connection, array $build_context = []): array
	{
		return $this->createComponentTree('TimeTrackerList', [
			'entries' => EntityTimetracker::getList(),
		], strings: self::buildStrings());
	}
	public function canAccess(iTreeBuildContext $tree_build_context, WidgetConnection $connection): bool
	{
		return true;
	}
}
