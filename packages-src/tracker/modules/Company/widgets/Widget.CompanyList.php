<?php

class WidgetCompanyList extends AbstractWidget implements iMockable
{
	public const string ID = 'company_list';
	public const bool VISIBILITY = true;

	/**
	 * @return array<string, string>
	 */
	public static function buildStrings(): array
	{
		return [
			'company.list.title' => t('company.list.title'),
			'contact.list.title' => t('contact.list.title'),
			'company.list.new' => t('company.list.new'),
			'common.id' => t('common.id'),
			'company.field.shortname.label' => t('company.field.shortname.label'),
			'company.col.name' => t('company.col.name'),
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
			'path' => '/companies/',
			'resource_name' => 'index.html',
			'layout' => 'public_default',
		];
	}

	protected function buildAuthorizedTree(iTreeBuildContext $tree_build_context, WidgetConnection $connection, array $build_context = []): array
	{
		return $this->createComponentTree('companyList', [
			'companyList' => EntityCompany::getList(),
		], strings: self::buildStrings());
	}

	public function buildMockTree(iTreeBuildContext $tree_build_context, WidgetConnection $connection, array $build_context = []): array
	{
		$faker = \Faker\Factory::create('hu_HU');

		return $this->createComponentTree('companyList', [
			'companyList' => array_map(fn () => [
				'company_id' => $faker->randomNumber(5),
				'name' => $faker->company(),
				'shortname' => strtoupper($faker->lexify('???')),
				'email' => $faker->companyEmail(),
				'phone' => $faker->phoneNumber(),
			], range(1, 5)),
		], strings: self::buildStrings());
	}
	public function canAccess(iTreeBuildContext $tree_build_context, WidgetConnection $connection): bool
	{
		return true;
	}
}
