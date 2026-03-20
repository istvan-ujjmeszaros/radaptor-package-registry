<?php

class WidgetContactPersonDescription extends AbstractWidget
{
	public const string ID = 'contact_person_description';
	public const bool VISIBILITY = true;

	/**
	 * @return array<string, string>
	 */
	public static function buildStrings(): array
	{
		return [
			'contact.description.widget_name' => t('contact.description.widget_name'),
			'contact.description.back_to_list' => t('contact.description.back_to_list'),
			'common.edit' => t('common.edit'),
			'common.id' => t('common.id'),
			'record_meta.created_by' => t('record_meta.created_by'),
			'common.no_data' => t('common.no_data'),
			'contact.col.company' => t('contact.col.company'),
			'record_meta.last_modified' => t('record_meta.last_modified'),
			'common.never' => t('common.never'),
			'contact.unknown' => t('contact.unknown'),
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
			'path' => '/contact-person/',
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
				'message' => $strings['contact.unknown'],
			]);
		}

		$contact_person_id = $extra_params['standalone'][0];

		$contactPerson = EntityContactperson::findById($contact_person_id);

		if (!$contactPerson) {
			return $this->buildStatusTree([
				'severity' => 'warning',
				'message' => $strings['contact.unknown'],
			]);
		}

		$contactPersonData = $contactPerson->data();
		$contactPersonData['company'] = EntityCompany::getLongName($contactPersonData['connected_company_id']);

		return $this->createComponentTree('contactPersonDescription', [
			'contactPersonData' => $contactPersonData,
			'modificationsList' => EntityContactperson::getAllModificationsData($contact_person_id),
		], strings: $strings);
	}
	public function canAccess(iTreeBuildContext $tree_build_context, WidgetConnection $connection): bool
	{
		return true;
	}
}
