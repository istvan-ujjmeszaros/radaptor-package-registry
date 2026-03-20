<?php

class FormTypeContactPerson extends FormCustomValidatorContactPerson
{
	public const string ID = 'contact_person';

	/**
	 * @return array<string, string>
	 */
	public static function buildExtensionStrings(): array
	{
		return [
			'company.form.title_create' => t('company.form.title_create'),
		];
	}

	public static function getName(): string
	{
		return t('form.' . self::ID . '.name');
	}

	public static function getDescription(): string
	{
		return t('form.' . self::ID . '.description');
	}

	public static function getListVisibility(): bool
	{
		return Roles::hasRole(RoleList::ROLE_SYSTEM_DEVELOPER);
	}

	public static function getDefaultPathForCreation(): array
	{
		return [
			'path' => '/contact-persons/edit/',
			'resource_name' => 'index.html',
			'layout' => 'public_default',
		];
	}

	public function hasRole(): bool
	{
		return Roles::hasRole(RoleList::ROLE_SYSTEM_DEVELOPER);
	}

	public function commit(): void
	{
		switch ($this->getMode()) {
			case self::_MODE_CREATE:

				$contactPerson = EntityContactperson::saveFromArray($this->savedata);
				$this->_item_id = $contactPerson->pkey();
				SystemMessages::addSystemMessage(t('contact.saved'));

				break;

			case self::_MODE_UPDATE:

				EntityContactperson::updateById($this->getItemId(), $this->savedata);
				SystemMessages::addSystemMessage(t('contact.updated'));

				break;
		}
	}

	public function setMetadata(): void
	{
		if ($this->_mode == self::_MODE_CREATE) {
			$this->_meta->title = t('contact.form.title_create');
		} else {
			$this->_meta->title = t('contact.form.title_edit');
			$this->_meta->sub_title = EntityContactperson::getName($this->getItemId());
		}
	}

	public function setInitValues(): void
	{
		$this->initvalues = EntityContactperson::findById($this->getItemId())?->data() ?? [];

		if ($this->initvalues['connected_company_id']) {
			$connected_company_data = EntityCompany::findById($this->initvalues['connected_company_id'])?->data();

			if ($connected_company_data) {
				$this->initvalues['connected_company'] = $connected_company_data['name'] . ' (' . $connected_company_data['shortname'] . ')';
			}
		}
	}

	public function makeInputs(): void
	{
		$name = new FormInputText('name', $this);
		$name->label = t('contact.field.name.label');
		$name->explanation = t('contact.field.name.explanation');
		$name->addValidator(new FormValidatorNotEmpty(t('form.validation.required')));
		$v = new FormValidatorStringlength(t('form.validation.max_length', ['max' => 255]));
		$name->addValidator($v);
		$v->min = 0;
		$v->max = 255;

		$connected_company_id = new FormInputText('connected_company_id', $this);
		$connected_company_id->label = t('contact.field.company_id.label');

		$connected_company = new FormInputText('connected_company', $this);
		$connected_company->save = false;
		$connected_company->label = t('contact.field.company.label');
		$connected_company->explanation = t('contact.field.company.explanation');
	}
}
