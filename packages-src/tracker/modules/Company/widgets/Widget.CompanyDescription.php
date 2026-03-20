<?php

class WidgetCompanyDescription extends AbstractWidget
{
	public const string ID = 'company_description';
	public const bool VISIBILITY = true;

	/**
	 * @return array<string, string>
	 */
	public static function buildStrings(): array
	{
		return [
			'company.description.widget_name' => t('company.description.widget_name'),
			'company.description.back_to_list' => t('company.description.back_to_list'),
			'common.edit' => t('common.edit'),
			'common.id' => t('common.id'),
			'record_meta.created_by' => t('record_meta.created_by'),
			'common.no_data' => t('common.no_data'),
			'company.field.shortname.label' => t('company.field.shortname.label'),
			'record_meta.last_modified' => t('record_meta.last_modified'),
			'common.never' => t('common.never'),
			'company.unknown' => t('company.unknown'),
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
			'path' => '/company/',
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
		$strings = self::buildStrings();
		$extra_params = Url::getExtraParams($tree_build_context);

		if (!isset($extra_params['standalone'][0])) {
			return $this->buildStatusTree([
				'severity' => 'warning',
				'message' => $strings['company.unknown'],
			]);
		}

		$company_id = $extra_params['standalone'][0];

		return $this->createComponentTree('companyDescription', [
			'companyData' => EntityCompany::findById($company_id)?->data(),
			'extraparams' => $extra_params,
			'modificationsList' => EntityCompany::getAllModificationsData($company_id),
		], strings: $strings);
	}
	public function canAccess(iTreeBuildContext $tree_build_context, WidgetConnection $connection): bool
	{
		return true;
	}
}
