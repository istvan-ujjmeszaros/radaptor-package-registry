<?php

class FormTypeProjectState extends FormCustomValidatorProjectState
{
	public const string ID = 'project_state';

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
			'path' => '/project-states/edit/',
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

				$projectState = EntityProject_stat::saveFromArray($this->savedata);
				$this->_item_id = $projectState->pkey();
				SystemMessages::addSystemMessage(t('project.state.saved'));

				break;

			case self::_MODE_UPDATE:

				EntityProject_stat::updateById($this->getItemId(), $this->savedata);
				SystemMessages::addSystemMessage(t('project.state.saved'));

				break;
		}
	}

	public function setMetadata(): void
	{
		if ($this->_mode == self::_MODE_CREATE) {
			$this->_meta->title = t('project.state.form.title_create');
		} else {
			$this->_meta->title = t('project.state.form.title_edit');
			$this->_meta->sub_title = EntityProject_stat::getName($this->getItemId());
		}
	}

	public function setInitValues(): void
	{
		$this->initvalues = EntityProject_stat::findById($this->getItemId())?->data() ?? [];
	}

	public function makeInputs(): void
	{
		$name = new FormInputText('name', $this);
		$name->label = t('project.state.field.name.label');
		$name->explanation = t('project.state.field.name.explanation');
		$name->addValidator(new FormValidatorNotEmpty(t('form.validation.required')));
		$v = new FormValidatorStringlength(t('project.state.validation.name_max_length'));
		$v->min = 0;
		$v->max = 64;
		$name->addValidator($v);
	}
}
