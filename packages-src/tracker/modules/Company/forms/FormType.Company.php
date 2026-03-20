<?php

class FormTypeCompany extends FormCustomValidatorCompany
{
	public const string ID = 'company';

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
			'path' => '/companies/edit/',
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
				$company = EntityCompany::saveFromArray($this->savedata);
				$this->_item_id = $company->pkey();
				SystemMessages::addSystemMessage(t('company.saved'));

				break;

			case self::_MODE_UPDATE:
				EntityCompany::updateById($this->getItemId(), $this->savedata);
				SystemMessages::addSystemMessage(t('company.updated'));

				break;
		}
	}

	public function setMetadata(): void
	{
		if ($this->_mode == self::_MODE_CREATE) {
			$this->_meta->title = t('company.form.title_create');
		} else {
			$this->_meta->title = t('company.form.title_edit');
			$this->_meta->sub_title = EntityCompany::getLongName($this->getItemId());
		}
	}

	public function setInitValues(): void
	{
		$this->initvalues = EntityCompany::findById($this->getItemId())?->data();
	}

	public function makeInputs(): void
	{
		$name = new FormInputText('name', $this);
		$name->label = t('company.field.name.label');
		$name->explanation = t('company.field.name.explanation');
		$name->addValidator(new FormValidatorNotEmpty(t('form.validation.required')));
		$v = new FormValidatorStringlength(t('form.validation.max_length', ['max' => 255]));
		$name->addValidator($v);
		$v->min = 0;
		$v->max = 255;

		$shortname = new FormInputText('shortname', $this);
		$shortname->label = t('company.field.shortname.label');
		$shortname->explanation = t('company.field.shortname.explanation');
		$v = new FormValidatorStringlength(t('form.validation.max_length', ['max' => 32]));
		$shortname->addValidator($v);
		$v->min = 0;
		$v->max = 32;
	}
}
