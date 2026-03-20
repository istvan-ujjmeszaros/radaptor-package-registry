<?php

class FormTypeProject extends FormCustomValidatorProject
{
	public const string ID = 'project';

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
			'path' => '/projects/edit/',
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
		$tags = EntityTag::extractTagsFromString($this->getInput('tags'));

		Audit::beginModificationGroup('tracker_project', $this->getItemId());

		switch ($this->getMode()) {
			case self::_MODE_CREATE:

				$project = EntityProject::saveFromArray($this->savedata);
				$project_id = $project->pkey();
				$this->_item_id = $project_id;

				Audit::setModificationGroupConnectedData('tracker_project', $project_id);

				SystemMessages::addSystemMessage(t('project.saved'));

				EntityTag::updateAllTags('tracker_project', $project_id, $tags);

				break;

			case self::_MODE_UPDATE:

				if (EntityTag::updateAllTags('tracker_project', $this->getItemId(), $tags)) {
					SystemMessages::addSystemMessage(t('project.updated'));
				}

				EntityProject::updateById($this->getItemId(), $this->savedata);
				SystemMessages::addSystemMessage(t('project.updated'));

				break;
		}

		Audit::endModificationGroup();
	}

	public function setMetadata(): void
	{
		if ($this->_mode == self::_MODE_CREATE) {
			$this->_meta->title = t('project.form.title_create');
		} else {
			$this->_meta->title = t('project.form.title_edit');
			$this->_meta->sub_title = EntityProject::getName($this->getItemId());
		}
	}

	public function setInitValues(): void
	{
		$this->initvalues = EntityProject::findById($this->getItemId())?->data() ?? [];

		$this->initvalues['tags'] = EntityTag::getConnectedTagNamesString('tracker_project', $this->getItemId());

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
		$name->label = t('project.field.name.label');
		$name->explanation = t('project.field.name.explanation');
		$name->addValidator(new FormValidatorNotEmpty(t('form.validation.required')));
		$v = new FormValidatorStringlength(t('project.validation.name_max_length'));
		$v->min = 0;
		$v->max = 255;
		$name->addValidator($v);

		$project_state = new FormInputSelect('state', $this);
		$project_state->label = t('project.field.state.label');
		/* 		'no_required'=>1, */
		$project_state->values = EntityProject_stat::getListForSelect();
		$project_state->explanation = t('project.field.state.explanation');
		$project_state->addValidator(new FormValidatorSelected(t('project.validation.state_required')));

		$tags = new FormInputText('tags', $this);
		$tags->save = false;
		$tags->label = t('ticket.field.tags.label');
		$tags->explanation = t('ticket.form.tags_explanation');

		$connected_company_id = new FormInputText('connected_company_id', $this);
		$connected_company_id->label = t('company.field.company_id.label');

		$connected_company = new FormInputText('connected_company', $this);
		$connected_company->save = false;
		$connected_company->label = t('project.field.company.label');
		$connected_company->explanation = t('project.field.company.explanation');

		if ($this->getMode() == self::_MODE_UPDATE) {
			$__comment = new FormInputTextarea('#comment', $this);
			$__comment->label = t('project.field.comment.label');
			$__comment->editor = FormInputTextarea::EDITOR_AUTO;
			$__comment->toolbar = FormInputTextarea::TOOLBAR_TEXTONLY;
		}
	}
}
