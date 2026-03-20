<?php

class WidgetContactPersonList extends AbstractWidget
{
	public const string ID = 'contact_person_list';
	public const bool VISIBILITY = true;

	/**
	 * @return array<string, string>
	 */
	public static function buildStrings(): array
	{
		return [
			'contact.list.title' => t('contact.list.title'),
			'company.list.title' => t('company.list.title'),
			'contact.list.new' => t('contact.list.new'),
			'common.id' => t('common.id'),
			'contact.col.name' => t('contact.col.name'),
			'contact.col.company' => t('contact.col.company'),
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
			'datatable.column_visibility' => t('datatable.column_visibility'),
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
			'path' => '/contact-persons/',
			'resource_name' => 'index.html',
			'layout' => 'public_default',
		];
	}

	protected function buildAuthorizedTree(iTreeBuildContext $tree_build_context, WidgetConnection $connection, array $build_context = []): array
	{
		return $this->createComponentTree('contactPersonList', [
			'contactPersonList' => EntityContactperson::getListWithExtradata(),
		], strings: self::buildStrings());
	}
	public function canAccess(iTreeBuildContext $tree_build_context, WidgetConnection $connection): bool
	{
		return true;
	}
}
